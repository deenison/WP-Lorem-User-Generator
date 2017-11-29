const path = require('path');
const BASE_PATH = path.dirname(path.dirname(__dirname));
const package = require(`${BASE_PATH}/package.json`);
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const cssnano = require('gulp-cssnano');
const rename = require('gulp-rename');

module.exports = function($, callback) {
  return $.src(`${BASE_PATH}/src/assets/css/*.scss`)
          .pipe(sass())
          .pipe(sourcemaps.init())
          .pipe(autoprefixer({
            browsers: ['last 4 versions'],
            cascade: false
          }))
          .pipe(cssnano())
          .pipe(rename(path => { path.extname = '.min.css' }))
          .pipe(sourcemaps.write('.'))
          .pipe($.dest(`${BASE_PATH}/build/${package.name}/assets/css`));
};
