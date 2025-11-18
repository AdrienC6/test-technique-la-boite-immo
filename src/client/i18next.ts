import en from './locales/en.json';
import fr from './locales/fr.json';
import i18next, { Resource } from 'i18next';
import { initReactI18next } from 'react-i18next';

export const resources = {
  fr,
  en,
} satisfies Resource;

export const i18nInit = (lng: string): void => {
  if (i18next.isInitialized) {
    return;
  }

  i18next.use(initReactI18next).init({
    lng,
    resources,
    fallbackLng: { default: ['fr'] },
    preload: Object.keys(resources),
    interpolation: { escapeValue: false },
    saveMissing: process.env.NODE_ENV !== 'production',
    missingKeyHandler: (lngs, ns, key, fallbackValue) => {
      if (process.env.NODE_ENV === 'production') {
        return;
      }

      console.error(
        'Missing i18next key ' + JSON.stringify({
          lngs,
          ns,
          key,
          fallbackValue,
        }),
      );
    },
    missingKeyNoValueFallbackToKey: true,
  });
};
