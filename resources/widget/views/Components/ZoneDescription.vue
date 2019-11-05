<template>
  <div class="zone-description">
    <div class="description">
      <div class="description-container">
        <div
          class="description__circle"
          :style="{ backgroundColor: '#aaa' }"
        ></div>
        <div
          class="description__text"
          v-html="$t('zoneDescription.busy')"
        ></div>
      </div>
      <div class="description-container">
        <div
          class="description__circle"
          :style="{ backgroundColor: '#f00' }"
        ></div>
        <div
          class="description__text"
          v-html="$t('zoneDescription.reserve')"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>

</script>
