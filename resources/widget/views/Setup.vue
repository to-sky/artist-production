<template>
    <section v-if="event" class="setup">
        <carousel
                :navigationEnabled="true"
                :perPage="3"
                :perPageCustom="[[320, 3], [768, 4], [1024, 5], [1200, 6], [1600, 7]]"
                :paginationEnabled="false"
                :navigationNextLabel="'›'"
                :navigationPrevLabel="'‹'"
                class="checkboxes-home-list"
        >
            <slide v-for="(price, index) in event.prices" :key="index">
                <checkbox
                    :param="price"
                    :index="index"
                    :checked="isPriceChecked(price)"
                    @input="selectedPrice = $event"
                ></checkbox>
            </slide>
        </carousel>

        <section ref="mapViewport" @mousedown="grabbing = true" class="hall-map" :class="{
          grabbing: grabbing
        }">
            <svg-pan-zoom
                style="width: 100%; height: 100%;"
                :contain="true"
                :fit="true"
                minZoom=".8"
                maxZoom="5"
                @svgpanzoom="registerSVG"
            >

                <hall-svg
                    :event="event"
                    :selected="[]"
                    @clickPlace="applyPrice"
                    @clickFanZone="applyZonePrice"
                ></hall-svg>
            </svg-pan-zoom>

            <section class="controls">
                <a href="#" class="zoom-in" @click.prevent="svgPanZoom.zoomIn()">+</a>
                <a href="#" class="zoom-out" @click.prevent="svgPanZoom.zoomOut()">-</a>
            </section>
        </section>

        <place-popup
            v-show="showPlace"
            @sendFanZone="updateCount($event)"
        ></place-popup>
    </section>
</template>

<script>
  import SvgPanZoom from 'vue-svg-pan-zoom';
  import HallSvg from './Components/Map/HallSvg.vue';
  import EventService from '../services/EventService/EventService';
  import checkbox from "./Components/Checkbox.vue";
  import placePopup from './Components/PlacePopup.vue';

  export default {
    components: {
      SvgPanZoom,
      HallSvg,
      checkbox,
      placePopup
    },
    name: "Setup",
    data() {
      return {
        EventService,
        selectedPrice: null,
        selectedZone: null,
        event: null,
        grabbing: false,
        svgPanZoom: null,
        showPlace: false
      };
    },
    mounted() {
      this.EventService.loadSetup(window.id)
        .then(capsule => {
          this.event = capsule;
        })
        .catch(e => console.log(e))
      ;

      window.addEventListener('mouseup', () => {this.grabbing = false});
      this.$emit('hallMapLoaded');
    },
    methods: {
      isPriceChecked(price) {
        return this.selectedPrice && (price.id === this.selectedPrice.id);
      },
      applyPrice($e, place) {
        if (!this.selectedPrice) return;

        place.color = this.selectedPrice.color;
        this.EventService.updateTicket(window.id, place, this.selectedPrice).then(r => console.log(r));
      },
      applyZonePrice($e, place) {
        if (!this.selectedPrice) return;

        this.selectedZone = place;
        this.$modal.show("place-zone");
        this.showPlace = true;
      },
      updateCount(count) {
        this.selectedZone.color = this.selectedPrice.color;
        this.EventService.updateTicket(window.id, this.selectedZone, this.selectedPrice, count);
        this.showPlace = false;
      },
      registerSVG(svg) {
        this.svgPanZoom = svg;
      }
    }
  }
</script>

<style>

</style>