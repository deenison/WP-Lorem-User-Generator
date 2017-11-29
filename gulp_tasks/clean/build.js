const del = require('del');
const path = require('path');

const BASE_PATH = path.dirname(path.dirname(__dirname));

module.exports = function($, callback) {
  return del([
    `${BASE_PATH}/build/**/*`
  ]);
};
