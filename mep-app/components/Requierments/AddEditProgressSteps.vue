<script setup>
const props = defineProps({ isEdit: Boolean, step: Number });
const progress = computed(() => props.step);

watch(progress, (newValue, oldValue) => {
  // console.log("new value in progress", newValue);
});
</script>

<template>
  <div>
    <h1 class="text-akq-green font-medium">
      {{ isEdit ? $t("EDIT_REQUIREMENT") : $t("NEW_REQUIREMENT") }}
    </h1>
    <div class="mt-10">
      <div class="flex items-center">
        <div
          class="w-[50px] h-[50px] bg-akq-green-50 rounded-full flex items-center justify-center"
        >
          <span class="font-bold text-akq-green">1</span>
        </div>
        <div class="ms-3">{{ $t("REQUEST_DETAILS_AND_DESCRIPTION") }}</div>
      </div>
      <div
        class="ms-[22px] w-[6px] h-20"
        :class="progress > 1 ? 'bg-akq-green' : ' bg-gray-400 '"
      ></div>
      <div class="flex items-center">
        <div
          class="w-[50px] h-[50px] rounded-full flex items-center justify-center"
          :class="progress > 1 ? 'bg-akq-green-50' : ' bg-gray-200 '"
        >
          <span class="font-bold" :class="progress > 1 ? 'text-akq-green' : ''"
            >2</span
          >
        </div>
        <div class="ms-3">{{ $t("DATE_DETAILS_AND_PRIORITY") }}</div>
      </div>
      <!--     <div
        class="ms-[22px] w-[6px] h-20"
        :class="progress > 2 ? 'bg-akq-green' : ' bg-gray-400 '"
      ></div> -->
      <!--  <div class="flex items-center">
        <div
          class="w-[50px] h-[50px] rounded-full flex items-center justify-center"
          :class="progress > 2 ? 'bg-akq-green-50' : ' bg-gray-200 '"
        >
          <span class="font-bold" :class="progress > 2 ? 'text-akq-green' : ''"
            >3</span
          >
        </div>
        <div class="ms-3">{{ $t("REQUEST_DETAILS_AND_DESCRIPTION") }}</div>
      </div> -->
    </div>
  </div>
</template>
