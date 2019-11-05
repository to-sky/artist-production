<template>
  <div class="checkbox-home">
    <input
      type="checkbox"
      :id="'checked-' + index"
      :name="'checked-' + index"
      :value="param.id"
      :checked="checked"
    />
    <label
      :style="{ backgroundColor: param.color }"
      @click="$emit('input', param)"
    >
    </label>
    <label
      @click="$emit('input', param)"
      class="checkbox-price"
    >
      <span>€</span> {{ param.price }}
    </label>
  </div>
</template>

<script>

export default {
  props: ["param", "index", "checked"]
};
</script>
