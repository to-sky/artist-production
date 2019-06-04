<template>
    <svg width="100%" height="100%">
        <g
            v-for="place in event.places.circle"
            @click.prevent="!place.disabled && $emit('clickPlace', $event, place)"
            @mouseover="!place.disabled && $emit('overPlace', $event, place)"
            @mouseout="!place.disabled && $emit('outPlace', place)"
        >
            <circle
                    :cx="place.x"
                    :cy="place.y"
                    :r="place.disabled ? place.width/6 : place.width/2"
                    :fill="placeSelected(place) ? '#13ff00' : place.color"
                    :stroke="placeSelected(place) ? '#444' : ''"
                    stroke-width=".3"
            ></circle>
            <text
                    v-if="!place.disabled"
                    :dx="place.x"
                    :dy="place.y + place.width/6"
                    :fill="placeSelected(place) ? '#13ff00' :'#fff'"
                    text-anchor="middle"
                    :style="{
                      fontSize: parseInt(place.width/2) + 'px',
                    }"
            >
                {{ place.text }}
            </text>
        </g>

        <g
            v-for="place in event.places.fanZone"
            @click.prevent="!place.disabled && $emit('clickFanZone', $event, place)"
            @mouseover="!place.disabled && $emit('overPlace', $event, place)"
            @mouseout="!place.disabled && $emit('outPlace', place)"
        >
            <rect
                    :x="place.x - place.width/2"
                    :y="place.y - place.height/2"
                    :width="place.width"
                    :height="place.height"
                    :fill="placeSelected(place) ? '#13ff00' : place.color"
                    :stroke="placeSelected(place) ? '#444' : ''"
            ></rect>
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

        <!--<g v-show="rows.length && zoom > 2">-->
            <!--<text-->
                    <!--v-for="row in rows"-->
                    <!--:dx="row.x"-->
                    <!--:dy="row.y + getR/2"-->
                    <!--fill="#000"-->
                    <!--text-anchor="middle"-->
                    <!--:style="{-->
                      <!--fontSize: getR + 'px',-->
                    <!--}"-->
            <!--&gt;-->
                <!--{{ row.text }}-->
            <!--</text>-->
        <!--</g>-->
    </svg>
</template>

<script>
  export default {
    props: ['event'],
    name: "HallSvg",
    methods: {
      placeSelected(place) {
        return this.event.cart && this.event.cart.isReserved(place);
      }
    }
  }
</script>

<style>
    circle, rect{cursor: pointer}
    text {pointer-events: none}
</style>