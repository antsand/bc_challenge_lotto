var path = require('path')
var webpack = require('webpack')

module.exports = {
  entry: {
        lotto: './app/vue/lotto.js',
  },
  output: {
    path: path.resolve(__dirname, './public/builds/production/js/dist'),
    publicPath: '/dist/',
    filename: '[name].bundle.js',
    chunkFilename: '[id].chunk.js'
  },
  module: { 	
  	rules: [
	{
	test: /\.css$/,
        use: [
          'vue-style-loader',
          'css-loader'
        ],
      },
      {
          test: /\.s[a|c]ss$/,
                   use: [
          'style-loader',
//          'css-loader',
          {
            loader: 'sass-loader',
            options: {
                          sourceMap: true,
              sassOptions: {
                outputStyle: 'compressed',
              },
              //additionalData: '$env: ' + process.env.NODE_ENV + ';',
            },
          },
        ],
      },
	{
        	test: /\.vue$/,
        	loader: 'vue-loader',
        	options: {
		  loaders: {
		  }
		  // other vue-loader options go here
		}
        },
        {
        	test: /\.js$/,
        	loader: 'babel-loader',
        	exclude: /node_modules/
        }
      ],
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    },
    extensions: ['*', '.js', '.vue', '.json']
  }
}

if (process.env.NODE_ENV === 'production') {
  module.exports.devtool = '#source-map'
  // http://vue-loader.vuejs.org/en/workflow/production.html
  module.exports.plugins = (module.exports.plugins || []).concat([
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true,
      compress: {
        warnings: false
      }
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: true
    }),
  ])
}

