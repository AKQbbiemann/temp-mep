<script setup>
// define emits
const emit = defineEmits(["mouseover", "mouseleave"]);

const props = defineProps({
  items: {
    type: Array,
    required: true,
    validator(items) {
      // Ensure the sum of percentages is 100
      const total = items.reduce((sum, item) => sum + item.percentage, 0);
      return total === 100;
    },
  },
  height: {
    type: String,
    default: "130",
  },
  showPercentage: {
    type: Boolean,
    default: true,
  },
  showTitle: {
    type: Boolean,
    default: true,
  },
  hoverLoad: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <div :class="'flex w-full h-[' + height + '] rounded'">
    <div
      v-for="(item, index) in items"
      :key="index"
      class="overflow-hidden items-center text-center font-bold flex flex-col h-full justify-between text-white cursor-pointer"
      :style="{
        width: item.percentage + '%',
        backgroundColor: item.color,
        height: height + 'px',
        transition: 'height 0.5s',
        'box-shadow':
          hoverLoad === item.label ? '0px 0px 10px ' + item.color : 'none',
      }"
      :class="{
        'rounded-l': index === 0,
        'rounded-r': index === items.length - 1,
      }"
      @mouseover="$emit('mouseover', item.label)"
      @mouseleave="$emit('mouseleave')"
    >
      <!-- <UTooltip
        :text="$t(item.label.toUpperCase()) + '(' + item.percentage + '%)'"
        class="flex flex-col h-full justify-between"
        :popper="{ placement: 'auto' }"
        :ui="{
          background: 'bg-slate-500',
          border: 'border-[' + item.color + ']',
          color: 'text-white',
        }"
      > -->
      <div class="pt-3 px-3">
        <span v-if="showTitle">
          {{ $t(item.label.toUpperCase()) }}
        </span>
      </div>
      <span
        v-if="showPercentage"
        class="bg-slate-500 border-1 ml-auto border-stone-950 rounded-tl px-2 py-1 text-sm"
        >{{ item.percentage }}%</span
      >
      <!-- </UTooltip> -->
    </div>
  </div>
</template>
