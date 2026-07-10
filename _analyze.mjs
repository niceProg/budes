import fs from 'fs';
const h = fs.readFileSync('_design_rendered.html', 'utf8');
console.log('total bytes:', h.length);
console.log('<script> blocks:', (h.match(/<script/gi) || []).length);
console.log('<style> blocks:', (h.match(/<style/gi) || []).length);
console.log('<link> tags:', (h.match(/<link/gi) || []).length);
console.log('data: URIs:', (h.match(/data:[^"\')]+/gi) || []).length);
console.log('base64 font bytes (approx):', (h.match(/data:font[^"\')]*/gi) || []).join('').length + (h.match(/data:application\/font[^"\')]*/gi)||[]).join('').length);
console.log('@font-face blocks:', (h.match(/@font-face/gi) || []).length);
console.log('inline style= count:', (h.match(/ style="/gi) || []).length);
console.log('__bundler refs:', (h.match(/__bundler/gi) || []).length);
console.log('data-dc / data-props / data-screen:', (h.match(/data-(dc|props|screen)/gi) || []).length);
console.log('remixicon/ri- icons:', (h.match(/\bri-[a-z]/gi) || []).length);
console.log('svg icons:', (h.match(/<svg/gi) || []).length);
console.log('emoji-ish:', (h.match(/[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}]/gu) || []).length);

// head slice
const headEnd = h.indexOf('</head>');
console.log('\n--- HEAD (tanpa isi <style> base64) ---');
let head = h.slice(0, headEnd + 7);
head = head.replace(/data:[^"')]{80,}/g, 'data:...(trimmed)...');
head = head.replace(/@font-face\s*\{[^}]*\}/g, '@font-face{...}');
console.log(head.slice(0, 2500));

// body start
const bodyStart = h.indexOf('<body');
console.log('\n--- BODY awal (400 char) ---');
console.log(h.slice(bodyStart, bodyStart + 400));
