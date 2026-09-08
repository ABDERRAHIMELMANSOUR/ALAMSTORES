const { PurgeCSS } = require(require('path').join(process.env.NODE_TOOLS, 'node_modules/purgecss'));
const fs = require('fs');
(async () => {
  const res = await new PurgeCSS().purge({
    content: ['*.html', 'assets/js/main.js'],
    css: [process.argv[2]],
    safelist: {
      standard: [
        /^fa$/, /^fas$/, /^far$/, /^fab$/, /^fa-/,
        /flaticon/, /^icon_/, /^arrow_/, /^social_/,
        /^zmdi/,
        /^is-/, /^has-/, /^active_sub$/, /^show$/, /^collapsing$/,
        /^lightbox/, /^slider/, /^slide$/, /^form-status$/,
      ],
      deep: [/lightbox/, /slider/, /dropdown/, /navbar/, /breadcrumb/, /collapse/],
      greedy: [/dropdown/, /navbar/, /collapse/],
    },
    fontFace: true, keyframes: true, variables: true,
  });
  fs.writeFileSync(process.argv[3], res[0].css);
  console.log('purged: ' + res[0].css.length + ' bytes');
})();
