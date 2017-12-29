const runSequence = require('run-sequence');

module.exports = function($, callback) {
  return runSequence('clean:build', ['txt:copy', 'php:copy', 'scss:compile', 'scss:minify', 'js:copy', 'js:minify', 'pot:compile', 'pot:copy']);
};
