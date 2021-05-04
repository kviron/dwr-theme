const path = require('path');
module.exports = (folder, prefix = '') => {
  const theme = path.basename(path.resolve('../'));
  return `${ prefix }/app/themes/${ process.env.THEME_NAME }/assets/`;
}
