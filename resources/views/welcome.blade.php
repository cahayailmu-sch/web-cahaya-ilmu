<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cahaya Ilmu Islamic School</title>
    <link rel="icon" type="image/png" href="/assets/images/logo-utama.png">
    <style>
        :root {
            color-scheme: light;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 2rem;
            background:
                radial-gradient(ellipse at top right, rgba(225, 29, 72, 0.08), transparent 45%),
                radial-gradient(ellipse at bottom left, rgba(245, 158, 11, 0.1), transparent 45%),
                #f8fafc;
            color: #0f172a;
        }

        main {
            width: 100%;
            max-width: 28rem;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            background: #fff;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 24px 60px -32px rgba(15, 23, 42, 0.25);
        }

        img {
            width: 11rem;
            height: auto;
            margin: 0 auto;
        }

        h1 {
            margin: 1.75rem 0 0;
            font-size: 1.5rem;
            line-height: 1.25;
        }

        p {
            margin: 0.75rem 0 0;
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.7;
        }

        a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 1.75rem;
            border-radius: 0.75rem;
            background: #e11d48;
            padding: 0.75rem 1.25rem;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        a:hover {
            background: #be123c;
        }
    </style>
</head>
<body>
    <main>
        <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School">
        <h1>Cahaya Ilmu Islamic School</h1>
        <p>Halaman ini adalah tampilan cadangan. Silakan kembali ke beranda untuk melihat informasi sekolah.</p>
        <a href="{{ route('home') }}">Kembali ke Beranda</a>
    </main>
</body>
</html>
