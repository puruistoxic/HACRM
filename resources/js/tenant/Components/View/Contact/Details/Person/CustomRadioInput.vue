<!-- CustomRadioInput.vue -->
<template>
  <div class="radio-button-group">
    <div :data-toggle="toggle" class="btn-group btn-group-toggle">
      <label
        v-for="(option, index) in options"
        :key="index"
        :class="['btn', 'border', { active: option.value === selectedOption }]"
      >
        <input
          type="radio"
          :name="name"
          :id="activityId"
          :value="option.value"
          v-model="selectedOption"
          @change="emitChange(option.activityId)"
        />
        <span>{{ option.label }}</span>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [String, Number],
      default: '',
    },
    options: {
      type: Array,
      default: () => [],
    },
    name: {
      type: String,
      default: 'activityId',
    },
    toggle: {
      type: String,
      default: 'buttons',
    },
    activityId: {
      type: String, // adjust the type as needed
      required: true,
    },
  },
  data() {
    return {
      selectedOption: this.value,
    }
  },
  watch: {
    value(newVal) {
      this.selectedOption = newVal
    },
  },
  methods: {
    emitChange(activityId) {
      this.$emit('input', this.selectedOption, activityId)
    },
  },
}
</script>

<style scoped>
/* Add your custom styling here */
.radio-button-group {
  margin: 15px 0;
  width: 100%;
}

.btn-group-toggle label {
  cursor: pointer;
}

.btn-group-toggle label:not(:first-child) {
  margin-left: -1px;
}

.btn.active {
  background-color: #e0e0e0;
}

.btn {
    width: 20%;
}
</style>
