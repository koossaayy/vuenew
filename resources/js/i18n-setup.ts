import { createI18n } from 'vue-i18n';
import enMessages from '../../lang/en.json';
import frMessages from '../../lang/fr.json';
import esMessages from '../../lang/es.json';

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    'en': enMessages,
    'fr': frMessages,
    'es': esMessages,
  },
});

export default i18n;