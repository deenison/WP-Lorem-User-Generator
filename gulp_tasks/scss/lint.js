const gulp = require('gulp');
const sassLint = require('gulp-sass-lint');
const path = require('path');
const BASE_PATH = path.dirname(path.dirname(__dirname));

module.exports = function($, callback) {
  return $.src(`${BASE_PATH}/src/assets/css/**/*.s+(a|c)ss`)
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError())
};
