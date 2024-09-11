import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  root: path.resolve(__dirname, 'resources/scripts'),
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/scripts'),
    },
  },
  build: {
    outDir: '../../public',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'resources/scripts/main.tsx'),
      },
      output: {
        entryFileNames: 'assets/index.php',
        chunkFileNames: 'assets/index.php',
        assetFileNames: 'assets/index.[ext]',
      }
    }
  },
  server: {
    proxy: {
      '/': 'http://45.137.68.42:81'
    }
  }
})
