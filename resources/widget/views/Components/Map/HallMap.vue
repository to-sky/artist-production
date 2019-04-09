<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
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

            <svg width="100%" height="100%" :viewBox="getViewbox">
              <g v-for="zone in zones" :transform="getTranslate(zone.area)">
                <g
                    v-for="place in zone.places"
                    :class="{
                      active: !place.disable
                    }"
                    @click.stop="update($event, place)"
                    @mouseover="showPopup($event, place)"
                    @mouseout="hidePopup"
                >
                  <g v-if="place.template === 'circle'">
                    <circle
                        :cx="place.x"
                        :cy="place.y"
                        :r="place.disable ? getR/3 : getR"
                        :fill="placeSelected(place) ? '#13ff00' : place.color"
                        :stroke="placeSelected(place) ? '#444' : ''"
                        stroke-width=".3"
                    ></circle>
                    <text
                        v-if="!place.disable"
                        :dx="place.x"
                        :dy="place.y + getR/2"
                        :fill="placeSelected(place) ? '#13ff00' :'#fff'"
                        text-anchor="middle"
                        :style="{
                          fontSize: getR + 'px',
                        }"
                    >
                      {{ place.text }}
                    </text>
                  </g>

                  <g v-if="['rectangle', 'fanzone'].includes(place.template)">
                    <rect
                        :x="place.x - place.width/2"
                        :y="place.y - place.width/2"
                        :width="place.width || zone.area.width"
                        :height="place.height || zone.area.height"
                        :fill="place.color"
                    ></rect>
                  </g>
                </g>
              </g>

              <g>
                <rect
                    v-if="scene"
                    :x="scene.x - scene.width/2"
                    :y="scene.y - scene.height/2"
                    :width="scene.width"
                    :height="scene.height"
                    fill="#ccc"
                ></rect>
                <text
                    :dx="scene.x"
                    :dy="parseFloat(scene.y) + scene.height/4"
                    fill="#fff"
                    text-anchor="middle"
                    :style="{
                    fontSize: scene.height/2
                  }"
                >
                  {{ $t('hall.scene') }}
                </text>
              </g>

              <g v-show="rows.length && zoom > 2">
                <text
                    v-for="row in rows"
                    :dx="row.x"
                    :dy="row.y + getR/2"
                    fill="#000"
                    text-anchor="middle"
                    :style="{
                      fontSize: getR + 'px',
                    }"
                >
                  {{ row.text }}
                </text>
              </g>
            </svg>
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
      </div>
    </div>
  </div>
</template>

