<template>
  <div class="hall">
    <zone-description></zone-description>
    <hall-map :zones="zones" :token="token" :event="event" :selected="selected" @updatePlace="flushPlace($event)"></hall-map>
    <checkout v-bind:endTokenTime="endTokenTime"
              v-bind:token="token">
    </checkout>
    <div
      class="current-zone__buy-tickets"
      v-html="buttonBuyTickets"
      @click="openCheckoutPopup"
    ></div>
  </div>
</template>

<script>
import hallMap from "./Map/HallMap.vue";
import zoneDescription from "./ZoneDescription.vue";
import checkout from "./Checkout.vue";

export default {
  props: ["zones", "event", "token", "endTokenTime"],

  components: {
    hallMap,
    zoneDescription,
    checkout
  },

  data() {
    let self = this;

    return {
      selected: []
    };
  },

  mounted() {
    this.$nextTick(() => {
      this.selected = this.selectedPlaces;
    });
  },

  computed: {
    selectedPlaces() {
      return this.$store.getters.getSelectedItems;
    },
    buttonBuyTickets() {
      if (!this.selectedPlaces.length) return "";
      return this.computedButtonHtml(this.selectedPlaces);
    }
  },

  methods: {
    openCheckoutPopup() {
      this.$modal.show("checkout-zone");
      this.$store.commit("setCheckoutPopup", true);
    },

    flushPlace(place) {
      let toDel = false;
      this.selected.forEach((e, i) =>  {
        if (e.id === place.id) {
          toDel = true;
          this.selected.splice(i, 1);
        }
      });

      if (!toDel) {
        this.selected.push(place);
      }
    },

    computedButtonHtml(places) {
      let finalPrice = 0;
      places.forEach(price => {
        finalPrice += parseFloat(price.price[0].value);
      });

      return (
        "<div class='current-zone__buy-tickets_size'>" +
        this.$tc("buyButton.ticket", places.length, {
          count: places.length,
          price: finalPrice.toFixed(2)
        }) +
        "</div>"
      );
    }
  }
};
</script>
