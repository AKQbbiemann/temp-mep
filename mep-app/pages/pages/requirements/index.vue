<script setup>
const router = useRouter();

const selected = ref(false);

watchEffect(() => {
  router.currentRoute.value.query.view === "calendar"
    ? (selected.value = true)
    : (selected.value = false);
});

watchEffect(() => {
  selected.value
    ? router.push({ query: { view: "calendar" } })
    : router.push({ query: { view: "table" } });
});

onMounted(() => {
  if (!router.currentRoute.value.query.view) {
    router.push({ query: { view: "table" } });
  }
});

onUnmounted(() => {
  router.push({ query: {} });
});

const toggle = () => {
  selected.value = !selected.value;
};
</script>

<template>
  <div class="m-4 grow sub-container min-h-screen">
    <div class="pt-4 flex justify-between">
      <UButton
        type="button"
        color="akq-green"
        icon="i-heroicons-plus-circle"
        class="justify-center text-base rounded mb-3"
        @click="navigateTo(localeRoute('/requirements/new').fullPath)"
      >
        {{ $t("ADD_REQUIERMENT") }}
      </UButton>

      <div class="flex items-center px-5">
        <span
          class="text-xs font-medium align-middle pe-3 cursor-pointer"
          :class="{ 'text-gray-300': selected }"
          @click="toggle()"
          >{{ $t("TABLE") }}</span
        >
        <UToggle
          v-model="selected"
          color="akq-green"
          :ui="{
            inactive: 'bg-akq-green-500 dark:bg-gray-700',
          }"
        />
        <span
          class="text-xs font-medium align-middle ps-3 cursor-pointer"
          :class="{ 'text-gray-300': !selected }"
          @click="toggle()"
          >{{ $t("CALENDER") }}</span
        >
      </div>
    </div>
    <RequiermentCalenderView v-if="selected" />
    <RequirementTableView v-else />
  </div>
</template>
