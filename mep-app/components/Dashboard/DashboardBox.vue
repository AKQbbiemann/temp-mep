<script setup>
defineProps({
  loading: Boolean,
  text: String,
  count: String,
  icon: String,
  lastbox: Boolean,
});
</script>

<template>
  <div>
    <USkeleton class="h-full w-full" v-if="loading" />
    <div v-else>
      <div class="dashboard-box p-6 bg-white dark:bg-gray-800 rounded-lg">
        <div class="flex items-center" :class="!lastbox ? 'border-r-2 ' : ''">
          <div class="flex items-center">
            <UIcon
              :name="icon"
              class="h-20 w-20 text-akq-green dark:text-gray-500 icon rounded-full p-3"
              dynamic
            />
          </div>
          <div>
            <h2
              class="ml-4 text-lg font-semibold text-gray-600 dark:text-white"
            >
              {{ text }}
            </h2>
            <p class="ml-4 text-2xl font-bold text-gray-900 dark:text-white">
              {{ count }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
