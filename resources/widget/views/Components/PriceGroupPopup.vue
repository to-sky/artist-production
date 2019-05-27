<template>
    <modal
            name="price-group"
            :adaptive="true"
            :width="'300px'"
            :height="'auto'"
            class="checkout"
    >
        <h3 class="price-header">Select ticket type</h3>

        <price-group-list
            :groups="priceGroups"
            @selectGroup="selectGroup"
        ></price-group-list>
    </modal>
</template>

<script>
  import PriceGroupList from './PriceGroupList.vue';

  export default {
    name: "PriceGroupPopup",

    components: {
      PriceGroupList
    },

    props: {
      priceGroups: {
        type: Array,
        default: []
      }
    },

    methods: {
      selectGroup(g) {
        this.$emit('selectGroup', g);
      }
    }
  }
</script>

<style>
    h3.price-header {
        margin: 10px auto;
    }
</style>