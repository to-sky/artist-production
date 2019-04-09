import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store/store";
import VModal from "vue-js-modal";
import VueI18n from "vue-i18n";
import VueCarousel from "vue-carousel";
import VueSweetAlert from "vue-sweetalert2";

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
      row: "Row: ",
      num: "Num: ",
      price: "Price: "
    }
  },
  de: {
    zoneDescription: {
      busy: "Beschäftigt",
      reserve: "Reservieren",
      zoneTitle: "Preiskategorien der Tickets"
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
      row: "Reihe: ",
      num: "Anzahl: ",
      price: "Preis: "
    }
  },
  ru: {
    zoneDescription: {
      busy: "Занято",
      reserve: "Резерв",
      zoneTitle: "Ценовые категории билетов"
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
      row: "Ряд: ",
      num: "Место: ",
      price: "Цена: "
    }
  }
};

const i18n = new VueI18n({
  locale: "de",
  messages
});

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount("#app");
