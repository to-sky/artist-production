<template>
  <div id="app" ref="app">
    <home v-if="modeView" @hallMapLoaded="hidePreloader"></home>
    <setup v-if="modeSetup" @hallMapLoaded="hidePreloader"></setup>

    <div id="preloader" class="preloader" :class="{
        'preloader-disable': preloaderHidden
    }">
      <h4 class="preloader__text">Your concert is waiting for you ...</h4>
    </div>
  </div>
</template>

<script>
  import Home from './views/Home.vue';
  import Setup from './views/Setup.vue';

  export default {
    components: {
      Home,
      Setup
    },

    data() {
      return {
        preloaderHidden: false,
        id: id,
        mode: mode
      };
    },

    methods: {
      hidePreloader() {
        this.preloaderHidden = true;
      }
    },

    computed: {
      modeView() {
        return this.mode === 'view';
      },
      modeSetup() {
        return this.mode === 'setup';
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
