<script setup>
const items = [
  {
    key: "clusters",
    label: "Clusters",
    description:
      "Make changes to your account here. Click save when you're done.",
  },
  {
    key: "skills",
    label: "Competencies",
    description:
      "Change your password here. After saving, you'll be logged out.",
  },
];

function onChange(index) {
  const item = items[index];
  if (item.key === "skills") {
    navigateTo("/clusters/skills");
  } else if (item.key === "clusters") {
    navigateTo("/clusters");
  }
}
</script>

<template>
  <div class="sub-nav">
    <UTabs
      :items="items"
      class="w-full sub-nav"
      @change="onChange"
      :ui="{
        list: { shadow: 'shadow-lg', rounded: 'rounded', padding: 'px-2' },
      }"
    >
      <template #default="{ item, selected }">
        <div class="flex items-center gap-2 relative truncate">
          <span class="truncate"> {{ $t(item.label) }}</span>
          <span
            v-if="selected"
            class="absolute -right-4 w-2 h-2 rounded-full bg-akq-green-500 dark:bg-akq-green-400"
          />
        </div>
      </template>

      <template #item="{ item }">
        <div v-if="item.key === 'clusters'" class="space-y-3">
          <clusters-list></clusters-list>
        </div>
        <div v-else-if="item.key === 'skills'" class="space-y-3">
          <skills-list></skills-list>
        </div>
      </template>
    </UTabs>
  </div>
</template>
