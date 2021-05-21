<template>
  <section class="section-price-list" :class="{
    'open': isOpen
  }">
    <button class="price-list-trigger" @click="switchOpen">
      {{ $t('priceButton.text') }}
    </button>

    <div class="price-list-prices-wrapper">
      <ul class="price-list-prices">
        <li v-for="price in prices" @click="toggleShowPrice(price.id)">
          <span
              class="price-color"
              :class="{
                checked: isPriceChecked(price.id)
              }"
              :style="{
                backgroundColor: price.color
              }"
          ></span>
          {{ currency }} {{ price.price }}
        </li>
      </ul>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      prices: {
        type: Array,
        default: []
      },
      currency: {
        type: String,
        default: '€'
      },
      selected: {
        type: Array,
        default: []
      }
    },

    data() {
      return {
        isOpen: false,
      };
    },

    methods: {
      switchOpen() {
        this.isOpen = !this.isOpen;
      },
      toggleShowPrice(priceId) {
        let isPos = this.selected.indexOf(priceId);

        if (~isPos) {
          this.$emit('removeShowPrice', priceId);
        } else {
          this.$emit('addShowPrice', priceId);
        }
      },
      isPriceChecked(priceId) {
        return this.selected.includes(priceId);
      }
    }
  };
</script>

<style>
  .section-price-list {
    display: inline-block;
    border: 2px solid rgba(0,0,0,0.2);
    background-clip: padding-box;
    margin: 0;
    padding: 0;
    border-radius: 2px;
    cursor: default;
  }

  .price-list-trigger {
    position: relative;
    margin: 0;
    width: 100%;
    padding: 0 5px 5px;
    background: #fff;
    border: none;
    outline: none;
    cursor: pointer;
    text-align: center;
    color: black;
    font-size: 14px;
    line-height: 30px;
  }
  .price-list-trigger:after {
    position: absolute;
    display: block;
    content: '';
    margin: -3px;
    width: 6px;
    height: 6px;
    bottom: 7px;
    left: 50%;
    border-top: solid 1px #000;
    border-left: solid 1px #000;
    transform: rotate(-135deg);
  }
  .open .price-list-trigger:after {
    transform: rotate(45deg);
    bottom: 2px;
  }

  .price-list-prices-wrapper {
    width: 50px;
    height: 0;
    overflow: hidden;
    background-color: #fff;
  }
  .open .price-list-prices-wrapper {
    width: auto;
    height: auto;
  }

  ul.price-list-prices {
    display: block;
    margin: 0;
    padding: 0;
  }
  ul.price-list-prices li {
    display: block;
    margin: 0;
    padding: 5px;
    cursor: pointer;
  }
  ul.price-list-prices li:last-child {
    margin-bottom: 0;
  }
  .price-color {
    position: relative;
    display: inline-block;
    width: 1em;
    height: 1em;
    margin-bottom: -.15em;
    border-radius: 50%;
    overflow: hidden;
  }
  .price-color.checked:after {
    position: absolute;
    top: 0;
    left: 0;
    display: block;
    content: "\2713";
    width: 100%;
    height: 100%;
    line-height: 100%;
    font-size: 100%;
    color: #fff;
  }
</style>
