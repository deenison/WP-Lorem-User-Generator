const path = require('path');
const BASE_PATH = path.dirname(path.dirname(__dirname));
const package = require(`${BASE_PATH}/package.json`);
const uglify = require('gulp-uglify');
const util = require('gulp-util');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');

module.exports = ($, callback) => {
  return $.src(`${BASE_PATH}/src/assets/js/**/*.js`)
          .pipe(uglify({
            mangle  : true,
            compress: true
          }).on('error', util.log))
          .pipe(rename(path => { path.extname = '.min.js' }))
          .pipe(sourcemaps.write('.'))
          .pipe($.dest(`${BASE_PATH}/build/${package.name}/assets/js`));
};
