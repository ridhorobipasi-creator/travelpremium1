// Script to generate PWA icons using SVG -> PNG via sharp
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const iconsDir = path.join(__dirname, 'public', 'icons');
if (!fs.existsSync(iconsDir)) {
  fs.mkdirSync(iconsDir, { recursive: true });
}

// Create a beautiful SVG icon for the app  
const sizes = [72, 96, 128, 144, 152, 192, 384, 512];

const createSvgIcon = (size) => `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 ${size} ${size}">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#0f172a;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#1e293b;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="accent" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#10B981;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
    </linearGradient>
  </defs>
  <!-- Background rounded rect -->
  <rect width="${size}" height="${size}" rx="${size * 0.22}" fill="url(#bg)"/>
  <!-- Green accent bar at top -->
  <rect x="${size*0.08}" y="${size*0.08}" width="${size*0.84}" height="${size*0.06}" rx="${size*0.03}" fill="url(#accent)"/>
  <!-- W Letter -->
  <text 
    x="${size / 2}" 
    y="${size * 0.62}" 
    font-family="Arial Black, Arial, sans-serif" 
    font-weight="900" 
    font-size="${size * 0.48}" 
    fill="white" 
    text-anchor="middle" 
    dominant-baseline="middle"
  >W</text>
  <!-- Wonderful text -->
  <text 
    x="${size / 2}" 
    y="${size * 0.84}" 
    font-family="Arial, sans-serif" 
    font-weight="700" 
    font-size="${size * 0.085}" 
    fill="#10B981"
    text-anchor="middle"
    letter-spacing="${size * 0.008}"
  >WONDERFUL TOBA</text>
</svg>`;

// Write SVG files as fallback
for (const size of sizes) {
  const svgContent = createSvgIcon(size);
  const svgPath = path.join(iconsDir, `icon-${size}x${size}.svg`);
  fs.writeFileSync(svgPath, svgContent, 'utf8');
  console.log(`Created SVG icon: icon-${size}x${size}.svg`);
}

console.log('SVG icons generated! Now converting to PNG using sharp...');

// Try to convert with sharp
try {
  const sharp = await import('sharp');
  
  for (const size of sizes) {
    const svgPath = path.join(iconsDir, `icon-${size}x${size}.svg`);
    const pngPath = path.join(iconsDir, `icon-${size}x${size}.png`);
    const svgContent = fs.readFileSync(svgPath);
    
    await sharp.default(svgContent)
      .resize(size, size)
      .png()
      .toFile(pngPath);
    
    console.log(`Converted: icon-${size}x${size}.png`);
  }
  
  console.log('All PNG icons generated successfully!');
} catch(e) {
  console.log('Sharp not available, installing...');
  console.log('Run: npm install -D sharp');
  console.log('Then re-run this script');
}
