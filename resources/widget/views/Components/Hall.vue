<template>
  <div class="hall">
    <zone-description :event="event"></zone-description>
    <hall-map
        :event="event"
        :selected="selected"
        @updatePlace="flushPlace($event)"
        @updateZone="flushZone($event)"
        @hallMapLoaded="$emit('hallMapLoaded')"
    ></hall-map>
    <checkout
        v-show="showCheckout"
        :event="event"
        :selected="selected"
        :time="timeLimit"
        @deleteReserve="flushPlace($event)"
        @closeCheck="showCheckout = false"
        @sendCheckout="sendCheckout"
    ></checkout>
    <place-popup
        v-if="showPlace"
        :max="selectedZone.limit"
        @sendFanZone="countZone($event)"
    ></place-popup>
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
import placePopup from './PlacePopup.vue';
import moment from 'moment';

export default {
  props: ["event"],

  components: {
    hallMap,
    zoneDescription,
    checkout,
    placePopup
  },

  data() {
    return {
      selected: [],
      selectedZone: null,
      showCheckout: false,
      showPlace: false,
      timeLimit: moment().format("DD-MM-YYYY HH:mm:ss")
    };
  },

  mounted() {
  },

  computed: {
    buttonBuyTickets() {
      if (!this.selected.length) return "";
      return this.computedButtonHtml(this.selected);
    }
  },

  methods: {
    openCheckoutPopup() {
      this.$modal.show("checkout-zone");
      this.showCheckout = true;
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

    flushZone(place) {
      let toDel = false;
      this.selected.forEach((e, i) =>  {
        if (e.id === place.id) {
          toDel = true;
          this.selected.splice(i, 1);
        }
      });

      if (!toDel) {
        this.selectedZone = place;
        this.$modal.show("place-zone");
        this.showPlace = true;
      }
    },

    countZone(count) {
      this.selectedZone.count = count;
      this.selected.push(this.selectedZone);
      this.showPlace = false;
    },

    hasParent(t, className) {
      while (t && t !== document) {
        if (t.classList.contains(className)) return true;

        t = t.parentNode;
      }

      return false;
    },

    computedButtonHtml(places) {
      let finalPrice = 0;
      let length = 0;
      places.forEach(p => {
        let count = parseInt(p.count) || 1;

        length += count;
        finalPrice += parseFloat(p.price) * count;
      });

      console.log(length);

      return (
        "<div class='current-zone__buy-tickets_size'>" +
        this.$tc("buyButton.ticket", length, {
          count: length,
          price: finalPrice.toFixed(2)
        }) +
        "</div>"
      );
    },

    sendCheckout(num) {
      this.showCheckout = false;

      // @todo: finish checkout code

      this.selected = [];
    }
  }
};
</script>
