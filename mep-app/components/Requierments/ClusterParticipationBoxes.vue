<script setup>
const props = defineProps({
  data: Object,
});

const boxes = computed(() => {
  if (props.data) return Object.entries(props.data);
});
</script>
<template>
  <div class="grid grid-cols-2 gap-4">
    <div v-for="box in boxes" :key="box[0]">
      <div
        class="sub-container-gray flex flex-col justify-center small-padding"
      >
        <div
          class="text-akq-green font-medium text-xl py-2 text-center truncate"
        >
          {{ box[0] }}
        </div>
        <span class="text-center font-semibold text-2xl"
          >{{ box[1] }} <span class="text-xs italic">PT</span></span
        >
      </div>
    </div>
  </div>
</template>
