const { PurgeCSS } = require(require('path').join(process.env.NODE_TOOLS, 'node_modules/purgecss'));
const fs = require('fs');
(async () => {
  const res = await new PurgeCSS().purge({
    content: ['*.html', 'assets/js/main.js'],
    css: [process.argv[2]],
    // Classes toggled by JavaScript at runtime never appear in the HTML.
    safelist: {
      standard: [
        /^is-/, /^has-/, 'reveal',
        /^lightbox/, /^drawer/, /^hero__/, /^partners/, /^steps/,
        /^field/, /^form-/, /^summary/, /^to-top$/, /^fab/,
        /^nav__/, /^card__/, /^gallery/, /^badge/, /^btn/,
      ],
      deep: [/lightbox/, /drawer/, /hero/, /partners/, /steps/],
      greedy: [/^\.lightbox/, /^\.drawer/],
    },
    fontFace: true, keyframes: true, variables: true,
  });
  fs.writeFileSync(process.argv[3], res[0].css);
  console.log('purged: ' + res[0].css.length + ' bytes');
})();
