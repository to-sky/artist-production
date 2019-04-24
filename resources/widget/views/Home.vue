<template>
  <div v-if="event" class="home">
    <header-home :event="event"></header-home>
    <hall
        :event="event"
        @hallMapLoaded="$emit('hallMapLoaded')"
    ></hall>
  </div>
</template>

<script>
import hall from "./Components/Hall.vue";
import headerHome from "./Components/Header.vue";
import EventService from '../services/EventService/EventService';

export default {
  name: "home",

  components: {
    hall,
    headerHome
  },

  data() {
    return {
      EventService,
      event: null,
      interval: "",
      ts: 1
    };
  },

  mounted: function() {
    this.EventService.load(window.id)
      .then(hallEvent => this.event = hallEvent)
      .catch(e => console.log)
    ;
  }
};
</script>

<style lang="styl">
  @import "../styles/checkbox-home.styl";
  @import "../styles/header-home.styl";
  @import "../styles/hall.styl";
</style>
