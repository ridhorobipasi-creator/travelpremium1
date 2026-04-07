import fs from 'fs';
import path from 'path';
import https from 'https';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const SRC_DIR = path.join(__dirname, 'src');
const PUBLIC_ASSETS_DIR = path.join(__dirname, 'public', 'assets', 'images');

if (!fs.existsSync(PUBLIC_ASSETS_DIR)) {
  fs.mkdirSync(PUBLIC_ASSETS_DIR, { recursive: true });
}

const downloadFile = (url, dest) => {
  return new Promise((resolve, reject) => {
    if (fs.existsSync(dest)) {
      return resolve(true); // Already downloaded
    }
    const file = fs.createWriteStream(dest);
    https.get(url, (response) => {
      response.pipe(file);
      file.on('finish', () => {
        file.close(resolve(true));
      });
    }).on('error', (err) => {
      fs.unlink(dest, () => {});
      console.error(`Error downloading ${url}:`, err.message);
      resolve(false); 
    });
  });
};

function getAllFiles(dirPath, arrayOfFiles) {
  const files = fs.readdirSync(dirPath);

  arrayOfFiles = arrayOfFiles || [];

  files.forEach(function(file) {
    if (fs.statSync(dirPath + "/" + file).isDirectory()) {
      arrayOfFiles = getAllFiles(dirPath + "/" + file, arrayOfFiles);
    } else {
      if (file.endsWith('.tsx') || file.endsWith('.ts')) {
        arrayOfFiles.push(path.join(dirPath, "/", file));
      }
    }
  });

  return arrayOfFiles;
}

async function processFiles() {
  const files = getAllFiles(SRC_DIR);
  const regex = /https:\/\/wonderfultoba\.com\/wp-content\/uploads\/([a-zA-Z0-9_\-\.\/]+)/g;
  
  let downloadCount = 0;

  for (const file of files) {
    let content = fs.readFileSync(file, 'utf8');
    let match;
    let modified = false;
    let newContent = content;

    const matches = [...content.matchAll(regex)];
    
    for (const m of matches) {
      const fullUrl = m[0];
      const relativePath = m[1];
      const fileName = path.basename(relativePath);
      const destFolder = path.join(PUBLIC_ASSETS_DIR, path.dirname(relativePath));
      
      if (!fs.existsSync(destFolder)) {
        fs.mkdirSync(destFolder, { recursive: true });
      }

      const destPath = path.join(destFolder, fileName);
      console.log(`Downloading: ${fileName}`);
      await downloadFile(fullUrl, destPath);
      downloadCount++;

      // Replace in content
      const localUrl = `/assets/images/${relativePath}`;
      newContent = newContent.split(fullUrl).join(localUrl);
      modified = true;
    }

    if (modified) {
      fs.writeFileSync(file, newContent, 'utf8');
    }
  }
  
  console.log(`Done! Downloaded/Checked ${downloadCount} images and updated references.`);
}

processFiles().catch(console.error);
