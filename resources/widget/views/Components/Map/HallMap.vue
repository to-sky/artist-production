<template>
  <section ref="mapViewport" @mousedown="grabbing = true" class="hall-map" :class="{
    grabbing: grabbing
  }">
    <svg-pan-zoom
        v-if="loaded"
        style="width: 100%; height: 100%;"
        :contain="true"
        :onZoom="setZoom"
        :customEventsHandler="getMobileEventsListener()"
        minZoom=".8"
        maxZoom="5"
        @svgpanzoom="setMap"
    >

      <hall-svg
          :event="event"
          @clickPlace="update"
          @clickFanZone="updateZone"
          @overPlace="showPopup"
          @outPlace="hidePopup"
      ></hall-svg>
    </svg-pan-zoom>

    <hall-map-popup
        ref="placePopup"
        :place="popupPlace"
        :top="popupTop"
        :left="popupLeft"
        :visible="popupVisible"
        @popupDisplayed="makePan(calculatePan($event))"
    />

    <section class="controls">
      <a href="#" class="zoom-in" @click.prevent="map.zoomIn()">+</a>
      <a href="#" class="zoom-out" @click.prevent="map.zoomOut()">-</a>
    </section>

    <section ref="animationContainer" class="animation">
      <div v-for="point in animated" class="dot"
           :style="{
                   width:  point.radius + 'px',
                   height: point.radius + 'px',
                   backgroundColor: point.color,
                   top: point.top + 'px',
                   left: point.left + 'px'
                 }"
      ></div>
    </section>
  </section>
</template>

<script>
  import SvgPanZoom from 'vue-svg-pan-zoom';
  import HallMapPopup from './HallMapPopup.vue';
  import HallSvg from './HallSvg.vue';
  import Hammer from 'hammerjs';

  export default {
    components: { SvgPanZoom, HallMapPopup, HallSvg },
    props: ["event"],
    data() {
      return {
        loaded: false,
        map: null,
        baseR: 4,
        zoom: 1,
        minR: 8,
        maxR: 12,
        baseRTimer: null,
        scene: null,
        grabbing: false,
        popupTimer: null,
        waitingPopup: false,
        popupVisible: false,
        popupTop: 0,
        popupLeft: 0,
        popupPlace: null,
        placeDelta: 0,
        rows: [],
        animated: [],
        hasAnimations: false
      };
    },
    beforeMount() {

    },
    mounted() {
      this.loaded = true;
      window.addEventListener('mouseup', () => {this.grabbing = false});
      this.$emit('hallMapLoaded');

      this.$nextTick(() => {
        this.map.center();
      });
    },
    computed: {
      containerHeight() {
        return "500px";
      },
      getR() {
        return this.baseR;
      }
    },
    methods: {
      setMap(map) {
        this.map = map;
        this.zoom = map.getZoom();
        this.updateBaseR();

        this.$nextTick(() => {
          this.map.fit().zoomOut();
        });
      },
      setZoom(zoom) {
        this.zoom = zoom;
        this.updateBaseR();
      },
      update(e, place) {
        if (place.disable) return;

        this.animateCheckout(e, place);
        this.$emit('updatePlace', place);
      },
      updateZone(e, place) {
        if (place.disable) return;

        this.animateCheckout(e, place);
        this.$emit('updateZone', place);
      },
      animateCheckout(e, place) {
        if (this.placeSelected(place)) return;

        let leftDest = this.$refs.animationContainer.clientWidth / 2;
        let topDest = this.$refs.animationContainer.clientHeight - 50;
        let self = this;

        let dot = {
          hash: + new Date(),
          top: e.offsetY,
          left: e.offsetX,
          radius: this.getR * this.zoom,
          color: place.color,
          leftDest: leftDest,
          topDest: topDest,
          dx: (leftDest - e.offsetX) / 20,
          dy: (topDest - e.offsetY) / 20
        };

        this.animated.push(dot);

        if (!this.hasAnimations) {
          requestAnimationFrame(anim);
          this.hasAnimations = true;
        }

        function anim() {
          self.animated.forEach(dot => {
            dot.top += dot.dy;
            if(Math.abs(dot.top - dot.topDest) < Math.abs(dot.dy)) {
              dot.top = dot.topDest;
              dot.dy = 0;
            }

            dot.left += dot.dx;
            if(Math.abs(dot.left - dot.leftDest) < Math.abs(dot.dx)) {
              dot.left = dot.leftDest;
              dot.dx = 0;
            }

            if (dot.left == dot.leftDest && dot.top == dot.topDest) {
              self.animated.splice(self.animated.indexOf(dot), 1);
            }
          });

          if (self.animated.length) {
            requestAnimationFrame(anim);
          } else {
            self.hasAnimations = false;
          }
        }
      },
      updateBaseR() {
        // clearTimeout(this.baseRTimer);
        //
        // this.baseRTimer = setTimeout(() => {
        //   let width = this.$refs.mapViewport.offsetWidth ;
        //   let ratio = this.viewport.width/width;
        //
        //   let r = 16 * ratio / this.zoom;
        //
        //   if (r > this.maxR) r = this.maxR;
        //   if (r < this.minR) r = this.minR;
        //
        //   this.baseR = r;
        // }, 500);
      },
      showPopup(e, place) {
        if (place.disable) return;
        clearTimeout(this.popupTimer);

        this.popupTimer = setTimeout(() => {
          this.popupTop = e.offsetY;
          this.popupLeft = e.offsetX;
          this.popupVisible = true;
          this.popupPlace = place;

          this.waitingPopup = false;
        }, 200);
        this.waitingPopup = true;
      },
      hidePopup() {
        if (this.waitingPopup) {
          clearTimeout(this.popupTimer);
          this.waitingPopup = false;
        } else {
          setTimeout(() => {
            this.popupVisible = false;
          }, 200);
        }
      },
      calculatePan(ref) {
        let map = this.$refs.mapViewport;
        let dim = {
          x: 0,
          y: 0
        };

        if (ref.left - ref.$el.clientWidth/2 < 0) dim.x += ref.$el.clientWidth/2 - ref.left + 10;
        if (ref.left + ref.$el.clientWidth/2 > map.clientWidth) dim.x = map.clientWidth - ref.left - ref.$el.clientWidth/2 - 10;
        if (ref.top - ref.$el.clientHeight < 0) dim.y -= ref.top - ref.$el.clientHeight - 10;

        return dim;
      },
      makePan(dim) {
        if (!dim.x && !dim.y) return;
        let t = 200;
        let map = this.$refs.mapViewport;
        let curr = this.map.getPan();
        let fin = {
          x: curr.x + dim.x,
          y: curr.y + dim.y
        };
        let self = this;
        let s;

        map.style.pointerEvents = 'none';
        function step (ts) {
          if (!s) s = ts;
          let progress = ts - s;

          if (progress < t) {
            let span = {
              x: curr.x + dim.x*progress/t,
              y: curr.y + dim.y*progress/t
            };
            self.map.pan(span);
            self.popupLeft = dim.x*progress/t;
            self.popupTop = dim.y*progress/t;

            window.requestAnimationFrame(step);
          } else {
            self.map.pan(fin);
            map.style.pointerEvents = '';
          }
        }
        window.requestAnimationFrame(step);
      },
      placeSelected(place) {
        return this.event.cart.isReserved(place);
      },
      getMobileEventsListener() {
        return {
          haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel'],
          init(options) {
            let instance = options.instance;
            let initialScale = 1;
            let pannedX = 0;
            let pannedY = 0;
            // Init Hammer
            // Listen only for pointer and touch events
            this.hammer = Hammer(options.svgElement, {
              inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
            });
            // Enable pinch
            this.hammer.get('pinch').set({enable: true});
            // Handle double tap
            this.hammer.on('doubletap', function(ev){
              instance.zoomIn();
            });
            // Handle pan
            this.hammer.on('panstart panmove', function(ev){
              // On pan start reset panned variables
              if (ev.type === 'panstart') {
                pannedX = 0;
                pannedY = 0;
              }
              // Pan only the difference
              instance.panBy({x: ev.deltaX - pannedX, y: ev.deltaY - pannedY})
              pannedX = ev.deltaX;
              pannedY = ev.deltaY;
            });
            // Handle pinch
            this.hammer.on('pinchstart pinchmove', function(ev){
              // On pinch start remember initial zoom
              if (ev.type === 'pinchstart') {
                initialScale = instance.getZoom();
                instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y});
              }
              instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y});
            });
            // Prevent moving the page on some devices when panning over SVG
            options.svgElement.addEventListener('touchmove', function(e){ e.preventDefault(); });
          },
          destroy(){
            this.hammer.destroy();
          }
        }
      }
    }
  }
