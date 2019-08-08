<template>
  <section class="section-price-list" :class="{
    'open': isOpen
  }">
    <button class="price-list-trigger" @click="switchOpen">
      {{ $t('priceButton.text') }}
    </button>

    <div class="price-list-prices-wrapper">
      <ul class="price-list-prices">
        <li v-for="price in prices">
          <span class="price-color" :style="{
            backgroundColor: price.color
          }"></span>
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
  }
  ul.price-list-prices li:last-child {
    margin-bottom: 0;
  }
  .price-color {
    display: inline-block;
    width: 1em;
    height: 1em;
    margin-bottom: -.15em;
    border-radius: 50%;
  }
</style>
