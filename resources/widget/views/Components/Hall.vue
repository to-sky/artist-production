<template>
  <div class="hall">
    <zone-description :event="event"></zone-description>
    <hall-map
        :event="event"
        @updatePlace="processPlace($event)"
        @updateZone="selectZone($event)"
        @hallMapLoaded="$emit('hallMapLoaded')"
    ></hall-map>
    <checkout
        :event="event"
        :time="time"
        @deleteReserveById="freeTicketById($event)"
        @closeCheck="$modal.hide('checkout-zone')"
        @sendCheckout="sendCheckout"
    ></checkout>
    <place-popup
        v-if="selectedZone"
        :max="selectedZone.limit"
        :event="event"
        @selectGroup="setZoneGroup($event)"
        @reserveFanZone="reserveFanZone($event)"
    ></place-popup>
    <price-group-popup
        :priceGroups="event.priceGroups"
        @selectGroup="bindGroupPlace($event)"
    ></price-group-popup>
    <div
      v-if="event.cart.hasItems()"
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
import priceGroupPopup from './PriceGroupPopup.vue';
import moment from 'moment';
import CookieService from '../../services/CookieService/CookieService';

export default {
  props: ["event"],

  components: {
    hallMap,
    zoneDescription,
    checkout,
    placePopup,
    priceGroupPopup
  },

  data() {
    let time = moment();

    return {
      selectedPlace: null,
      selectedZone: null,
      selectedPriceGroup: null,
      time: time.format("DD-MM-YYYY HH:mm:ss"),
      timestamp: +time
    };
  },

  mounted() {
    CookieService.set('initialTimestamp', this.timestamp);
  },

  computed: {
    buttonBuyTickets() {
      return this.computedButtonHtml(this.event.cart.length, this.event.cart.total());
    }
  },

  methods: {
    openCheckoutPopup() {
      this.$modal.show("checkout-zone");
    },

    openPriceGroupPopup() {
      this.$modal.show('price-group');
    },

    processPlace(place) {
      if (this.isReserved(place)) {
        this.flushPlace(place);
      } else {
        if (this.event.hasManyPriceGroups()) {
          this.selectedPlace = place;
          this.openPriceGroupPopup();

          return;
        }

        this.flushPlace(place);
      }
    },

    bindGroupPlace(group) {
      this.selectedPriceGroup = group;
      this.$modal.hide('price-group');

      this.flushPlace(this.selectedPlace);
    },

    setZoneGroup(group) {
      this.selectedPriceGroup = group;
    },

    flushPlace(place) {
      if (this.isReserved(place)) {
        this.event.cart.free(place);
      } else {
        this.event.cart.reserve(place, 1, {
          price_group_id: this.selectedPriceGroup && this.selectedPriceGroup.id || null
        });
      }

      this.selectedPriceGroup = null;
    },

    freeTicketById(ticketId) {
      this.event.cart.freeById(ticketId);
    },

    isReserved(place) {
      return this.event.cart.isReserved(place);
    },

    selectZone(place) {
      this.selectedZone = place;
      this.$modal.show("place-zone");
    },

    reserveFanZone(count) {
      this.event.cart.reserve(this.selectedZone, count, {
        price_group_id: this.selectedPriceGroup && this.selectedPriceGroup.id || null
      });

      this.selectedPriceGroup = null;
      this.$modal.hide('place-zone');
    },

    computedButtonHtml(ticketsNum, total) {
      return (
        "<div class='current-zone__buy-tickets_size'>" +
        this.$tc("buyButton.ticket", ticketsNum, {
          count: ticketsNum,
          price: total
        }) +
        "</div>"
      );
    },

    sendCheckout() {
      this.$modal.hide('checkout-zone');
      window.parent.location = '/payment/checkout';
    }
  }
};
</script>
