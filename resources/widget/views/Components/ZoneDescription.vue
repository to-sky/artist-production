<template>
  <div class="zone-description">
    <div class="description">
      <div class="description-container">
        <div
          class="description__circle"
          :style="{ backgroundColor: '#aaa' }"
        ></div>
        <div
          class="description__text"
          v-html="$t('zoneDescription.busy')"
        ></div>
      </div>
      <div class="description-container">
        <div
          class="description__circle"
          :style="{ backgroundColor: '#f00' }"
        ></div>
        <div
          class="description__text"
          v-html="$t('zoneDescription.reserve')"
        ></div>
      </div>
    </div>

    <div
      class="container"
      :class="{ 'container-small-length': colorsLength }"
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
        <slide v-for="(param, index) in event.prices" :key="index">
          <checkbox :param="param" :index="index"> </checkbox>
        </slide>
      </carousel>
    </div>
  </div>
</template>

<script>
import checkbox from "./Checkbox.vue";

export default {
  props: ['event'],

  components: {
    checkbox
  },

  data: () => ({
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
  }
};
</script>
