<template>
  <div v-if="fanzoneIsReady">
    <l-layer-group>
      <l-polygon
        :lat-lngs="place.latlngs"
        ref="Fanzone"
        @add="showSceneText($event, place)"
        :interactive="false"
        :stroke="false"
        :fillOpacity="1"
        :weight="1"
        :fillColor="'#ccc'"
      >
        <l-popup
          :options="{
            autoClose: false,
            autoPan: false,
            keepInView: true,
            closeOnClick: false,
            closeButton: false,
            className: 'scene-popup'
          }"
          v-bind:style="{ backgroundColor: '#ccc' }"
          v-html="$t('hall.fanzone')"
        >
        </l-popup>
      </l-polygon>
    </l-layer-group>
  </div>
</template>

<script>
import { LLayerGroup, LPolygon, LPopup } from "vue2-leaflet";
import lerp from "lerp-array";
import {
  changePlaceCommand,
  deletePlaceFromCartCommand,
  getOrderCommand
} from "../../../scripts/api/kartinaApi.js";

export default {
  props: ["place", "map", "token", "event"],
  data: () => ({
    fanzoneIsReady: false
  }),

  components: {
    LPopup,
    LPolygon,
    LLayerGroup
  },

  computed: {
    scene() {
      return this.$store.getters.getScene;
    }
  },

  methods: {
    showSceneText(event, place) {
      this.$nextTick(() => {
        event.target.removeEventListener("click");
        event.target.addEventListener("click", () => {
          this.togglePlaceToBasket(event, place);
        });
        event.target.openPopup();
      });
    },

    placeSuccessfullyAdded(place) {
      this.animatePlaceToBasket();
      this.$store.commit("addSelectedItems", place);
    },

    togglePlaceToBasket(event, place) {
      changePlaceCommand(this.token, this.event, place.id).then(() => {
        getOrderCommand(this.token).then(order => {
          let lastOrderItem = order.items.length - 1;
          place.orderId = order.items[lastOrderItem].id;
          this.placeSuccessfullyAdded(place);
        });
      });
    },

    animatePlaceToBasket() {
      this.$nextTick(() => {
        let place = this.$refs.Fanzone.mapObject;
        let startLatLng = this.place.fanzoneCenter;

        let animatedPoint = L.circle(
          [startLatLng.lat, startLatLng.lng],
          place.options
        );

        animatedPoint.setRadius(50000);

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
    },

    unproject(x, y) {
      let maxZoomSize = 3;
      return this.map.unproject([x, y], maxZoomSize);
    },

    computedFanzoneCenter(place, fanzoneArea) {
      let center = this.unproject(
        (fanzoneArea.minX + fanzoneArea.maxX) / 2,
        (fanzoneArea.minY + fanzoneArea.maxY) / 2
      );
      place.fanzoneCenter = center;
    },

    computedMaxBoundsFanzone(place) {
      // computed area of fanzone
      let width = parseFloat(place.width);
      let height = parseFloat(place.height);
      let minX = parseFloat(place.x);
      let maxX = parseFloat(place.x) + width;
      let minY = parseFloat(place.y);
      let maxY = parseFloat(place.y) + height;
      let topleft = this.unproject(minX - width / 2, minY - height / 2);
      let topright = this.unproject(maxX - width / 2, minY - height / 2);
      let bottomleft = this.unproject(minX - width / 2, maxY - height / 2);
      let bottomright = this.unproject(maxX - width / 2, maxY - height / 2);
      place.latlngs = [
        [topleft.lat, topleft.lng],
        [topright.lat, topright.lng],
        [bottomright.lat, bottomright.lng],
        [bottomleft.lat, bottomleft.lng]
      ];
      place["isFanzone"] = true;
      this.computedFanzoneCenter(place, {
        minX: minX - width / 2,
        maxX: maxX - width / 2,
        minY: maxY - height / 2,
        maxY: maxY - height / 2
      });
      this.fanzoneIsReady = true;
    }
  },

  mounted() {
    this.computedMaxBoundsFanzone(this.place);
  }
};
</script>
