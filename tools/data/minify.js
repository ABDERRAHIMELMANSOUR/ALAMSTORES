const CleanCSS = require(require('path').join(process.env.NODE_TOOLS, 'node_modules/clean-css'));
const fs = require('fs');
const out = new CleanCSS({ level: 2, rebase: false, inline: ['none'] })
  .minify(fs.readFileSync(process.argv[2], 'utf8'));
if (out.errors.length) { console.error(out.errors.slice(0, 5)); process.exit(1); }
fs.writeFileSync(process.argv[3], out.styles);
console.log('minified: ' + out.stats.originalSize + ' -> ' + out.stats.minifiedSize + ' bytes');
