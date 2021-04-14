const dotEnv = require('dotenv').config()
module.exports = {
  context: 'src',
  entry: {
    app: '/'
  },
  devtool: 'cheap-module-eval-source-map',
  outputFolder: `${process.env.DIST_PATH}`,
  publicFolder: `${process.env.SOURCE_PATH}`,
  proxyTarget: `${process.env.DOMAIN}`,
  watch: [
    '../**/*.php'
  ]
}
