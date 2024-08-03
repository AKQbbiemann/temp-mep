<script setup>
const props = defineProps({
  lable: String,
  value: String | Date,
});
</script>

<template>
  <div class="mb-4">
    <label class="block text-gray-700 font-bold uppercase">
      {{ $t(props.lable) }}
    </label>
    <span class="">
      {{ props.value === null ? "---" : props.value }}
    </span>
  </div>
</template>
