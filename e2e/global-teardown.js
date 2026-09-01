require('dotenv').config();
const { togglePlugin } = require('./scripts/pro-plugin-control');

module.exports = async () => {
  togglePlugin('activate');
};
