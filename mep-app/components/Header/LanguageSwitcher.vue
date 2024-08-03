<script setup>
const { locale, setLocale } = useI18n();

const lang = ref("en");

const langOptions = [
  { label: "EN", value: "en" },
  { label: "DE", value: "de" },
];

onMounted(() => {
  lang.value = locale.value;
});

watch(lang, (newVal) => {
  setLocale(newVal);
});
</script>

<template>
  <div>
    <USelect
      icon="i-heroicons-globe-alt"
      :options="langOptions"
      color="akq-green"
      class="w-full dark:bg-gray-800 dark:text-gray-200"
      :ui="{ rounded: 'rounded' }"
      v-model="lang"
    />
  </div>
</template>
