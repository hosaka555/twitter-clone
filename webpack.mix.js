const mix = require('laravel-mix');
const s3Plugin = require('webpack-s3-plugin');

let webpackPlugins = [];
if (mix.inProduction() && process.env.UPLOAD_S3) {
  webpackPlugins = [
    new s3Plugin({
      include: /.*\.(css|js|png|jpeg|jpg)$/,
      s3Options: {
        accessKeyId: process.env.AWS_ACCESS_KEY_ID,
        secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
        region: process.env.AWS_DEFAULT_REGION,
      },
      s3UploadOptions: {
        Bucket: process.env.AWS_BUCKET,
        CacheControl: 'public, max-age=31536000'
      },
      basePath: 'app',
      directory: 'public',
      cloudfrontInvalidateOptions: {
        DistributionId: process.env.CLOUDFRONT_DISTRIBUTION_ID,
        Items: ['/*'],
      },
    })
  ]
}


mix.webpackConfig({
  plugins: webpackPlugins,
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': __dirname + '/resources/js'
    }
  },
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
  .copy('resources/img', 'public/img')
  .sass('resources/sass/app.scss', 'public/css').version();
