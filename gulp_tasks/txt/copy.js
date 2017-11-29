const path = require('path');
const BASE_PATH = path.dirname(path.dirname(__dirname));
const package = require(`${BASE_PATH}/package.json`);

module.exports = function($, callback) {
  return $.src([`${BASE_PATH}/src/**/*.txt`])
    .pipe($.dest(`${BASE_PATH}/build/${package.name}`));
};
