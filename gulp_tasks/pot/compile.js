const path = require('path');
const BASE_PATH = path.dirname(path.dirname(__dirname));
const package = require(`${BASE_PATH}/package.json`);

const wpPot = require('gulp-wp-pot');

module.exports = function($, callback) {
  return $.src(`${BASE_PATH}/src/**/*.php`)
    .pipe(wpPot({
      domain   : package.name,
      package  : `${package.title} v${package.v}`,
      headers  : false,
      bugReport: package.bugs.url
    }))
    .pipe($.dest(`${BASE_PATH}/build/${package.name}/languages/${package.name}.pot`));
};
