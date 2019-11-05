<template>
    <svg width="100%" height="100%">
        <g
            v-for="place in event.places.circle"
            @click.prevent="!placeDisabled(place) && $emit('clickPlace', $event, place)"
            @mouseover="!placeDisabled(place) && $emit('overPlace', $event, place)"
            @mouseout="!placeDisabled(place) && $emit('outPlace', place)"
        >
            <circle
                    :cx="place.x"
                    :cy="place.y"
                    :r="placeDisabled(place) ? place.width/6 : place.width/2"
                    :fill="placeSelected(place) ? '#13ff00' : place.color"
                    :stroke="placeSelected(place) ? '#444' : ''"
                    stroke-width=".3"
            ></circle>
            <text
                    v-if="!placeDisabled(place)"
                    :dx="place.x"
                    :dy="place.y + place.width/6"
                    :fill="placeSelected(place) ? '#13ff00' :'#fff'"
                    text-anchor="middle"
                    :style="{
                      fontSize: parseInt(place.width/2) + 'px'
                    }"
            >
                {{ place.text }}
            </text>
        </g>

        <g
            v-for="place in event.places.fanZone"
            @click.prevent="!placeDisabled(place) && $emit('clickFanZone', $event, place)"
            @mouseover="!placeDisabled(place) && $emit('overPlace', $event, place)"
            @mouseout="!placeDisabled(place) && $emit('outPlace', place)"
        >
            <rect
                    :x="place.x - place.width/2"
                    :y="place.y - place.height/2"
                    :width="place.width"
                    :height="place.height"
                    :fill="placeSelected(place) ? '#13ff00' : place.color"
                    :stroke="placeSelected(place) ? '#444' : ''"
            ></rect>
            <text
                :dx="place.x"
                :dy="parseFloat(place.y) + place.fontSize/2 - 8"
                :fill="placeSelected(place) ? '#13ff00' :'#fff'"
                text-anchor="middle"
                :textLength="place.width"
                :style="{
                  fontSize: place.fontSize + 'px'
                }"
            >
                {{ place.text }}
            </text>
        </g>

        <g v-for="scene in event.places.scene">
            <rect
                    :x="scene.x - scene.width/2"
                    :y="scene.y - scene.height/2"
                    :width="scene.width"
                    :height="scene.height"
                    fill="#ccc"
            ></rect>
            <text
                :dx="scene.x"
                :dy="parseFloat(scene.y) + scene.fontSize/2 - 8"
                fill="#fff"
                text-anchor="middle"
                :style="{
                    fontSize: scene.fontSize + 'px'
                }"
            >
                {{ $t('hall.scene') }}
            </text>
        </g>

        <g v-for="label in event.labels" :style="{
          transform: 'translate('+label.x+'px, '+(label.y + event.fontSize/2)+'px)'
        }">
            <text
                fill="#000"
                text-anchor="middle"
                :style="{
                  transform: 'rotate(' + label.rotation + 'deg)',
                  fontSize: event.fontSize
                }"
            >
                {{ label.text }}
            </text>
        </g>
    </svg>
</template>

<script>
  export default {
    props: ['event'],
    name: "HallSvg",
    methods: {
      placeSelected(place) {
        return this.event.cart && this.event.cart.isReserved(place);
      },
      placeDisabled(p) {
        return p.disabled || !this.event.shouldShowPrice(p.price_id);
      }
    }
  }
</script>

<style>
    circle, rect{cursor: pointer}
    text {pointer-events: none}
</style>