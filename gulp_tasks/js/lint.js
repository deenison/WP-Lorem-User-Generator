const path = require('path');
const BASE_PATH = path.dirname(path.dirname(__dirname));

const jslint = require('gulp-jslint');

module.exports = function($, callback) {
  return $.src(`${BASE_PATH}/src/assets/js/**/*.js`)
    .pipe(jslint({}))
    .pipe(jslint.reporter('stylish'));
};
