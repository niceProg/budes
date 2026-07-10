import fs from 'fs';

let h = fs.readFileSync('_design_rendered.html', 'utf8');
h = h.replace(/^﻿/, ''); // buang BOM

// --- Ambil isi <body> ---
let body = h.slice(h.indexOf('<body'), h.indexOf('</body>'));
body = body.replace(/^<body[^>]*>/, '');

// --- Ambil <style> dasar desain (body bg / a:hover / keyframes) dari <head> ---
const headPart = h.slice(0, h.indexOf('</head>'));
const styleBlocks = [...headPart.matchAll(/<style[^>]*>([\s\S]*?)<\/style>/gi)].map(m => m[1]);
const baseDesignCss = styleBlocks.find(s => /background:\s*#FAF6F1/i.test(s)) || '';

// --- Buang semua <script> ---
body = body.replace(/<script[\s\S]*?<\/script>/gi, '');

// --- Buang <style> runtime yg mungkin ikut di body ---
body = body.replace(/<style[\s\S]*?<\/style>/gi, '');

// --- Buang atribut editor / runtime dc ---
body = body
  .replace(/\s+data-dc-tpl="[^"]*"/gi, '')
  .replace(/\s+data-dc-script="[^"]*"/gi, '')
  .replace(/\s+data-dc[a-z-]*="[^"]*"/gi, '')
  .replace(/\s+data-sc-name="[^"]*"/gi, '')
  .replace(/\s+data-sc[a-z-]*="[^"]*"/gi, '')
  .replace(/\s+data-screen-label="[^"]*"/gi, '')
  .replace(/\s+data-props="[^"]*"/gi, '')
  .replace(/\s+hint-placeholder-val="[^"]*"/gi, '')
  .replace(/\s+contenteditable="[^"]*"/gi, '');

// --- Unwrap kontainer runtime: #dc-root + .sc-host (buka pembungkus, sisakan app) ---
body = body
  .replace(/<div\s+id="dc-root"[^>]*>/i, '')
  .replace(/<div\s+class="sc-host"[^>]*>/i, '');
// hapus 2 penutup </div> paling akhir milik wrapper tsb
body = body.replace(/(\s*<\/div>)(\s*)$/i, '$2');   // sc-host close
body = body.replace(/(\s*<\/div>)(\s*)$/i, '$2');   // dc-root close

// --- Bersihkan class runtime sisa ---
body = body.replace(/\s+class="(sc-host|sc-placeholder|sc-interp[^"]*)"/gi, '');

// --- Rakit HTML final bersih, bertema views (Plus Jakarta Sans + palet Budes) ---
const out = `<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bursa Desa — Koperasi Desa Merah Putih</title>

  <!-- Font: Plus Jakarta Sans (selaras dengan tema views) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* === Token tema Budes (selaras views) — untuk kemudahan edit === */
    :root {
      --primary-dark: #2B1A17;   /* coklat gelap (heading) */
      --maroon:       #A93438;   /* brand utama */
      --maroon-dark:  #8E2A2F;   /* hover */
      --cream:        #FAF6F1;   /* latar halaman */
      --cream-card:   #FFFDFB;   /* kartu */
      --border:       #EBDDD3;
      --text-main:    #2B1A17;
      --text-body:    #5C4A44;
      --text-muted:   #9C8378;
      --green:        #1D7A46;
    }

    /* === Base desain asli === */
    ${baseDesignCss.trim()}

    /* Font global (inline style memakai 'Plus Jakarta Sans' — pastikan fallback rapi) */
    body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
  </style>
</head>
<body>
${body.trim()}
</body>
</html>
`;

fs.writeFileSync('bursa-desa.html', out, 'utf8');
console.log('bursa-desa.html ditulis:', out.length, 'bytes');
console.log('sisa <script>:', (out.match(/<script/gi) || []).length);
console.log('sisa data-dc:', (out.match(/data-dc/gi) || []).length);
console.log('sisa @font-face base64:', (out.match(/@font-face/gi) || []).length);
console.log('sisa {{:', (out.match(/\{\{/g) || []).length);
console.log('base design css found:', baseDesignCss ? 'ya' : 'TIDAK');
