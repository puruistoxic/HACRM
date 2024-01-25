<!-- app-input.vue -->
<template>
  <div class="app-input">
    <label v-if="type === 'dropdown'" class="app-input-label">{{
      label
    }}</label>
    <select
      v-if="type === 'dropdown'"
      class="app-input-dropdown margin-right-8"
      :value="value"
      @change="handleChange"
    >
      <option disabled value="null" v-if="placeholder">
        {{ placeholder }}
      </option>
      <option
        v-for="option in options"
        :value="option.value"
        :key="option.value"
        :disabled="option.disabled"
        :style="{ background: option.color }"
      >
        {{ option.label }}
      </option>
    </select>
    <!-- Display error message if any -->
    <span v-if="errorMessage" class="app-input-error">{{ errorMessage }}</span>
  </div>
</template>

<script>
export default {
  props: {
    type: String,
    label: String,
    value: [String, Number], // Adjust the data type based on your use case
    errorMessage: String,
    options: {
      type: Array,
      default: () => [],
    },
    placeholder: String,
    color: String,
  },
  methods: {
    handleChange(event) {
      // Emit an event to notify the parent about the change
      this.$emit('input', event.target.value)
    },
  },
}
</script>

<style scoped>
.app-input {
  margin-bottom: 10px;
}

.app-input-label {
  font-size: 14px;
  margin-bottom: 4px;
  display: block;
}

.app-input-dropdown {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.app-input-error {
  color: #ff0000;
  font-size: 12px;
  margin-top: 4px;
  display: block;
}
</style>
