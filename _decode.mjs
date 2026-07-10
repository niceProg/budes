import fs from 'fs';

const src = fs.readFileSync('Bursa-desa-design.html', 'utf8');
const lines = src.split('\n');
// baris 202 (index 201) = string JSON template
let raw = lines[201];
if (raw.endsWith('\r')) raw = raw.slice(0, -1);

let html;
try {
  html = JSON.parse(raw);
} catch (e) {
  console.error('JSON.parse gagal:', e.message);
  process.exit(1);
}

fs.writeFileSync('_design_decoded.html', html, 'utf8');

console.log('decoded length:', html.length);
console.log('--- struktur cepat ---');
const marker = (re, label) => console.log(label + ':', (html.match(re) || []).length);
marker(/<x-dc/g, '<x-dc>');
marker(/<helmet/g, '<helmet>');
marker(/style-hover=/g, 'style-hover attr');
marker(/<script/g, '<script>');
marker(/<style/g, '<style>');
marker(/class="/g, 'class="');
marker(/react/gi, 'react-ref');
marker(/createElement|useState|this\.setState|render\(/g, 'react-code');
marker(/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}/g, 'uuid-asset-ref');

// Cari root render container
const rootIds = [...html.matchAll(/id="([^"]*root[^"]*|app|__[a-z]+)"/gi)].map(m => m[1]);
console.log('possible roots:', [...new Set(rootIds)].slice(0, 10));

// Jumlah teks Indonesia khas Budes (indikasi markup statis / SSR)
['Buat Permintaan','Etalase','Sanggupi','Uang Muka','Koperasi','Titip','Pesan','Jelajah Pasar','Demand','Supply'].forEach(t => {
  const c = html.split(t).length - 1;
  if (c) console.log('  teks "' + t + '":', c);
});
