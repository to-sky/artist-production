<template>
  <div class="zone-description">
    <div class="description">
      <div class="description-container">
        <div
          class="description__circle"
          v-bind:style="{ backgroundColor: DISABLE_ELEMENT_COLOR }"
        ></div>
        <div
          class="description__text"
          v-html="$t('zoneDescription.busy')"
        ></div>
      </div>
      <div class="description-container">
        <div
          class="description__circle"
          v-bind:style="{ backgroundColor: DANGER_COLOR }"
        ></div>
        <div
          class="description__text"
          v-html="$t('zoneDescription.reserve')"
        ></div>
      </div>
    </div>

    <div
      class="container"
      v-bind:class="{ 'container-small-length': colorsLength }"
    >
      <h3
        class="zone-description__title"
        v-html="$t('zoneDescription.zoneTitle')"
      ></h3>
      <carousel
        :navigationEnabled="true"
        :perPage="3"
        :perPageCustom="[[320, 3], [768, 4], [1024, 5], [1200, 6], [1600, 7]]"
        :paginationEnabled="false"
        :navigationNextLabel="'›'"
        :navigationPrevLabel="'‹'"
        class="checkboxes-home-list"
      >
        <slide v-for="(param, index) in params" :key="index">
          <checkbox v-bind:param="param" v-bind:index="index"> </checkbox>
        </slide>
      </carousel>
    </div>
  </div>
</template>

<script>
import checkbox from "./Checkbox.vue";
import {
  DISABLE_ELEMENT_COLOR,
  DANGER_COLOR,
  ACTIVE_COLOR
} from "../../global.config.js";

export default {
  data: () => ({
    DISABLE_ELEMENT_COLOR: DISABLE_ELEMENT_COLOR,
    DANGER_COLOR: DANGER_COLOR,
    ACTIVE_COLOR: ACTIVE_COLOR,
    colorsLength: false,
    options: {
      navigationEnabled: true,
      autoplayHoverPause: true
    }
  }),

  computed: {
    params() {
      return this.$store.getters.getArrayColors;
    }
  },

  mounted() {
    if (this.params.length <= 4) this.colorsLength = true;
  },

  components: {
    checkbox
  }
};
</script>
