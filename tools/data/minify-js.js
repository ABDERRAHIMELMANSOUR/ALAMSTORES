const path = require('path');
const terser = require(path.join(process.env.NODE_TOOLS, 'node_modules/terser'));
const fs = require('fs');
(async () => {
  const code = fs.readFileSync(process.argv[2], 'utf8');
  const out = await terser.minify(code, {
    compress: { passes: 2, drop_console: true },
    mangle: true,
    format: { comments: /^!/ },
  });
  if (out.error) { console.error(out.error); process.exit(1); }
  fs.writeFileSync(process.argv[3], out.code);
  console.log('js: ' + code.length + ' -> ' + out.code.length + ' bytes');
})();
