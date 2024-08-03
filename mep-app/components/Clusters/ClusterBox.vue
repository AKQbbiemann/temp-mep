<script setup>
const props = defineProps({
  data: Object,
});
</script>

<template>
  <div class="border-akq-green border-l-4 p-2 px-4">
    <span class="font-medium">{{ props.data.label }} </span>
    <span> {{ props.data.data }}</span>
  </div>
</template>
