<template>
  <div id="app">
    <router-view />
    <div id="preloader" class="preloader" :class="{
        'preloader-disable': preloaderHidden
    }">
      <h4 class="preloader__text">Your concert is waiting for you ...</h4>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        zoom: 1,
        preloaderHidden: false
      };
    },
    beforeMount() {
      this.$router.app.$on('hallMapLoaded', () => {
        this.hidePreloader();
      })
    },
    methods: {
      hidePreloader() {
        this.preloaderHidden = true;
      }
    }
  };
</script>

<style>
@import "~normalize.css";
</style>

<style lang="styl">
@import "styles/layout.styl";
</style>
