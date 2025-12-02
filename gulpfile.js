const gulp = require('gulp');
const sass = require('sass');
const gulpSass = require('gulp-sass')(sass);
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const terser = require('gulp-terser');

const paths = {
  blocks: {
    scss: 'src/Blocks/scss/app.scss', // Main SCSS file that imports all blocks
    js: ['src/Blocks/js/**/*.js', '!src/Blocks/js/**/*.min.js'],
    output: {
      css: 'src/Blocks',
      js: 'src/Blocks/js'
    }
  },
  assets: {
    scss: 'assets/scss/**/*.scss',
    js: ['assets/js/**/*.js', '!assets/js/**/*.min.js']
  },
  output: {
    blocks: 'src/Blocks',
    assets: 'assets'
  }
};

// Compile SCSS for blocks (compiles app.scss to app.css)
function compileBlockScss() {
  return gulp.src(paths.blocks.scss)
    .pipe(sourcemaps.init())
    .pipe(gulpSass({
      sass: sass,
      outputStyle: 'expanded',
      silenceDeprecations: ['legacy-js-api', 'import']
    }).on('error', gulpSass.logError))
    .pipe(autoprefixer({
      cascade: false
    }))
    .pipe(rename('app.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.blocks.output.css))
    .pipe(cleanCSS({ compatibility: 'ie8' }))
    .pipe(rename('app.min.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.blocks.output.css));
}

// Compile SCSS for assets
function compileAssetScss() {
  return gulp.src(paths.assets.scss)
    .pipe(sourcemaps.init())
    .pipe(gulpSass({
      sass: sass,
      outputStyle: 'expanded',
      silenceDeprecations: ['legacy-js-api', 'import']
    }).on('error', gulpSass.logError))
    .pipe(autoprefixer({
      cascade: false
    }))
    .pipe(rename(function(path) {
      // Remove 'scss' directory from path, output to assets/css/
      // assets/scss/admin.scss -> assets/css/admin.css
      path.dirname = 'css';
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.output.assets))
    .pipe(cleanCSS({ compatibility: 'ie8' }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.output.assets));
}

// Minify JS for blocks
function minifyBlockJs() {
  return gulp.src(paths.blocks.js)
    .pipe(sourcemaps.init())
    .pipe(terser())
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.blocks.output.js));
}

// Minify JS for assets
function minifyAssetJs() {
  return gulp.src(['assets/js/**/*.js', '!assets/js/**/*.min.js'])
    .pipe(sourcemaps.init())
    .pipe(terser())
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.output.assets + '/js'));
}

// Watch task
function watch() {
  gulp.watch('src/Blocks/scss/**/*.scss', compileBlockScss);
  gulp.watch(paths.assets.scss, compileAssetScss);
  gulp.watch(paths.blocks.js, minifyBlockJs);
  gulp.watch(['assets/js/**/*.js', '!assets/js/**/*.min.js'], minifyAssetJs);
}

// Build task
const build = gulp.parallel(
  compileBlockScss,
  compileAssetScss,
  minifyBlockJs,
  minifyAssetJs
);

// Export tasks
exports.compileBlockScss = compileBlockScss;
exports.compileAssetScss = compileAssetScss;
exports.minifyBlockJs = minifyBlockJs;
exports.minifyAssetJs = minifyAssetJs;
exports.watch = watch;
exports.build = build;
exports.default = build;

