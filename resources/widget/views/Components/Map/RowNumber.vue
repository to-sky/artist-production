<template>
  <l-circle
    ref="RowNumber"
    :lat-lng="rowNumber.latlng"
    :color="'transparent'"
    :weight="weightSize"
    :fillColor="'transparent'"
    :fillOpacity="1"
    :radius="mapZoom * rowNumber.radius"
  >
  </l-circle>
</template>

<script>
import { LCircle } from "vue2-leaflet";

export default {
  props: ["rowNumber", "map"],

  data: () => ({
    weightSize: 0,
    numberTooltip: null
  }),

  components: {
    LCircle
  },

  computed: {
    mapZoom() {
      return this.map.getZoom();
    }
  },

  mounted() {
    this.numberTooltip = this.createNumberTooltip();
    this.updateNumberTooltipVisibility();

    this.map.on("zoomend", () => {
      this.updateNumberTooltipVisibility();
    });
  },

  methods: {
    createNumberTooltip() {
      let element = this.$refs.RowNumber.mapObject;
      element.removeEventListener("click");
      let htmlPopup = "<div>" + this.rowNumber.row + "</div>";
      let tooltip = element.bindTooltip(htmlPopup, {
        permanent: true,
        direction: "center",
        className: "popup-row"
      });
      element.addEventListener("click", event => {
        this.togglePlaceToBasket(event, this.rowNumber);
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
    }
  }
};
</script>