</script>

<style>
  @import "~vue-svg-pan-zoom/dist/vue-svg-pan-zoom.css";
</style>

<style>
  .hall-place {
    cursor: pointer;
  }

  .hall-map {
    position: absolute;
    top: 80px;
    left: 0;
    right: 0;
    bottom: 0;
    border-top: solid 1px #aaa;
    overflow: hidden;
    cursor: grab;
    font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
    font-size: 12px;
  }
  .setup .hall-map{
    top: 50px;
    border: solid 1px #aaa;
  }
  .hall-map.grabbing {
    cursor: grabbing !important;
  }
  .hall-map.grabbing * {
    cursor: grabbing !important;
  }

  g.active {
    cursor: pointer;
  }

  .controls {
    position: absolute;
    top: 10px;
    left: 10px;
    border: 2px solid rgba(0,0,0,0.2);
    background-clip: padding-box;
    margin: 0;
    padding: 0;
    border-radius: 2px;
  }

  .controls .zoom-in, .controls .zoom-out {
    background-color: #fff;
    border-bottom: 1px solid #ccc;
    display: block;
    text-align: center;
    text-decoration: none;
    color: black;
    font-size: 22px;
    width: 30px;
    height: 30px;
    line-height: 30px;
    margin: 0;
  }

  .controls *:first-child {
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
  }

  .controls *:last-child {
    border-bottom-left-radius: 2px;
    border-bottom-right-radius: 2px;
  }

  .animation {
    pointer-events: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .animation .dot {
    position: absolute;
    display: block;
    border-radius: 50%;
    transform: translate(-50%, -50%);
  }
</style>