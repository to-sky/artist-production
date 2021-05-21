<template>
  <div class="timer">
    <span v-html="$t('checkout.timeSession')"></span>
    <span class="time">
      <span class="number">{{ minutes }}</span>
      <span>:</span>
      <span class="number">{{ seconds }}</span>
    </span>
    <div v-bind:style="{color: DANGER_COLOR}" class="message">{{ message }}</div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: ["time"],

  data: () => ({
    endTime: null,
    currentTime: null,
    interval: null,
    timeIsOut: false,
    DANGER_COLOR: '#f00',
    format: 'DD-MM-YYYY HH:mm:ss'
  }),

  created() {
    this.endTime = moment(this.time, this.format).add(30, "minute");
    this.currentTime = moment();
  },

  mounted() {
    this.interval = setInterval(() => {
      this.progress();
    }, 500);
  },
  methods: {
    progress() {
      this.currentTime = moment();

      if (this.diff <= 0) {
        this.timeIsOut = true;
        clearInterval(this.interval);
        this.$emit('timeout');
      }
    }
  },
  computed: {
    minutes() {
      return this.endTime.diff(this.currentTime, 'minutes');
    },
    diff() {
      return this.endTime.diff(this.currentTime, 'seconds');
    },
    seconds() {
      let s = this.diff - this.minutes * 60;
      if (s < 10) s = '0' + s.toString();
      return s;
    },
    message() {
      return this.timeIsOut ? this.$i18n.t("checkout.timeSessionEnd") : '';
    }
  }
};
</script>

<style>
  .timer .time {
    font-size: 0;
  }
  .timer .time span {
    font-size: 20px;
  }
</style>
