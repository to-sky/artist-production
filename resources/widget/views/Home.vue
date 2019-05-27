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
      event: null,
      interval: "",
      ts: 1,
      deltaTimer: null
    };
  },

  mounted: function() {
    EventService.load(window.id)
      .then(hallEvent => {
        this.event = hallEvent;

        setTimeout(() => this.setDeltaInterval(), 5000);
      })
      .catch(e => console.log(e))
    ;


  },

  methods: {
    setDeltaInterval() {
      this.deltaTimer = setInterval(() => {
        EventService.loadDelta(window.id)
          .then(tickets => this.event.patch(tickets))
          .catch(e => console.log(e))
        ;
      }, 5000);
    },
    cleatDeltaInterval() {
      clearInterval(this.deltaTimer);
    }
  }
};
</script>

<style lang="styl">
  @import "../styles/checkbox-home.styl";
  @import "../styles/header-home.styl";
  @import "../styles/hall.styl";
</style>
