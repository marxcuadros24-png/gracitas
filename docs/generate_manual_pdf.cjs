const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const root = process.cwd();
const docsDir = path.join(root, 'docs');
const htmlFilePath = path.join(docsDir, 'MANUAL_DE_USUARIO.html');

console.log('Reading logos...');
const logoApstiPath = path.join(root, 'public', 'images', 'logo apsti.png');
const logoIestpPath = path.join(root, 'public', 'images', 'logo iestp.png');

const b64Apsti = fs.readFileSync(logoApstiPath).toString('base64');
const b64Iestp = fs.readFileSync(logoIestpPath).toString('base64');

console.log('Reading MANUAL_DE_USUARIO.html...');
let html = fs.readFileSync(htmlFilePath, 'utf8');

// 1. Agregar estilos de cabecera institucional
const extraStyles = `
        /* Cabecera Institucional IESTP / APSTI */
        .cover-institutional-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 90%;
            margin-top: 14px;
            margin-bottom: 12px;
            padding: 10px 18px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(8, 127, 104, 0.06);
            z-index: 2;
        }
        .inst-logo-wrap {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .inst-logo {
            height: 58px;
            max-width: 110px;
            object-fit: contain;
            display: block;
        }
        .inst-text {
            flex: 1 1 auto;
            text-align: center;
            padding: 0 14px;
        }
        .inst-title {
            font-family: 'Outfit', sans-serif;
            font-size: 9pt;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: 0.5px;
            line-height: 1.25;
            text-transform: uppercase;
        }
        .inst-career {
            font-size: 8.5pt;
            font-weight: 700;
            color: var(--primary);
            margin-top: 2px;
            line-height: 1.25;
        }
        .inst-course {
            font-size: 7.8pt;
            font-weight: 600;
            color: var(--muted);
            margin-top: 2px;
        }
`;

// Insertar estilos antes de </style>
if (!html.includes('.cover-institutional-header')) {
    html = html.replace('</style>', extraStyles + '\n    </style>');
}

