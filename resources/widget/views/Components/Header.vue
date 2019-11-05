<template>
  <div class="header-event">
    <img class="header-event__image" :src="event.raw('event.event_image.file_url')" :alt="event.raw('event.name')">

    <div class="header-event__description-container">
      <div class="header-event__title-container">
        <h2 class="header-event__title">{{ event.raw('event.name') }}</h2>
      </div>
      <div class="header-event__description">
        <a
            :href="'https://maps.google.com/?q=' + event.raw('hall.building_address')"
            class="header-event__link-google"
            target="_blank"
        >
          <div class="header-event__marker-icon"></div>
          <p class="header-event__text">{{ event.raw('hall.name') }}, {{ event.raw('hall.building_name') }}</p>
        </a>
      </div>
      <div class="header-event__date" v-if="!isMobile">
        <div class="calendar-container">
          <div class="date-container">
            <div class="calendar__icon"></div>
            <span class="calendar__end-day">{{ endDay }}</span>
            <div class="calendar__end-month">{{ endMonth }}</div>
            <div class="calendar-year-container">
              <span class="calendar__end-day-name">{{ endDayName }}</span>
              <div class="calendar__end-year">{{ endYear }}</div>
            </div>
          </div>
          <div class="time-container">
            <div class="calendar__icon-time"></div>
            <span class="calendar__end-hour">{{ endHour }}</span>
          </div>
        </div>
      </div>

      <div class="select-language-container" v-if="!isMobile">
        <div
            :class="'select-language' + ' select-language-change__' + getLangName"
            @click="showLanguages = !showLanguages"
        ></div>
        <div class="select-language-change" v-show="showLanguages">
          <div
              class="select-language__language select-language-change__en"
              @click="changeLanguage('en')"
          ></div>
          <div
              class="select-language__language select-language-change__de"
              @click="changeLanguage('de')"
          ></div>
          <div
              class="select-language__language select-language-change__ru"
              @click="changeLanguage('ru')"
          ></div>
        </div>
      </div>

      <div class="header-event__date" v-if="isMobile">
        <div class="calendar-container">
          <div class="date-container">
            <div class="calendar__icon"></div>
            <span class="calendar__end-day">{{ endDay }}</span>
            <div class="calendar__end-month">{{ endMonthShort }}</div>
            <div class="calendar-year-container">
              <span class="calendar__end-day-name">{{ endDayName }}</span>
              <div class="calendar__end-year">{{ endYear }}</div>
            </div>
          </div>
          <div class="time-container">
            <div class="calendar__icon-time"></div>
            <span class="calendar__end-hour">{{ endHour }}</span>
            <div class="select-language-container">
              <div
                  :class="
                'select-language' + ' select-language-change__' + getLangName
              "
                  @click="showLanguages = !showLanguages"
              ></div>
              <div class="select-language-change" v-show="showLanguages">
                <div
                    class="select-language__language select-language-change__en"
                    @click="changeLanguage('en')"
                ></div>
                <div
                    class="select-language__language select-language-change__de"
                    @click="changeLanguage('de')"
                ></div>
                <div
                    class="select-language__language select-language-change__ru"
                    @click="changeLanguage('ru')"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: ['event'],

  data: () => ({
    showLanguages: false,
    locales: ['de', 'en', 'ru']
  }),

  methods: {
    changeLanguage(locale) {
      this.$root.currentLocale = locale;
      this.showLanguages = false;
    }
  },

  computed: {
    isMobile() {
      if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
          navigator.userAgent
        ) &&
        window.innerWidth < 767
      )
        return true;
    },
    getHallAddress() {
      return encodeURI(this.$store.getters.getBuildingAddress);
    },
    getLangName() {
      return this.$i18n.locale;
    },
    endDate() {
      return this.event.raw('event.date.date');
    },
    outputEndDate() {
      return moment(new Date(this.endDate)).format("DD MM YYYY");
    },
    endDay() {
      return moment(new Date(this.endDate)).format("DD");
    },
    endDayName() {
      return moment(new Date(this.endDate))
        .locale(this.getLangName)
        .format("ddd");
    },
    endMonth() {
      return moment(new Date(this.endDate))
        .locale(this.getLangName)
        .format("MMMM");
    },
    endMonthShort() {
      return moment(new Date(this.endDate))
        .locale(this.getLangName)
        .format("MMMM");
    },
    endYear() {
      return moment(new Date(this.endDate)).format("YYYY");
    },
    endTime() {
      return Date.parse(this.event.raw('event.date.date'));
    },
    endHour() {
      return moment(new Date(this.endTime)).format("HH:mm");
    },
    eventName() {
      // change size of event title on mobile devices
      if (window.screen.availWidth < 480) {
        let maxSizeOfTitle = 18;
        let sizeOfTitle = this.$store.getters.getEventName.length;
        if (sizeOfTitle > maxSizeOfTitle) {
          return (
            this.$store.getters.getEventName.slice(0, maxSizeOfTitle) + "..."
          );
        }
      }
      return this.$store.getters.getEventName;
    },
    eventImg() {
      return this.$store.getters.getEventImg;
    },
    hallName() {
      return this.$store.getters.getHallName;
    },
    cityName() {
      // change size of event title on mobile devices
      if (window.screen.availWidth < 480) {
        let maxSizeOfTitle = 18;
        let sizeOfTitle = this.$store.getters.getCityName.length;
        if (sizeOfTitle > maxSizeOfTitle) {
          return (
            this.$store.getters.getCityName.slice(0, maxSizeOfTitle) + "..."
          );
        }
      }
      return this.$store.getters.getCityName;
    }
  }
};
</script>
