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
               :endTime="time">
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
          v-for="(place, index) in selected"
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
              {{ $t('checkout.price', { price: parseInt(place.price) }) }}
              <span v-if="parseInt(place.count)"> X{{ place.count }}</span>
            </p>
            <span
              class="checkout-ticket__basket-delete"
              @click="deleteFromBasket(place)"
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

    <div class="checkout__button" v-html="$t('checkout.confirm')" @click.prevent="$emit('sendCheckout')"></div>
  </modal>
</template>

<script>
import moment from "moment";
import timer from "./Timer.vue";

export default {
  props: ['event', 'selected', 'showPopup', 'time'],
  data: () => ({
    modalWidth: "60%",
    modalHeight: "60%"
  }),
  components: {
    timer
  },
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
      let price = 0;
      if (!this.selected.length) return price;
      for (let i = 0; i < this.selected.length; i++) {
        let count = parseInt(this.selected[i].count) || 1;

        price += parseInt(this.selected[i].price) * count;
      }

      return price;
    }
  },

  methods: {
    deleteFromBasket(place) {
      this.$emit('deleteReserve', place);
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
  }
};
</script>

<style lang="styl">
@import "../../styles/checkout.styl";
</style>
