import Vue from "vue";
import App from "./App.vue";
import store from "./store/store";
import VModal from "vue-js-modal";
import VueI18n from "vue-i18n";
import VueCarousel from "vue-carousel";
import VueSweetAlert from "vue-sweetalert2";
import axios from 'axios';
import UrlService from './services/UrlService/UrlService';

Object.assign(axios.defaults, {
  withCredentials: true
});

Vue.config.productionTip = false;
Vue.use(VModal);
Vue.use(VueI18n);
Vue.use(VueCarousel);
Vue.use(VueSweetAlert);

const messages = {
  en: {
    zoneDescription: {
      busy: "Busy",
      reserve: "Reserve",
      zoneTitle: "Price categories of tickets"
    },
    priceButton: {
      text: "Prices",
    },
    buyButton: {
      ticket:
        "Buy {count} ticket for {price} € | Buy {count} tickets for {price} €"
    },
    checkout: {
      checkout: "Сheckout",
      rowNumber: "Row: {row}, Number: {num}",
      price: "Price: € {price}",
      total: "Total:",
      confirm: "Confirm",
      timeSession: "Tickets are available for: ",
      timeSessionEnd: "Session time is over, please refresh the page."
    },
    hall: {
      scene: "STAGE",
      fanzone: "DANCEFLOOR"
    },
    place: {
      zone: "Zone: ",
      row: "Row: ",
      num: "Num: ",
      price: "Price: "
    },
    priceGroup: {
      standard: 'Standard'
    }
  },
  de: {
    zoneDescription: {
      busy: "Beschäftigt",
      reserve: "Reservieren",
      zoneTitle: "Preiskategorien der Tickets"
    },
    priceButton: {
      text: "Preise",
    },
    buyButton: {
      ticket:
        "Kaufen 1 ticket für {price} € | Kaufen {count} tickets für {price} €"
    },
    checkout: {
      checkout: "Auschecken",
      rowNumber: "Reihe: {row}, Nummer: {num}",
      price: "Preis: € {price}",
      total: "Gesamt:",
      confirm: "Bestätigen",
      timeSession: "Tickets sind erhältlich für: ",
      timeSessionEnd:
        "Sitzungszeit ist abgelaufen, bitte aktualisieren Sie die Seite."
    },
    hall: {
      scene: "BÜHNE",
      fanzone: "STEHPLÄTZE"
    },
    place: {
      zone: "Zone: ",
      row: "Reihe: ",
      num: "Anzahl: ",
      price: "Preis: "
    },
    priceGroup: {
      standard: 'Standard'
    }
  },
  ru: {
    zoneDescription: {
      busy: "Занято",
      reserve: "Резерв",
      zoneTitle: "Ценовые категории билетов"
    },
    priceButton: {
      text: "Цены",
    },
    buyButton: {
      ticket:
        "Купить {count} билет for {price} € | Купить {count} билета for {price} €"
    },
    checkout: {
      checkout: "Корзина",
      rowNumber: "Ряд: {row}, Место: {num}",
      price: "Стоимость: € {price}",
      total: "Итого:",
      confirm: "Подтверджение",
      timeSession: "Билеты доступны в течение: ",
      timeSessionEnd: "Время сессии закончилось, обновите страницу, пожалуйста."
    },
    hall: {
      scene: "СЦЕНА",
      fanzone: "СТОЯЧИЕ МЕСТА"
    },
    place: {
      zone: "Зона: ",
      row: "Ряд: ",
      num: "Место: ",
      price: "Цена: "
    },
    priceGroup: {
      standard: 'Стандартный'
    }
  }
};

const i18n = new VueI18n({
  locale: "de",
  messages
});

window.app = new Vue({
  store,
  i18n,
  render: h => h(App),

  data() {
    return {
      locales: ['de', 'en', 'ru'],
      defaultLocale: 'de',
      currentLocale: UrlService.get('lang', 'de', l => {
        this.currentLocale = l;
      })
    };
  },

  mounted() {
    this.changeLanguage(this.currentLocale);
  },

  methods: {
    changeLanguage(locale) {
      this.currentLocale = this.locales.includes(locale)
        ? locale
        : this.currentLocale || this.defaultLocale
      ;

      this.$i18n.locale = this.currentLocale;
    }
  },

  watch: {
    currentLocale(n, o) {
      this.changeLanguage(n);
    }
  }
}).$mount("#app");