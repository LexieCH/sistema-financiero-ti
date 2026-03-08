<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión expirada</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --border: #334155;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --primary: #3b82f6;
            --primary-h: #2563eb;
            --secondary: #1f2937;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at top, #1e293b 0%, var(--bg) 55%);
            font-family: Inter, Arial, sans-serif;
            color: var(--text);
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(17, 24, 39, 0.95);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
            padding: 28px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        p {
            color: var(--muted);
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text);
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover { background: var(--primary-h); }

        .btn-secondary {
            background: var(--secondary);
            border-color: var(--border);
        }

        .btn-secondary:hover {
            border-color: #64748b;
            background: #111827;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Sesión expirada (419)</h1>
        <p>
            Tu sesión o token CSRF ya no es válido. Esto puede pasar si dejas la página abierta mucho tiempo o si cambias de dominio.
        </p>

        <div class="actions">
            <button class="btn btn-secondary" type="button" onclick="window.history.back()">Volver atrás</button>
            <a class="btn btn-primary" href="{{ route('login') }}">Ir al login</a>
        </div>
    </div>
</body>
</html>
