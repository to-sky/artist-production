<template>
  <div class="timer">
    <span v-html="$t('checkout.timeSession')"></span>
    <span class="number">{{ minutes }}</span>
    <span>:</span>
    <span class="number">{{ seconds }}</span>
    <div v-bind:style="{color: DANGER_COLOR}" class="message">{{ message }}</div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: ["endTime"],
  data: () => ({
    end: "",
    interval: "",
    minutes: "",
    seconds: "",
    message: "",
    DANGER_COLOR: '#f00'
  }),

  mounted() {
    this.end = this.endTime;
    this.spreadOutTime(this.endTime);
    this.interval = setInterval(() => {
      this.spreadOutTime(this.endTime);
    }, 1000);
  },
  methods: {
    spreadOutTime(date) {
      let momentFormat = "DD-MM-YYYY HH:mm:ss";
      let maxDate = moment(date, momentFormat)
        .add(30, "m")
        .format(momentFormat);
      this.minutes = moment(maxDate, momentFormat).diff(moment(), "minutes");
      if (this.minutes < 0)
        this.message = this.$i18n.t("checkout.timeSessionEnd");
      this.seconds =
        moment(maxDate, momentFormat).diff(moment(), "seconds") -
        this.minutes * 60;
    }
  }
};
</script>
