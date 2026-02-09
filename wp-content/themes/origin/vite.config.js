import { defineConfig } from 'vite';
import path from 'path';

const usePolling = process.env.VITE_USE_POLLING === '1';
const isWatch = process.env.VITE_WATCH === '1';

export default defineConfig({
  build: {
    watch: usePolling ? { usePolling: true, interval: 100 } : undefined,
    outDir: 'assets',
    assetsDir: '',
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return '[name][extname]';
        },
      },
    },
    minify: !isWatch,
  },
  server: {
    host: true,
    cors: true,
    strictPort: true,
    port: 3000,
  },
});
