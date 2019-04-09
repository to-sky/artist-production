<template>
  <div class="container">
    <l-map
      :zoom="zoom"
      :maxZoom="5"
      :noMoveStart="true"
      :minZoom="0"
      :center="center"
      ref="Hall"
      :crs="crs"
      :options="{
        attributionControl: false,
        zoomAnimated: false,
        animate: false,
        noMoveStart: true,
        zoomDelta: 0.5,
        zoomSnap: 0,
        preferCanvas: true
      }"
      v-bind:style="{
        height: containerHeight
      }"
    >
      <div v-if="mapLoaded">
        <l-layer-group v-for="(zone, zoneIndex) in zones" :key="zoneIndex">
          <place v-if="zone.places.length > 0"
            v-for="(place, placeIndex) in zone.places"
            :key="placeIndex"
            v-bind:map="map"
            v-bind:place="place"
            v-bind:token="token"
            v-bind:event="event"
          >
          </place>
        </l-layer-group>
        <l-layer-group>
          <l-polygon
            v-if="sceneIsReadyWithoutApi"
            :lat-lngs="scene.latlngs"
            :interactive="false"
            @add="showSceneText"
            :stroke="false"
            :fillOpacity="1"
            :weight="1"
            :fillColor="'#ccc'"
          >
            <l-popup
              :options="{
                autoClose: false,
                closeOnClick: false,
                closeButton: false,
                className: 'scene-popup'
              }"
              v-bind:style="{ backgroundColor: '#fff' }"
              v-html="$t('hall.scene')"
            >
            </l-popup>
          </l-polygon>
        </l-layer-group>
        <div v-if="sceneIsReadyApi">
          <l-layer-group v-for="(currentScene, index) in scene" :key="index">
            <l-polygon
              :lat-lngs="currentScene.latlngs"
              :interactive="false"
              @add="showSceneText"
              :stroke="false"
              :fillOpacity="1"
              :weight="1"
              :fillColor="'#ccc'"
            >
              <l-popup
                v-if="!!currentScene.text"
                :options="{
                  autoClose: false,
                  closeOnClick: false,
                  closeButton: false,
                  className: 'scene-popup'
                }"
                v-bind:style="{ backgroundColor: '#fff' }"
                v-html="$t('hall.scene')"
              >
              </l-popup>
            </l-polygon>
          </l-layer-group>
        </div>
      </div>
    </l-map>
  </div>
</template>

<script>
import place from "./Place.vue";
import rowNumber from "./RowNumber.vue";
import { LMap, LLayerGroup, LPolygon, LPopup } from "vue2-leaflet";
import groupBy from "lodash/groupBy";
import orderBy from "lodash/orderBy";

