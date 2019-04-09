<template>
  <div class="home">
    <header-home></header-home>
    <hall v-if="zones.length"
          v-bind:zones="zones"
          v-bind:endTokenTime="endTokenTime"
          v-bind:token="token"
          v-bind:event="event">
    </hall>
  </div>
</template>

<script>
// @ is an alias to /src
import {
  getAuthToken,
  getPartnerEvents,
  getEventStatus,
  loadHall,
  getOrderCommand,
  updateFlashEventDataCommand
} from "../scripts/api/kartinaApi";
import { addParamsToZone } from "../scripts/home.js";
import {
  KASSIR_URL,
  TOKEN_LIFECYCLE,
  DISABLE_ELEMENT_COLOR,
  DANGER_COLOR
} from "../global.config";
import hall from "./Components/Hall.vue";
import moment from "moment";
import headerHome from "./Components/Header.vue";
import convert from "xml-js";

export default {
  name: "home",
  data: () => ({
    prices: [],
    zones: [],
    token: null,
    event: null,
    endTokenTime: null,
    interval: "",
    ts: 1
  }),

  methods: {
    checkTokenLife(tokenRes) {
      let momentFormat = "DD-MM-YYYY HH:mm:ss";
      let oldToken = localStorage.getItem("token");
      let tokenTime = moment().format(momentFormat);
      let tokenExist;
      if (oldToken === null) {
        localStorage.setItem("token", tokenRes);
        localStorage.setItem("tokenTime", tokenTime);
        tokenExist = false;
      } else {
        // check token lifecycle
        let oldTime = localStorage.getItem("tokenTime");
        if (oldTime === null) {
          localStorage.setItem("tokenTime", tokenTime);
          oldTime = tokenTime;
        }
        let diffInMinutes = moment(tokenTime, momentFormat).diff(
          moment(oldTime, momentFormat),
          "minutes"
        );
        if (diffInMinutes >= TOKEN_LIFECYCLE) {
          localStorage.setItem("token", tokenRes);
          localStorage.setItem("tokenTime", tokenTime);
        } else {
          tokenRes = oldToken;
          tokenExist = true;
          this.endTokenTime = oldTime;
        }
      }
      return {
        tokenRes,
        tokenExist
      };
    },

    getTsFromApi(xml) {
      let result = convert.xml2json(xml, {
        compact: true
      });
      let resultJson = JSON.parse(result);
      if (
        !resultJson.update ||
        !resultJson.update._attributes ||
        !resultJson.update._attributes.ts
      )
        return;
      return resultJson.update._attributes.ts;
    },

    parseXmlToJson(xml) {
      let result = convert.xml2json(xml, {
        compact: true
      });
      return JSON.parse(result);
    },

    updatePlaceStatus(place) {
      let zones = this.zones;
      zones.forEach(zone => {
        if (zone.id === place.zoneId) {
          zone.places.forEach(storePlace => {
            if (storePlace.id === place.id) {
              storePlace.status = place.status;
              if (place.status === 1 || place.status === 3) {
                storePlace.disable = true;
                storePlace.status = place.status.toString();
                storePlace.radius = 7500;
                storePlace.color = DISABLE_ELEMENT_COLOR;
              }
              if (place.status === 4) {
                storePlace.disable = true;
                storePlace.status = place.status.toString();
                storePlace.radius = 21000;
                storePlace.color = DANGER_COLOR;
              }
            }
          });
        }
      });
    }
  },

  mounted: function() {
    let self = this;
    let event = window.id;
    this.event = event;
    getAuthToken().then(tokenRes => {
      let tokenObject = this.checkTokenLife(tokenRes);
      let token = tokenObject.tokenRes;
      self.token = token;
      getEventStatus(token, event).then(response => {
        self.prices = response.prices;
        self.$store.commit(
          "setEndDate",
          moment(response.SalesEndTime, "YYYY-MMM-DD HH:mm")
        );
        self.$store.commit(
          "setEndTime",
          moment(response.SalesEndTime, "YYYY-MMM-DD HH:mm")
        );
      });

      getPartnerEvents(token, event).then(response => {
        self.$store.commit("setEventName", response.ActivityName);
        self.$store.commit(
          "setEventImg",
          KASSIR_URL + response.ActivityImageSmall
        );
        self.$store.commit("setBuildingAddress", response.BuildingName);
        self.$store.commit("setHallName", response.HallName);
        self.$store.commit("setCityName", response.City);
      });

      loadHall(token, event).then(zones => {
        self.zones = addParamsToZone(zones, self.prices);
        if (tokenObject.tokenExist) {
          getOrderCommand(token).then(response => {
            if (!response.items.length) return;
            response.items.forEach(item => {
              self.zones.forEach(zone => {
                zone.places.forEach(place => {
                  if (place.id === item.pid) {
                    place.isTarget = true;
                    place.weightSize = 1;
                    place.orderId = item.id;
                    if (item.zone === "Stehplatz") {
                      place.isFanzone = true;
                    }
                    self.$store.commit("addSelectedItems", place);
                  }
                });
              });
            });
          });
        }

        // update hall from api with timestamp
        self.interval = setInterval(() => {
          updateFlashEventDataCommand(token, event, self.ts).then(result => {
            let oldTs = self.ts;
            let newTs = self.getTsFromApi(result);
            if (typeof newTs !== "undefined") self.ts = newTs;
            if (oldTs === 1) return;
            let resultParse = self.parseXmlToJson(result);

            // update places from listening api
            if (!resultParse.update.p) return;
            if (resultParse.update.p.length > 1) {
              resultParse.update.p.forEach(place => {
                self.updatePlaceStatus(place._attributes);
              });
            } else {
              self.updatePlaceStatus(resultParse.update.p._attributes);
            }
          });
        }, 1500);
      });
    });
  },

  components: {
    hall,
    headerHome
  }
};
</script>

<style lang="styl">
  @import "../styles/checkbox-home.styl";
  @import "../styles/header-home.styl";
  @import "../styles/hall.styl";
</style>
