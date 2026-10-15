<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $bloqueado ? 'Acceso restringido' : 'Sistema liberado' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 1.5rem; background: #111827; color: #d1d5db;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .card {
            width: 100%; max-width: 44rem; padding: 3rem 2.5rem; text-align: center;
            background: #1f2937; border: 1px solid #374151; border-radius: .75rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.6);
        }
        .icon {
            width: 5rem; height: 5rem; margin: 0 auto 1.75rem; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center; font-size: 2.5rem;
            background: {{ $bloqueado ? 'rgba(239,68,68,.15)' : 'rgba(34,197,94,.15)' }};
            border: 2px solid {{ $bloqueado ? '#ef4444' : '#22c55e' }};
        }
        h1 { font-size: 2.25rem; font-weight: 800; color: #fff; margin-bottom: 1.25rem; letter-spacing: -.02em; }
        .line { width: 4rem; height: 4px; margin: 0 auto 1.75rem; border-radius: 9999px; background: #6366f1; }
        p { font-size: 1.5rem; line-height: 1.6; color: #e5e7eb; }
        p strong { color: #818cf8; font-weight: 700; }
        @media (max-width: 480px) { h1 { font-size: 1.75rem; } p { font-size: 1.2rem; } .card { padding: 2rem 1.25rem; } }
    </style>
</head>
<body>
    <main class="card">
        @if ($bloqueado)
            <div class="icon">🔒</div>
            <h1>Acceso restringido</h1>
            <div class="line"></div>
            <p>Este sistema es <strong>propiedad intelectual particular</strong>.<br>
            Por favor, póngase en contacto con el <strong>ingeniero en software responsable del proyecto</strong>.</p>
        @else
            <div class="icon">✅</div>
            <h1>Sistema liberado</h1>
            <div class="line"></div>
            <p>El sistema ha sido <strong>liberado</strong>.</p>
        @endif
    </main>
</body>
</html>
