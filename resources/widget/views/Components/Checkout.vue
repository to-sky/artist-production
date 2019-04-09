<template>
  <modal
    name="checkout-zone"
    v-show="checkoutPopup"
    :adaptive="true"
    :width="modalWidth"
    :height="modalHeight"
    class="checkout"
  >
    <div class="checkout__without-button">
      <div class="current-zone__close" @click="closeCheckoutPopup"></div>
      <div class="checkout-container">
        <div class="checkout-title">
          <h3 class="checkout__title" v-html="$t('checkout.checkout')"></h3>
        </div>

        <div class="checkout-block">
          <img class="checkout__image" :src="eventImg" alt="event image" />
          <p class="checkout__text">{{ eventName }}</p>
        </div>
      </div>
      <div class="checkout-container">
        <timer v-if="endTokenTime"
               v-bind:endTime="endTokenTime">
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
          v-for="(place, index) in selectedItems"
          v-bind:key="index"
        >
          <div class="checkout-ticket-block">
            <p
              class="checkout-ticket-block__text"
              v-if="!!place.row && !!place.num"
              v-html="
                $t('checkout.rowNumber', { row: place.row, num: place.num })
              "
            ></p>
            <p
              class="checkout-ticket-block__text"
              v-if="place.isFanzone"
              v-html="$t('hall.fanzone')"
            ></p>
            <p
              class="checkout-ticket-block__text"
              v-html="
                $t('checkout.price', { price: parseInt(place.price[0].value) })
              "
            ></p>
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

    <div class="checkout__button" v-html="$t('checkout.confirm')"></div>
  </modal>
</template>

<script>
import moment from "moment";
import timer from "./Timer.vue";
import { deletePlaceFromCartCommand } from "../../scripts/api/kartinaApi.js";

export default {
  props: ["endTokenTime", "token"],
  data: () => ({
    modalWidth: "60%",
    modalHeight: "60%"
  }),
  components: {
    timer
  },
  computed: {
    checkoutPopup() {
      return this.$store.getters.getCheckoutPopup;
    },
    endDate() {
      return moment(new Date(this.$store.getters.getEndDate)).format(
        "DD MM YYYY"
      );
    },
    endTime() {
      return moment(new Date(this.$store.getters.getEndTime)).format("HH:mm");
    },
    eventName() {
      return this.$store.getters.getEventName;
    },
    eventImg() {
      return this.$store.getters.getEventImg;
    },
    hallName() {
      return this.$store.getters.getHallName;
    },
    cityName() {
      return this.$store.getters.getCityName;
    },
    selectedItems() {
      return this.$store.getters.getSelectedItems;
    },
    totalPrice() {
      let price = 0;
      if (!this.selectedItems.length) return price;
      for (let i = 0; i < this.selectedItems.length; i++) {
        price += parseInt(this.selectedItems[i].price[0].value);
      }
      return price;
    }
  },

  methods: {
    closeCheckoutPopup() {
      this.$store.commit("setCheckoutPopup", false);
    },

    deleteFromBasket(place) {
      deletePlaceFromCartCommand(this.token, place.orderId).then(response => {
        if (response.errormessage)
          return this.$swal({
            type: "error",
            text: response.errormessage
          });
        place.orderId = null;
        this.$store.commit("removeSelectedItems", place);
        place.isTarget = false;
      });
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
