<template>
  <div v-if="place.template === 'fanzone'">
    <fanzone v-bind:place="place"
             v-bind:map="map"
             v-bind:token="token"
             v-bind:event="event">
    </fanzone>
  </div>
  <div v-else>
    <l-circle
      ref="Place"
      v-bind:style="{ cursor: place.cursor }"
      :lat-lng="place.latlng"
      :color="weightColor"
      :weight="place.weightSize"
      :fillColor="placeColor"
      :fillOpacity="1"
      :radius="mapZoom * place.radius"
      @mouseover="showHoverPopup()"
      @mouseleave="hideHoverPopup()"
      @click="togglePlaceToBasket($event, place)"
    >
    </l-circle>
  </div>
</template>

<script>
import fanzone from "./Fanzone.vue";
import { LCircle } from "vue2-leaflet";
import { DISABLE_ELEMENT_COLOR } from "../../../global.config.js";
import { ACTIVE_COLOR } from "../../../global.config.js";
import lerp from "lerp-array";
import {
  changePlaceCommand,
  deletePlaceFromCartCommand,
  getOrderCommand
} from "../../../scripts/api/kartinaApi.js";

export default {
  props: ["place", "map", "token", "event"],

  data: () => ({
    weightColor: "",
    addedMobilePopup: false,
    numberTooltip: null,
    hoverPopup: null
  }),

  components: {
    LCircle,
    fanzone
  },

  computed: {
    isMobile() {
      if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
          navigator.userAgent
        ) &&
        window.innerWidth < 1025
      )
        return true;
      return false;
    },

    placeColor() {
      if (this.place.isTarget) return ACTIVE_COLOR;
      return this.place.isAvailable ? this.place.color : DISABLE_ELEMENT_COLOR;
    },

    mapZoom() {
      return this.map.getZoom();
    }
  },

  mounted() {
    this.color = this.place.color;
    this.weightColor = this.place.color;
    if (this.$refs.Place === undefined) return;

    if (this.place.num && !this.place.disable) {
      this.numberTooltip = this.createNumberTooltip();
      this.updateNumberTooltipVisibility();

      this.map.on("zoomend", () => {
        this.updateNumberTooltipVisibility();
      });
    }
  },

  methods: {
    createHoverPopup() {
      let element = this.$refs.Place.mapObject;

      let htmlPopup =
        "<div>" +
        this.$i18n.t("place.row") +
        this.place.row +
        "</div>" +
        "<div>" +
        this.$i18n.t("place.num") +
        this.place.num +
        "</div>" +
        "<div>" +
        this.$i18n.t("place.price") +
        this.place.price[0].value +
        "</div>";
      return element.bindPopup(htmlPopup, {
        closeButton: false,
        className: "popup-place"
      });
    },

    createNumberTooltip() {
      let element = this.$refs.Place.mapObject;
      element.removeEventListener("click");
      let htmlPopup = "<div>" + this.place.num + "</div>";
      let tooltip = element.bindTooltip(htmlPopup, {
        permanent: true,
        direction: "center",
        className: "popup-place-mobile"
      });
      element.addEventListener("click", event => {
        this.togglePlaceToBasket(event, this.place);
      });
      return tooltip;
    },

    updateNumberTooltipVisibility() {
      const zoom = this.map.getZoom();

      if (zoom < 3) {
        this.hideNumberTooltip();
      } else {
        this.showNumberTooltip();
      }
    },

    showNumberTooltip() {
      this.$nextTick(() => {
        this.numberTooltip = this.numberTooltip || this.createNumberTooltip();
        this.numberTooltip.openTooltip();
      });
    },

    hideNumberTooltip() {
      this.$nextTick(() => {
        this.numberTooltip = this.numberTooltip || this.createNumberTooltip();
        this.numberTooltip.closeTooltip();
      });
    },

    showHoverPopup() {
      this.$nextTick(() => {
        if (this.place.disable || this.isMobile) return;
        this.hoverPopup = this.hoverPopup || this.createHoverPopup();
        this.hoverPopup.openPopup();
      });
    },

    hideHoverPopup() {
      this.$nextTick(() => {
        if (this.place.disable || this.isMobile) return;
        this.hoverPopup = this.hoverPopup || this.createHoverPopup();
        this.hoverPopup.closePopup();
      });
    },

    placeSuccessfullyAdded(event, place) {
      // event.target.openPopup();
      if (place.disable || !place.isAvailable) return;
      if (!place.isTarget) {
        this.animatePlaceToBasket();
        place.isTarget = true;
        this.weightColor = "#333333";
        place.weightSize = 1;
        this.$store.commit("addSelectedItems", place);
      } else {
        place.isTarget = false;
        this.weightColor = place.color;
        place.weightSize = 0;
        this.$store.commit("removeSelectedItems", place);
      }
    },

    togglePlaceToBasket(event, place) {
      if (!place.orderId) {
        changePlaceCommand(this.token, this.event, place.id).then(() => {
          getOrderCommand(this.token).then(order => {
            order.items.forEach(item => {
              if (place.id === item.pid) {
                place.orderId = item.id;
                this.placeSuccessfullyAdded(event, place);
              }
            });
            if (!place.orderId)
              return this.$swal({
                type: "error",
                text: "Something went wrong. Try a later"
              });
          });
        });
      } else {
        deletePlaceFromCartCommand(this.token, place.orderId).then(response => {
          if (response.errormessage)
            return this.$swal({
              type: "error",
              text: response.errormessage
            });
          place.orderId = null;
          this.placeSuccessfullyAdded(event, place);
        });
      }
    },

    animatePlaceToBasket() {
      this.$nextTick(() => {
        let place = this.$refs.Place.mapObject;
        let startLatLng = place.getLatLng();

        let animatedPoint = L.circle(
          [startLatLng.lat, startLatLng.lng],
          place.options
        );

        animatedPoint.setRadius(place.getRadius());

        let bounds = this.map.getBounds();
        let southWest = bounds.getSouthWest();
        let southEast = bounds.getNorthEast();

        let start = [startLatLng.lat, startLatLng.lng];
        let end = [southWest.lat, (southWest.lng + southEast.lng) / 2];

        animatedPoint.addTo(this.map);

        let points = this.interpolate(start, end);
        let zoom = this.map.getZoom();
        let intervalMs;
        switch (zoom) {
          case 4:
            intervalMs = 15;
            break;
          case 5:
            intervalMs = 15;
            break;
          default:
            intervalMs = 20;
            break;
        }

        this.animatePlace({
          place: animatedPoint,
          intervalMs,
          points,
          onEnd: () => {
            animatedPoint.remove();
          }
        });
      });
    },

    animatePlace(params) {
      params.intervalMs = params.intervalMs || 15;
      let i = 0;
      let intervalId = setInterval(() => {
        let point = params.points[i];
        params.place.setLatLng(point);
        if (i === params.points.length - 1) {
          clearInterval(intervalId);
          params.onEnd && params.onEnd();
          return;
        }
        i++;
      }, params.intervalMs);
    },

    interpolate(start, end, delta) {
      delta = delta || 0.01;
      let points = [];

      for (let t = 0; t <= 1; t += delta) {
        let point = lerp(start, end, t);
        points.push(point);
      }

      return points;
    }
  }
};
</script>
