import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './main.css';
import { Index } from './pages/index';
import { i18nInit } from './i18next';

i18nInit('fr');

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <Index />
  </StrictMode>,
);