export default {
  props: ["zones", "token", "event"],
  components: {
    LPopup,
    LPolygon,
    LMap,
    LLayerGroup,
    place,
    rowNumber
  },

  data: () => ({
    zoom: 2,
    mapLoaded: false,
    center: undefined,
    map: "",
    sceneIsReadyWithoutApi: false,
    sceneIsReadyApi: false,
    crs: L.CRS.EPSG4326,
    rowNumbers: []
  }),

  computed: {
    containerHeight() {
      return this.$store.getters.getZoneContainer.height / 1.5 + "px";
    },

    scene() {
      return this.$store.getters.getScene;
    }
  },

  methods: {
    // computed maximum size of the map area
    computedMapArea(mapArea) {
      let center = this.unproject(
        (mapArea.minX + mapArea.maxX) / 2,
        (mapArea.minY + mapArea.maxY) / 2
      );
      this.center = L.latLng(center);
    },

    showSceneText(event) {
      this.$nextTick(() => {
        event.target.removeEventListener("click");
        event.target.openPopup();
      });
    },

    computedLatLngPlaces() {
      this.map = this.$refs.Hall.mapObject;
      this.zones.forEach(zone => {
        zone.places.forEach(place => {
          let x = place.x + zone.area.left;
          let y = place.y + zone.area.top;

          // conversion coords from places to leaflet
          let coords = this.unproject(x, y);
          place.lat = coords.lat;
          place.lng = coords.lng;
          place.latlng = {
            lat: place.lat,
            lng: place.lng
          };
        });

        zone.rowNumbers = [];

        const groupedPlaces = groupBy(zone.places, "row");

        for (let row in groupedPlaces) {
          let places = groupedPlaces[row];

          places = orderBy(places, "lat");
          places = orderBy(places, "lng");

          let firstPlace = places[0];
          let lastPlace = places[places.length - 1];
          let eps = 0.0001;
          let isVertical =
            Math.abs(firstPlace.lat - lastPlace.lat) >= eps &&
            places.length > 1;
          let delta = 3;

          if (isVertical) {
            this.rowNumbers.push(
              this.createRowNumber(firstPlace, "lat", -delta)
            );
            this.rowNumbers.push(
              this.createRowNumber(lastPlace, "lat", +delta)
            );
          } else {
            this.rowNumbers.push(
              this.createRowNumber(firstPlace, "lng", -delta)
            );
            this.rowNumbers.push(
              this.createRowNumber(lastPlace, "lng", +delta)
            );
          }
        }
      });
    },

    createRowNumber(place, deltaProperty, delta) {
      let rowNumber = {
        row: place.row,
        radius: 21000,
        lng: place.lng,
        lat: place.lat
      };

      rowNumber[deltaProperty] += delta;

      rowNumber.latlng = {
        lat: rowNumber.lat,
        lng: rowNumber.lng
      };

      return rowNumber;
    },

    computedMaxBoundsOfMap() {
      let minX = Infinity,
        minY = Infinity;
      let maxX = -Infinity,
        maxY = -Infinity;
      this.zones.forEach(zone => {
        let x = zone.area.left, y = zone.area.top;
        // zone.places.forEach(place => {
        //   let x = place.x + zone.area.left;
        //   let y = place.y + zone.area.top;
        //
        //   // computed max area of bound
        //   minX = Math.min(minX, x);
        //   maxX = Math.max(maxX, x);
        //   minY = Math.min(minY, y);
        //   maxY = Math.max(maxY, y);
        // });
        minX = Math.min(minX, x);
        maxX = Math.max(maxX, x + zone.area.width);
        minY = Math.min(minY, y);
        maxY = Math.max(maxY, y + zone.area.height);
      });
      return {
        minX,
        maxX,
        minY,
        maxY
      };
    },

    computedSceneWithoutApi(sizes) {
      let width = sizes.maxX - sizes.minX;
      let height = sizes.maxY - sizes.minY;
      let topleft = this.unproject(
        sizes.minX + width / 4,
        sizes.minY - height / 3 - 50
      );
      let topright = this.unproject(
        sizes.minX + width - width / 4,
        sizes.minY - height / 3 - 50
      );
      let bottomleft = this.unproject(
        sizes.minX + width / 4,
        sizes.minY + height - height - 50
      );
      let bottomright = this.unproject(
        sizes.minX + width - width / 4,
        sizes.minY + height - height - 50
      );
      this.scene.latlngs = [
        [topleft.lat, topleft.lng],
        [topright.lat, topright.lng],
        [bottomright.lat, bottomright.lng],
        [bottomleft.lat, bottomleft.lng]
      ];
    },

    computedSceneFromApi(scene) {
      // computed area of scene if scene exist from api
      let minX = parseFloat(scene.x);
      let maxX = parseFloat(scene.x) + parseFloat(scene.width);
      let minY = parseFloat(scene.y);
      let maxY = parseFloat(scene.y) + parseFloat(scene.height);
      let width = maxX - minX;
      let height = maxY - minY;
      let topleft = this.unproject(minX - width / 2, minY - height / 2);
      let topright = this.unproject(maxX - width / 2, minY - height / 2);
      let bottomleft = this.unproject(minX - width / 2, maxY - height / 2);
      let bottomright = this.unproject(maxX - width / 2, maxY - height / 2);
      scene.latlngs = [
        [topleft.lat, topleft.lng],
        [topright.lat, topright.lng],
        [bottomright.lat, bottomright.lng],
        [bottomleft.lat, bottomleft.lng]
      ];
    },

    computedScene() {
      this.computedLatLngPlaces();
      let sizes = this.computedMaxBoundsOfMap();
      if (!this.scene.length) {
        this.computedSceneWithoutApi({
          minX: sizes.minX,
          minY: sizes.minY,
          maxX: sizes.maxX,
          maxY: sizes.maxY
        });
        this.sceneIsReadyWithoutApi = true;
      } else {
        this.scene.forEach(scene => {
          this.computedSceneFromApi(scene);
        });
        this.sceneIsReadyApi = true;
      }
      this.computedMapArea({
        minX: sizes.minX,
        minY: sizes.minY,
        maxX: sizes.maxX,
        maxY: sizes.maxY
      });
      this.sceneIsReady = true;
    },

    unproject(x, y) {
      let maxZoomSize = 3;
      let map = this.$refs.Hall.mapObject;
      return map.unproject([x, y], maxZoomSize);
    }
  },

  mounted() {
    let self = this;
    this.$nextTick(() => {
      this.computedScene();
      if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
          navigator.userAgent
        )
      )
        self.zoom = 0;
      this.mapLoaded = true;

      // disable preloader when map is loaded
      document.getElementById("preloader").classList.add("preloader-disable");
    });
  }
};
</script>

<style>
@import "../../../../node_modules/leaflet/dist/leaflet.css";
</style>
