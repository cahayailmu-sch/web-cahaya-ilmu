[CmdletBinding()]
param(
    [Parameter(Position = 0)]
    [string]$Message = '',

    [string]$Branch = 'main',
    [string]$RemoteHost = '147.93.80.80',
    [int]$RemotePort = 65002,
    [string]$RemoteUser = 'u841268813',
    [string]$RemotePath = '/home/u841268813/domains/cahayailmu-bpn.id/public_html'
)

$ErrorActionPreference = 'Stop'

$localBundle = $null
$remoteBundle = '/tmp/cahaya-ilmu-deploy-' + [DateTimeOffset]::Now.ToUnixTimeSeconds() + '.bundle'

function Write-Section {
    param([string]$Text)

    Write-Host "`n==> $Text" -ForegroundColor Cyan
}

function Assert-NativeSuccess {
    param([string]$Action)

    if ($LASTEXITCODE -ne 0) {
        throw "$Action gagal (exit code $LASTEXITCODE)."
    }
}

function Invoke-RemoteScript {
    param([string]$Script)

    $normalizedScript = $Script.Replace("`r`n", "`n").Replace("`r", "`n")
    $encodedScript = [Convert]::ToBase64String([Text.Encoding]::UTF8.GetBytes($normalizedScript))
    $remoteCommand = "echo '$encodedScript' | base64 -d | bash"

    & ssh -p $RemotePort "$RemoteUser@$RemoteHost" $remoteCommand
    Assert-NativeSuccess 'ssh deploy'
}

Push-Location $PSScriptRoot

try {
    foreach ($command in @('git', 'npm', 'ssh', 'scp')) {
        if (-not (Get-Command $command -ErrorAction SilentlyContinue)) {
            throw "Command '$command' tidak ditemukan di PATH."
        }
    }

    Write-Section 'Build asset frontend'
    & npm run build
    Assert-NativeSuccess 'npm run build'

    $currentBranch = (& git branch --show-current).Trim()

    if ($LASTEXITCODE -ne 0) {
        throw 'Gagal membaca branch Git saat ini.'
    }

    if ($currentBranch -ne $Branch) {
        throw "Branch lokal '$currentBranch' bukan '$Branch'. Pindah ke branch $Branch terlebih dahulu."
    }

    Write-Section 'Menyiapkan commit Git'
    & git add -A
    Assert-NativeSuccess 'git add -A'

    $changes = & git status --porcelain

    if ($LASTEXITCODE -ne 0) {
        throw 'Gagal membaca status Git.'
    }

    if ($changes) {
        if ([string]::IsNullOrWhiteSpace($Message)) {
            $Message = 'Deploy: ' + (Get-Date -Format 'yyyy-MM-dd HH:mm')
        }

        & git commit -m $Message
        Assert-NativeSuccess 'git commit'

        Write-Host "Commit dibuat: $Message"
    } else {
        Write-Host 'Tidak ada perubahan baru untuk di-commit.'
    }

    Write-Section "Push ke GitHub (origin/$Branch)"
    & git push origin $Branch
    Assert-NativeSuccess 'git push'

    $localHead = (& git rev-parse HEAD).Trim()
    Assert-NativeSuccess 'git rev-parse HEAD'

    Write-Section 'Membaca versi commit di server'
    $remoteHeadOutput = & ssh -p $RemotePort "$RemoteUser@$RemoteHost" "cd '$RemotePath' && git rev-parse HEAD"

    if ($LASTEXITCODE -ne 0) {
        throw "Tidak bisa membaca HEAD Git di server. Cek RemotePath: $RemotePath"
    }

    $remoteHead = ($remoteHeadOutput | Select-Object -Last 1).Trim()

    $bundleRemotePath = ''

    if ($remoteHead -ne $localHead) {
        & git merge-base --is-ancestor $remoteHead $localHead

        if ($LASTEXITCODE -ne 0) {
            throw "Commit server ($remoteHead) bukan ancestor dari commit lokal ($localHead). Deploy dibatalkan agar tidak menimpa riwayat server."
        }

        Write-Section 'Membuat bundle update Git'
        $localBundle = Join-Path $env:TEMP ('cahaya-ilmu-deploy-' + [DateTimeOffset]::Now.ToUnixTimeSeconds() + '.bundle')

        & git bundle create $localBundle "refs/heads/$Branch" "^$remoteHead"
        Assert-NativeSuccess 'git bundle create'

        Write-Section 'Upload bundle ke server'
        & scp -P $RemotePort $localBundle "$RemoteUser@$RemoteHost`:$remoteBundle"
        Assert-NativeSuccess 'scp bundle'

        $bundleRemotePath = $remoteBundle
    } else {
        Write-Host 'Server sudah berada di commit yang sama.'
    }

    $remoteScript = @'
set -euo pipefail

PROJECT_DIR="__REMOTE_PATH__"
BUNDLE_PATH="__BUNDLE_PATH__"

cd "$PROJECT_DIR"
echo "Folder project: $PROJECT_DIR"

if [ -n "$(git status --porcelain --untracked-files=no)" ]; then
  echo "ERROR: Ada perubahan pada file yang dilacak Git di server. Deploy dibatalkan." >&2
  git status --short --untracked-files=no >&2
  exit 1
fi

if [ -n "$BUNDLE_PATH" ]; then
  if [ ! -f "$BUNDLE_PATH" ]; then
    echo "ERROR: File bundle tidak ditemukan: $BUNDLE_PATH" >&2
    exit 1
  fi

  echo "Menerapkan update dari bundle..."
  rm -rf public/build
  git fetch "$BUNDLE_PATH" "refs/heads/__BRANCH__:refs/remotes/deploy/__BRANCH__" --force
  git checkout -B "__BRANCH__" "refs/remotes/deploy/__BRANCH__"
  rm -f "$BUNDLE_PATH"
else
  echo "Tidak ada commit baru untuk diterapkan."
fi

echo "Menjalankan migrasi database..."
php artisan migrate --force

echo "Membersihkan cache..."
php artisan optimize:clear

echo "Membangun cache produksi..."
php artisan optimize

echo "Deploy server selesai."
'@

    $remoteScript = $remoteScript.Replace('__REMOTE_PATH__', $RemotePath).Replace('__BUNDLE_PATH__', $bundleRemotePath).Replace('__BRANCH__', $Branch)

    Write-Section "Deploy ke server $RemoteUser@$RemoteHost"
    Invoke-RemoteScript $remoteScript

    Write-Host "`nDeploy selesai." -ForegroundColor Green
} finally {
    if ($localBundle -and (Test-Path $localBundle)) {
        Remove-Item $localBundle -Force
    }

    Pop-Location
}
