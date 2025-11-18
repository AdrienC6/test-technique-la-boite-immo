import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';
import fs from 'fs';
import type { Plugin } from 'vite';
import tailwindcss from '@tailwindcss/vite';

function twigHmrPlugin(): Plugin {
  return {
    name: 'twig-hmr',
    configureServer(server) {
      const templatesDir = resolve(process.cwd(), 'templates');

      if (!fs.existsSync(templatesDir)) {
        console.warn('templates directory not found');

        return;
      }

      server.watcher.add(resolve(templatesDir, '**/*.twig'));

      server.watcher.on('change', (path) => {
        if (!path.endsWith('.twig')) {
          return;
        }

        server.ws.send({
          type: 'full-reload',
          path: '*',
        });
      });
    },
  };
}

// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), twigHmrPlugin(), tailwindcss()],
  root: resolve(__dirname, 'src/client'),
  base: '/assets/',
  build: {
    manifest: true,
    outDir: resolve(__dirname, 'public/assets'),
    rollupOptions: { input: { main: resolve(__dirname, 'src/client/index.tsx') } },
  },
  resolve: { alias: { '@': resolve(__dirname, './src/client') } },
});
