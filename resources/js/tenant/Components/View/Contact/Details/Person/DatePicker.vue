<template>
  <div class="app-input">
    <label v-if="type === 'range-picker'" class="app-input-label">{{
      label
    }}</label>
    <input
      v-if="type === 'range-picker'"
      class="app-input-date margin-right-8"
      type="date"
      :placeholder="placeholder"
      :value="value"
      @change="handleChange"
    />
    <!-- Display error message if any -->
    <span v-if="errorMessage" class="app-input-error">{{ errorMessage }}</span>
  </div>
</template>

<script>
export default {
  props: {
    type: String,
    label: String,
    placeholder: String,
    value: [String, Date],
    errorMessage: String,
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
/* Add any necessary styles here */
.app-input {
  margin-bottom: 10px;
  width: 100%;
}

.app-input-label {
  font-size: 14px;
  margin-bottom: 4px;
  display: block;
}

.app-input-date {
  width: 100%;
  padding: 8px;
  border: 1px solid #ebedee;
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