<script>
  import SvgPanZoom from 'vue-svg-pan-zoom';
  import HallMapPopup from './HallMapPopup.vue';
  import Hammer from 'hammerjs';
  import groupBy from "lodash/groupBy";
  import orderBy from "lodash/orderBy";

  export default {
    components: { SvgPanZoom, HallMapPopup },
    props: ["zones", "token", "event", "selected"],
    data() {
      return {
        viewport: {
          viewbox: [0, 0, 0, 0],
          width: 0
        },
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
      let minx, maxx, miny, maxy, undefined;

      this.zones.forEach(zone => {
        let areax = zone.area.left;
        let areay = zone.area.top;

        zone.places.forEach(place => {
          if (['rectangle', 'fanzone'].includes(place.template)) {
            if (minx === undefined) minx = place.x;
            if (miny === undefined) miny = place.y;
            if (maxx === undefined) maxx = place.x + parseFloat(place.width);
            if (maxy === undefined) maxy = place.y + parseFloat(place.height);

            minx = Math.min(minx, place.x);
            miny = Math.min(miny, place.y);
            maxx = Math.max(maxx, place.x + parseFloat(place.width));
            maxy = Math.max(maxy, place.y + parseFloat(place.height));
          } else {
            if (minx === undefined) minx = areax + place.x;
            if (maxx === undefined) maxx = areax + place.x;
            if (miny === undefined) miny = areay + place.y;
            if (maxy === undefined) maxy = areay + place.y;

            if (minx > areax + place.x) minx = areax + place.x;
            if (maxx < areax + place.x) maxx = areax + place.x;
            if (miny > areay + place.y) miny = areay + place.y;
            if (maxy < areay + place.y) maxy = areay + place.y;
          }
        });
      });

      this.viewport.viewbox = [minx - 4, miny - 4, maxx + 8, maxy + 8];

      let s = this.composeScene();
      this.computeRowNumbers();

      this.viewport.viewbox[1] = parseFloat(s.y) - s.height/2 - 4;
      this.viewport.width = maxx - minx + 12;
    },
    mounted() {
      this.loaded = true;
      window.addEventListener('mouseup', () => {this.grabbing = false});
      this.$router.app.$emit('hallMapLoaded');

      this.$nextTick(() => {
        this.map.center();
      });
    },
    computed: {
      containerHeight() {
        return this.$store.getters.getZoneContainer.height / 1.5 + "px";
      },
      getViewbox() {
        return this.viewport.viewbox.join(' ');
      },
      getR() {
        return this.baseR;
      },
      _scene() {
        return this.$store.getters.getScene;
      }
    },
    methods: {
      getTranslate(area) {
        return "translate(" + area.left + "," + area.top + ")";
      },
      setMap(map) {
        this.map = map;
        this.zoom = map.getZoom();
        this.updateBaseR();
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
        clearTimeout(this.baseRTimer);

        this.baseRTimer = setTimeout(() => {
          let width = this.$refs.mapViewport.offsetWidth ;
          let ratio = this.viewport.width/width;

          let r = 16 * ratio / this.zoom;

          if (r > this.maxR) r = this.maxR;
          if (r < this.minR) r = this.minR;

          this.baseR = r;
        }, 500);
      },
      composeScene() {
        let s = this._scene;

        if (s && s.length) {
          this.scene = s[0];
        } else {
          let vb = this.viewport.viewbox;

          let w = (vb[2] - vb[0])/2;
          let h = 150;

          this.scene = {
            x: w + vb[0],
            y: vb[1] - h,
            width: w,
            height: h
          };
        }

        return this.scene;
      },
      computeRowNumbers() {
        let d = 100;

        this.zones.forEach((zone) => {
          let rows = groupBy(zone.places, 'row');

          for(let r in rows) {
            let row = rows[r];
            let zx = zone.area.left;
            let zy = zone.area.top;

            row = orderBy(row, 'x');
            row = orderBy(row, 'y');

            if (row.length >= 2) {
              let sdx = row[1].x - row[0].x;
              let sdy = row[1].y - row[0].y;

              this.rows.push({
                x: row[0].x + zx - sdx*2/3,
                y: row[0].y + zy - sdy*2/3,
                text: r
              });

              let edx = row[row.length - 1].x - row[row.length - 2].x;
              let edy = row[row.length - 1].y - row[row.length - 2].y;

              this.rows.push({
                x: row[row.length - 1].x + zx + sdx*2/3,
                y: row[row.length - 1].y + zy + sdy*2/3,
                text: r
              });

              let spd = Math.sqrt(Math.pow(sdx, 2) + Math.pow(sdy, 2));
              let epd = Math.sqrt(Math.pow(edx, 2) + Math.pow(edy, 2));

              d = Math.min(d, spd, epd);
            }
          }
        });

        this.placeDelta = d/2 - .5;

        if (this.maxR > this.placeDelta) {
          this.maxR = this.placeDelta;
          this.minR = this.placeDelta*2/3;
        }
      },
      showPopup(e, place) {
        if (place.disable) return;
        clearTimeout(this.popupTimer);

        console.log(e);

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
        let isSelected = false;
        this.selected.forEach((e) => {
          if (e.id === place.id) isSelected = true;
        });

        return isSelected;
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
    position: relative;
    border: solid 1px #000;
    height: 85vh;
    overflow: hidden;
    cursor: grab;
    font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
    font-size: 12px;
  }
  .hall-map.grabbing {
    cursor: grabbing;
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