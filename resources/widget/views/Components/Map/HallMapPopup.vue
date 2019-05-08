<template>
  <section
      class="popup"
      :class="{
        open: visible
      }"
      :style="{
        top: top + 'px',
        left: left + 'px'
      }"
  >
    <div class="popup-content-wrapper">
      <div v-if="place" class="popup-content">
        <div v-if="place.row">
          {{ $t('place.row') }} {{ place.row }}
        </div>
        <div v-if="place.num">
          {{ $t('place.num') }} {{ place.num }}
        </div>
        <div>
          {{ $t('place.price') }} {{ place.price }}
        </div>
      </div>
    </div>
    <div class="popup-tip"></div>
  </section>
</template>

<script>
  export default {
    props: ['place', 'visible', 'top', 'left'],
    watch: {
      visible(nv, ov) {
        if (nv) {
          this.$nextTick(() => {
            this.$emit('popupDisplayed', this);
          })
        }
      }
    }
  }
</script>

<style>
  .popup {
    pointer-events: none;
    display: block;
    position: absolute;
    transform: translate(-50%, -100%);
    text-align: left;
    padding-bottom: 20px;
    cursor: pointer;
    opacity: 0;
  }
  .popup.open {
    opacity: 1;
    transition: opacity .2s linear;
  }

  .popup-content-wrapper {
    overflow: hidden;
    line-height: 1.4;
    color: #333;
    background: #fff;
    border-radius: 12px;
    -webkit-box-shadow: 0 3px 14px rgba(0,0,0,.4);
    box-shadow: 0 3px 14px rgba(0,0,0,.4);
    white-space: nowrap;
  }

  .popup-content {
    margin: 13px 19px;
    min-width: 50px;
    min-height: 20px;
  }

  .popup-tip {
    width: 40px;
    height: 20px;
    position: absolute;
    left: 50%;
    margin-left: -20px;
    overflow: hidden;
    pointer-events: none;
  }
  .popup-tip:before {
    display: block;
    content: '';
    width: 17px;
    height: 17px;
    padding: 1px;
    margin: -10px auto 0;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    background: #fff;
    color: #333;
    -webkit-box-shadow: 0 3px 14px rgba(0,0,0,.4);
    box-shadow: 0 3px 14px rgba(0,0,0,.4);
  }
</style>