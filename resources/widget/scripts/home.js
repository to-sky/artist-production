import { DISABLE_ELEMENT_COLOR, DANGER_COLOR } from "../global.config";
import store from "../store/store.js";

function addParamsToZone(zones, prices) {
  // add all colors and prices to zones
  for (let i = 0; i < prices.length; i++) {
    let colorWithPrice = {
      color: prices[i].color.toLowerCase(),
      price: prices[i].prices[0].Цена
    };
    store.commit("setArrayColors", colorWithPrice);
  }
  // add params to each place of zones
  let places = [];
  for (let i = 0; i < zones.length; i++) {
    for (let j = 0; j < zones[i].places.length; j++) {
      let currentPrice = Math.round(zones[i].places[j].price[0].value);

      zones[i].places[j].isAvailable = true;
      zones[i].places[j].isTarget = false;
      zones[i].places[j].havePopup = false;
      zones[i].places[j].havePopupNum = false;
      zones[i].places[j].havePopupRow = false;
      zones[i].places[j].orderId = null;
      zones[i].places[j].weightSize = 0;

      for (let k = 0; k < prices.length; k++) {
        let priceWithColor = Math.round(prices[k].prices[0].Цена);
        if (
          zones[i].places[j].status === "1" ||
          zones[i].places[j].status === "3"
        ) {
          zones[i].places[j]["color"] = DISABLE_ELEMENT_COLOR;
          zones[i].places[j]["disable"] = true;
          zones[i].places[j]["cursor"] = "unset";
          zones[i].places[j]["radius"] = 7500;
        } else if (zones[i].places[j].status === "4") {
          zones[i].places[j]["color"] = DANGER_COLOR;
          zones[i].places[j]["disable"] = true;
          zones[i].places[j]["cursor"] = "unset";
          zones[i].places[j]["radius"] = 21000;
        } else if (priceWithColor === currentPrice) {
          zones[i].places[j]["color"] = prices[k].color.toLowerCase();
          zones[i].places[j]["disable"] = false;
          zones[i].places[j]["cursor"] = "pointer";
          zones[i].places[j]["radius"] = 21000;
          places.push(zones[i].places[j]);
        }
      }
    }
  }
  store.commit("setPlaces", places);
  return zones;
}

export { addParamsToZone };
