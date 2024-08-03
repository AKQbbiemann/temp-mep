<script setup>
const props = defineProps({
  open: Boolean,
});

const emit = defineEmits(["close", "open"]);

const isOpen = ref(false);

watch(
  () => props.open,
  (first, secound) => {
    isOpen.value = props.open;
  },
  {
    immediate: true,
    deep: true,
  }
);

const handleClickOutside = (event) => {
  const searchContainer = document.querySelector(".template-aside");
  const openIcon = document.querySelector(".hamburger-icon");
  if (
    !searchContainer.contains(event.target) &&
    !openIcon.contains(event.target)
  ) {
    emit("open", false);
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
  <USlideover
    v-model="isOpen"
    side="left"
    :overlay="false"
    preventClose
    class="slideOver-container"
  >
    <div class="flex items-center justify-end">
      <UButton
        color="gray"
        variant="ghost"
        size="sm"
        icon="i-heroicons-x-mark-20-solid"
        class="flex absolute end-5 top-5 z-10 focus-visible:ring-0 hover:bg-akq-green-50"
        square
        padded
        @click="$emit('open', false)"
      />
    </div>
    <div class="h-full pt-20 template-aside aside">
      <UVerticalNavigation
        @click="$emit('open', false)"
        :links="getSidebarMenu()"
        :ui="{
          padding: 'p-7 ',
          active: 'active slideover',
          inactive: 'inactive slideover',
          icon: {
            base: 'flex-shrink-0 w-5 h-5 relative',
            active: 'text-gray-700 ',
            inactive: 'text-gray-400 group-hover:text-gray-700',
          },
        }"
      />
      <div class="absolute left-0 top-0 pl-8 pt-2">
        <NuxtLink :to="localePath('/')"><AkqLogo /></NuxtLink>
      </div>
    </div>
  </USlideover>
</template>
