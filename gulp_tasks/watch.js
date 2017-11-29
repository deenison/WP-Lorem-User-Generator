const runSequence = require('run-sequence');
const watch = require('gulp-watch');
const path = require('path');

module.exports = function($, callback) {
  return watch(`${path.dirname(__dirname)}/src/**/*`, { ignoreInitial: false }, function() {
    runSequence('clean:build', ['txt:copy', 'php:copy', 'scss:compile', 'js:copy', 'pot:compile']);
  });
};
