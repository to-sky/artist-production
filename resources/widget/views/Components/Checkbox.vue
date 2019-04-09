<template>
  <div class="checkbox-home">
    <input
      type="checkbox"
      :id="'checked-' + index"
      :name="'checked-' + index"
      :value="param.color"
      v-model="checked"
    />
    <label
      v-bind:style="{ backgroundColor: param.color }"
      :for="'checked-' + index"
      @click="filterColorPlaces(param.color)"
    >
    </label>
    <label
      :for="'checked-' + index"
      @click="filterColorPlaces(param.color)"
      class="checkbox-price"
    >
      <span>€</span> {{ param.price }}
    </label>
  </div>
</template>

<script>
import { DANGER_COLOR } from "../../global.config.js";

export default {
  props: ["param", "index"],

  data: () => ({
    checked: false
  }),

  computed: {
    checkedColors() {
      return this.$store.getters.getCheckedColors;
    },
    places() {
      return this.$store.getters.getPlaces;
    }
  },

  methods: {
    filterColorPlaces(color) {
      this.$store.commit("changeCheckedColors", color);
      this.places.forEach(place => {
        place.isAvailable =
          place.color === DANGER_COLOR ||
          Object.keys(this.checkedColors).length === 0 ||
          !!this.checkedColors[place.color];
      });
    }
  }
};
</script>
