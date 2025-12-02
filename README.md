# NXW Page Builder

Modular, scalable WordPress page builder with React blocks.

## Development

### Prerequisites
- Node.js and npm
- WordPress development environment

### Setup

1. Install dependencies:
```bash
npm install
```

2. Build assets:
```bash
npm run build
```

3. Watch for changes (development):
```bash
npm run watch
```

### Gulp Tasks

- `gulp build` - Compile all SCSS to CSS and minify JS/CSS
- `gulp watch` - Watch for changes and auto-compile
- `gulp compileBlockScss` - Compile block SCSS files
- `gulp compileAssetScss` - Compile asset SCSS files
- `gulp minifyBlockJs` - Minify block JavaScript files
- `gulp minifyAssetJs` - Minify asset JavaScript files

## Structure

- **Blocks**: Each block has its own directory with `view.php` (HTML), `style.scss` (SCSS with BEM naming), and `frontend.js`
- **Admin**: Uses TailwindCSS for admin interface
- **Frontend**: Blocks use compiled SCSS (not TailwindCSS) with BEM naming convention
- **Build**: Gulp compiles SCSS to CSS and minifies both JS and CSS

## Block Development

When creating a new block:
1. Create block directory in `src/Blocks/{BlockName}/`
2. Add `block.json` configuration
3. Create `view.php` with HTML (no TailwindCSS classes)
4. Create `style.scss` using BEM naming (import `_blocks-common.scss` for shared styles)
5. Run `gulp build` to compile SCSS to CSS

