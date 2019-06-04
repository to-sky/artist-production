<template>
    <modal
            name="place-zone"
            :adaptive="true"
            :width="'300px'"
            :height="'auto'"
            class="checkout"
    >
        <div v-if="hasGroups">
          <h3 class="price-header">Select ticket type</h3>

          <price-group-list
              :groups="event.priceGroups"
              :highlight="true"
              @selectGroup="selectGroup"
          ></price-group-list>
        </div>

        <h3 class="price-header">Enter number of tickets</h3>

        <input
            class="form-control"
            type="number"
            min="1"
            :max="inputMax"
            v-model="count"
            @input="checkMax"
        >

        <button :disabled="!canReserve" class="checkout__button" v-html="$t('checkout.confirm')" @click.prevent="$emit('updateTicketsCount', count)"></button>
    </modal>
</template>

<script>
  import PriceGroupList from './PriceGroupList.vue';

  export default {
    name: "PlacePopup",

    components: {
      PriceGroupList
    },

    props: {
      max: {
        type: Number,
        default: 10000
      },
      event: {
        type: Object,
        default: null
      },
      hasGroups: {
        type: Boolean,
        default: false
      }
    },

    data() {
      return {
        count: 1,
        priceGroup: null
      };
    },

    methods: {
      checkMax($e) {
        if (this.max && this.count > this.max) this.count = this.max;
      },
      selectGroup(g) {
        this.$emit('selectGroup', g);
      }
    },

    computed: {
      inputMax() {
        return this.max || '';
      },
      canReserve() {
        return this.count >= 1 && this.count <= this.max;
      }
    }
  }
</script>

<style>
  h3.price-header {
    margin: 10px auto;
    text-align: center;
  }

  .form-control {
    padding: 10px 15px;
    margin-bottom: 10px;
    font-size: 18px;
    line-height: 1.1;
  }

  .checkout .price-groups-list {
    margin-bottom: 10px;
  }
</style>