// Ajustar márgenes para que la carátula no se desborde en A4
html = html.replace(/\.cover-logo-box\s*\{[\s\S]*?margin-top:\s*\d+px;/, '.cover-logo-box {\n            margin-top: 12px;');
html = html.replace(/\.cover-center\s*\{[\s\S]*?margin:\s*\d+px 0;/, '.cover-center {\n            padding: 0 25px;\n            margin: 18px 0;');
html = html.replace(/font-size:\s*34pt;/, 'font-size: 28pt;');

// 2. Construir la nueva carátula
const newCover = `    <!-- CARÁTULA -->
    <section class="cover-page">
        <div class="cover-top-bar"></div>

        <!-- Cabecera Institucional con Logos IESTP y APSTI -->
        <div class="cover-institutional-header">
            <div class="inst-logo-wrap">
                <img src="data:image/png;base64,${b64Iestp}" alt="Logo IESTP" class="inst-logo">
            </div>
            <div class="inst-text">
                <div class="inst-title">Instituto de Educación Superior Tecnológico Público</div>
                <div class="inst-career">PROGRAMA DE ESTUDIOS: ARQUITECTURA DE PLATAFORMAS Y SERVICIOS DE TI (APSTI)</div>
                <div class="inst-course">Módulo Formativo: Arquitectura y Programación Web — VI Ciclo</div>
            </div>
            <div class="inst-logo-wrap">
                <img src="data:image/png;base64,${b64Apsti}" alt="Logo APSTI" class="inst-logo">
            </div>
        </div>

        <div class="cover-logo-box">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 520" role="img" aria-labelledby="title desc">
  <title id="title">GraCitas</title>
  <desc id="desc">Logo minimalista de reservas de canchas con calendario, campo de fútbol y confirmación.</desc>
  <rect width="640" height="520" fill="#ffffff"/>
  <g transform="translate(0,8)" stroke="#087f68" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" fill="none">
    <!-- Calendario -->
    <rect x="170" y="55" width="300" height="250" rx="34"/>
    <path d="M170 112 H470"/>
    <path d="M230 40 V78 M410 40 V78"/>
    <!-- Campo de fútbol -->
    <rect x="205" y="135" width="230" height="140" rx="5" stroke-width="7"/>
    <path d="M320 135 V275" stroke-width="6"/>
    <circle cx="320" cy="205" r="25" stroke-width="6"/>
    <rect x="205" y="173" width="34" height="64" stroke-width="6"/>
    <rect x="401" y="173" width="34" height="64" stroke-width="6"/>
  </g>
  <!-- Sello de reserva confirmada -->
  <circle cx="443" cy="278" r="48" fill="#087f68" stroke="#ffffff" stroke-width="7"/>
  <path d="M420 278 L436 294 L468 259" fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
  <!-- Nombre -->
  <text x="320" y="410" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="66" font-weight="700" letter-spacing="-2">
    <tspan fill="#252a2d">Gra</tspan><tspan fill="#087f68">Citas</tspan>
  </text>
  <text x="320" y="450" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="17" font-weight="500" letter-spacing="3" fill="#687477">RESERVA TU CANCHA</text>
</svg>
        </div>
        <div class="cover-center">
            <span class="cover-badge">Documentación Oficial del Sistema — Edición Académica & Comercial</span>
            <h1 class="cover-title">MANUAL DE USUARIO</h1>
            <p class="cover-subtitle">Sistema Web de Gestión y Reserva de Canchas de Gras Sintético</p>
            <p class="cover-desc">
                Guía oficial y completa de operación para deportistas (clientes), propietarios de complejos deportivos y administradores de la plataforma <strong>GraCitas</strong>.
            </p>
        </div>
        <div class="cover-meta-card">
            <div class="cover-meta-row">
                <span class="cover-meta-label">Institución</span>
                <span class="cover-meta-value">Instituto de Educación Superior Tecnológico Público (IESTP)</span>
            </div>
            <div class="cover-meta-row">
                <span class="cover-meta-label">Programa de Estudios</span>
                <span class="cover-meta-value">Arquitectura de Plataformas y Servicios de TI (APSTI)</span>
            </div>
            <div class="cover-meta-row">
                <span class="cover-meta-label">Módulo / Semestre</span>
                <span class="cover-meta-value">Arquitectura y Programación Web — VI Ciclo</span>
            </div>
            <div class="cover-meta-row">
                <span class="cover-meta-label">Plataforma</span>
                <span class="cover-meta-value">GraCitas — Reservas Deportivas en Tiempo Real</span>
            </div>
            <div class="cover-meta-row">
                <span class="cover-meta-label">Versión de Sistema</span>
                <span class="cover-meta-value">Versión Oficial 2.4.4</span>
            </div>
            <div class="cover-meta-row">
                <span class="cover-meta-label">Ámbito Geográfico & Fecha</span>
                <span class="cover-meta-value">Coracora, Parinacochas, Ayacucho — Noviembre 2026</span>
            </div>
        </div>
        <div class="cover-bottom-bar"></div>
    </section>`;

// Reemplazar la sección .cover-page completa
html = html.replace(/<!-- CARÁTULA -->[\s\S]*?<\/section>/, newCover);

fs.writeFileSync(htmlFilePath, html, 'utf8');
console.log('MANUAL_DE_USUARIO.html updated successfully!');

// 3. Generar PDFs con Chrome Headless
const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const pdfFinalPath = path.join(docsDir, 'MANUAL_DE_USUARIO_FINAL.pdf');
const pdfGracitasPath = path.join(docsDir, 'MANUAL_DE_USUARIO_GRACITAS.pdf');

if (fs.existsSync(chromePath)) {
    console.log('Compiling HTML to PDF using Chrome Headless...');
    const fileUrl = 'file:///' + htmlFilePath.replace(/\\/g, '/');
    const tempProfile = path.join(process.env.TEMP || 'C:\\Windows\\Temp', 'chrome_manual_pdf_' + Date.now());

    try {
        const cmd = `"${chromePath}" --headless=new --disable-gpu --no-sandbox --user-data-dir="${tempProfile}" --no-pdf-header-footer --print-to-pdf="${pdfFinalPath}" "${fileUrl}"`;
        execSync(cmd, { timeout: 60000 });

        if (fs.existsSync(pdfFinalPath)) {
            const stats = fs.statSync(pdfFinalPath);
            console.log('PDF generated at MANUAL_DE_USUARIO_FINAL.pdf (' + stats.size + ' bytes)');
            fs.copyFileSync(pdfFinalPath, pdfGracitasPath);
            console.log('Copied to MANUAL_DE_USUARIO_GRACITAS.pdf');
        } else {
            console.error('PDF file was not created.');
        }
    } catch (err) {
        console.error('Error during Chrome PDF compilation:', err.message);
    } finally {
        try {
            fs.rmSync(tempProfile, { recursive: true, force: true });
        } catch (e) {}
    }
} else {
    console.warn('Chrome executable not found.');
}
