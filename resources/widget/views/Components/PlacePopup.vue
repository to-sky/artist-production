<template>
    <modal
            name="place-zone"
            :adaptive="true"
            :width="'300px'"
            :height="'100px'"
            class="checkout"
    >
        <input class="form-control" type="number" placeholder="Enter number of tickets" v-model="count">

        <div class="checkout__button" v-html="$t('checkout.confirm')" @click.prevent="$emit('sendFanZone', count)"></div>
    </modal>
</template>

<script>
  export default {
    name: "PlacePopup",

    data() {
      return {
        count: 1
      };
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