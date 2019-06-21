<template>
  <div v-if="event" class="home">
    <header-home v-if="!hideHeader" :event="event"></header-home>
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
import UrlService from '../services/UrlService/UrlService';

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
      deltaTimer: null,
      hideHeader: UrlService.get('no_header', false, d => this.hideHeader = d)
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

<style lang="scss">
  .home {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    .hall {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }


    .header-event {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;

      & + .hall {
        top: 100px;
      }
    }
  }
</style>

<style lang="styl">
  @import "../styles/checkbox-home.styl";
  @import "../styles/header-home.styl";
  @import "../styles/hall.styl";
</style>
