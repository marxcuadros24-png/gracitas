<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swagger API — GraCitas OpenAPI 3.0</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui.css">
    <style>
        body {
            margin: 0;
            background: #f8fafc;
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }
        .gracitas-swagger-header {
            background: linear-gradient(90deg, #064e3b 0%, #087f68 100%);
            color: #ffffff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(6, 78, 59, 0.2);
        }
        .gracitas-swagger-header a {
            color: #ecfdf5;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 6px 12px;
            border-radius: 6px;
        }
        #swagger-ui {
            max-width: 1200px;
            margin: 0 auto;
            padding: 12px 16px 40px 16px;
        }
    </style>
</head>
<body>
    <header class="gracitas-swagger-header">
        <div>
            <strong style="font-size: 1.1rem;">GraCitas — Documentación API (Swagger / OpenAPI 3.0)</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ $specUrl }}" target="_blank" rel="noopener">OpenAPI JSON</a>
            <a href="{{ route('home') }}">Volver a GraCitas</a>
        </div>
    </header>

    <div id="swagger-ui"></div>

    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        window.onload = function () {
            window.ui = SwaggerUIBundle({
                url: @json($specUrl),
                dom_id: '#swagger-ui',
                deepLinking: true,
                docExpansion: 'list',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIBundle.SwaggerUIStandalonePreset
                ],
            });
        };
    </script>
</body>
</html>

