import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './main.css';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { i18nInit } from './i18next';
import { Index } from './pages';
import { ExportsPage } from './pages/exports';
import Layout from './components/layout';

i18nInit('fr');

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <BrowserRouter>
      <Routes>
        <Route element={<Layout />}>
          <Route path="/" element={<Index />} />
          <Route path="/exports" element={<ExportsPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  </StrictMode>,
);
