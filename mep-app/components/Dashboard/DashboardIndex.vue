<template>
  <div class="flex flex-col m-4 h-screen">
    <div
      class="sub-container grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
    >
      <!-- <DashboardBox :loading="true" /> -->
      <DashboardBox
        count="10"
        text="Cluster"
        icon="i-heroicons-user-group-solid"
      />
      <DashboardBox
        count="11"
        text="Kompetenzen"
        icon="i-mingcute-user-setting-line"
      />
      <DashboardBox
        count="10"
        text="Anforderungen"
        icon="i-mdi-file-question"
      />
      <DashboardBox
        lastbox
        count="10"
        text="Warten auf Freigabe"
        icon="i-clarity-clock-line"
      />
    </div>
    <div class="mt-4 sub-container h-full"></div>
  </div>
</template>
