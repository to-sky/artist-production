<template>
  <ul class="price-groups-list">
    <li>
      <input
          id="pg_id_null"
          type="radio"
          name="group"
          value="0"
          checked="checked"
          @click="selectGroup(null)"
          :class="{
            highlight: highlight
          }"
      >
      <label for="pg_id_null">
        {{ $t('priceGroup.standard') }} (0%)
      </label>
    </li>

    <li v-for="priceGroup in groups">
      <input
          :id="`pg_id_${priceGroup.id}`"
          type="radio"
          name="group"
          :value="priceGroup.id"
          @click="selectGroup(priceGroup)"
          :class="{
            highlight: highlight
          }"
      >
      <label :for="`pg_id_${priceGroup.id}`">
        {{ priceGroup.name }} ({{ priceGroup.discount }}%)
      </label>
    </li>
  </ul>
</template>

<script>
  export default {
    name: "PriceGroupList",

    props: {
      groups: {
        type: Array,
        default: []
      },
      highlight: {
        type: Boolean,
        default: false
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
  ul.price-groups-list {
    display: block;
    margin: 0;
    padding: 0;
  }
  ul.price-groups-list li {
    display: block;
    margin: 0;
    padding: 0;
    text-align: center;
    border-bottom: solid 1px #aaa;
  }
  ul.price-groups-list li:last-child {
    border-bottom: none;
  }
  ul.price-groups-list li input[type="radio"] {
    display: none;
  }
  ul.price-groups-list li label {
    display: block;
    margin: 0;
    padding: 10px;
    cursor: pointer;
  }
  ul.price-groups-list li input.highlight[type="radio"]:checked + label {
    background-color: #ffeb00;
  }
</style>