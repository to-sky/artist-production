<template>
  <modal
    name="checkout-zone"
    :adaptive="true"
    :width="modalWidth"
    :height="modalHeight"
    class="checkout"
  >
    <div class="checkout__without-button">
      <div class="current-zone__close" @click="$emit('closeCheck')"></div>
      <div class="checkout-container">
        <div class="checkout-title">
          <h3 class="checkout__title" v-html="$t('checkout.checkout')"></h3>
        </div>

        <div class="checkout-block">
          <p class="checkout__text">{{ eventName }}</p>
        </div>
      </div>
      <div class="checkout-container">
        <timer v-if="time"
               :time="time"
               @timeout=""
        >
        </timer>
        <div class="checkout-description">
          <div class="checkout-subblock">
            <p class="checkout-subblock__text">{{ cityName }}</p>
            <p class="checkout-subblock__text">{{ endDate }}</p>
          </div>
          <div class="checkout-subblock">
            <p class="checkout-subblock__text checkout-subblock__text_font">
              {{ hallName }}
            </p>
            <p class="checkout-subblock__text">{{ endTime }}</p>
          </div>
        </div>
      </div>

      <div>
        <div
          class="checkout-container"
          v-for="(place, index) in event.cart.reservationsList"
          v-bind:key="index"
        >
          <div class="checkout-ticket-block">
            <p
              class="checkout-ticket-block__text"
              v-if="place.row && place.num"
              v-html="
                $t('checkout.rowNumber', { row: place.row, num: place.num })
              "
            ></p>
            <p
              class="checkout-ticket-block__text"
              v-if="place.template == 'fanzone'"
              v-html="$t('hall.fanzone')"
            ></p>
            <p class="checkout-ticket-block__text">
              {{ $t('checkout.price', { price: parseFloat(place.discount_price).toFixed(2) }) }}
              <span v-if="place.discount">({{ place.discount }})</span>
            </p>
            <span
              class="checkout-ticket__basket-delete"
              @click="deleteFromBasket(place.ticket_id)"
            ></span>
          </div>
        </div>
      </div>

      <div class="checkout-ticket-total">
        <p
          class="checkout-ticket-block__text"
          v-html="$t('checkout.total')"
        ></p>
        <p class="checkout-ticket-block__text">€{{ totalPrice }}</p>
      </div>
    </div>

    <div v-if="!isTimedOut" class="checkout__button" v-html="$t('checkout.confirm')" @click.prevent="$emit('sendCheckout')"></div>
  </modal>
</template>

<script>
import moment from "moment";
import timer from "./Timer.vue";

export default {
  props: ['event', 'showPopup', 'time'],

  components: {
    timer
  },

  data: () => ({
    modalWidth: "60%",
    modalHeight: "60%",
    isTimedOut: false
  }),

  computed: {
    endDate() {
      return moment(new Date(this.event.raw('event.date.date'))).format(
        "DD MM YYYY"
      );
    },
    endTime() {
      return moment(new Date(this.event.raw('event.date.date'))).format("HH:mm");
    },
    eventName() {
      return this.event.raw('event.name');
    },
    hallName() {
      return this.event.raw('hall.name');
    },
    cityName() {
      return this.event.raw('hall.building.city.name');
    },
    totalPrice() {
      return this.event.cart.total();
    }
  },

  methods: {
    deleteFromBasket(place) {
      this.$emit('deleteReserveById', place);
    },
    getSelectedPlaces() {
      return this.event.getSelectedPlaces();
    }
  },

  created() {
    if (
      /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
        navigator.userAgent
      )
    ) {
      this.modalWidth = "80%";
      this.modalHeight = "80%";
    }
  },

  watch: {
    'event.cart.reservationsList': function (n) {
      if (!n || !n.length) this.$modal.hide('checkout-zone');
    }
  }
};
</script>

<style lang="styl">
@import "../../styles/checkout.styl";
</style>
