import 'i18next';
import { resources } from '../i18next';

declare module 'i18next' {
  // Extend CustomTypeOptions
  interface CustomTypeOptions {
    // custom resources type
    resources: (typeof resources)['fr']
  }
}
