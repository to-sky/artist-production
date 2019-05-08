<template>
    <modal
            name="place-zone"
            :adaptive="true"
            :width="'300px'"
            :height="'100px'"
            class="checkout"
    >
        <input
            class="form-control"
            type="number"
            min="1"
            placeholder="Enter number of tickets"
            :max="inputMax"
            v-model="count"
            @input="checkMax"
        >

        <div class="checkout__button" v-html="$t('checkout.confirm')" @click.prevent="$emit('sendFanZone', count)"></div>
    </modal>
</template>

<script>
  export default {
    name: "PlacePopup",

    props: {
      max: {
        type: Number,
        default: 0
      }
    },

    data() {
      return {
        count: 1
      };
    },

    methods: {
      checkMax($e) {
        if (this.max && this.count > this.max) this.count = this.max;
      }
    },

    computed: {
      inputMax() {
        return this.max || '';
      }
    }
  }
</script>

<style scoped>
    .form-control {
        padding: 10px 15px;
        font-size: 18px;
        line-height: 1.1;
    }
</style>