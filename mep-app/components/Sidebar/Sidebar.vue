<script setup>
import { useRoute, useRouter } from "vue-router";
import { onMounted } from "vue";
import { getSidebarMenu } from "@/composables/useSidebarMenu";

let route = useRoute();
let router = useRouter();

onMounted(() => {
  route = useRoute();
});

const getMenu = () => {
  return getSidebarMenu().map((item) => {
    const { label, ...rest } = item;
    return rest;
  });
};

const logoLink = router.push('/');
</script>

<template>
  <div class="min-h-screen aside relative">
    <div class="logo-container">
      <img src="@/public/logo.png" alt="Akquinet Logo" class="logo-icon" />
      <NuxtLink class="full-logo" :to="logoLink"><AkqLogo /></NuxtLink>
    </div>
    <aside class="flex flex-col aside justify-between w-[100px]">
      <div class="template-aside flex justify-center">
        <UVerticalNavigation
          :links="getMenu()"
          :ui="{
            padding: 'px-3 py-3 ',
            active: 'exact-active',
            inactive: 'exact-inactive',
            icon: {
              base: 'flex-shrink-0 w-5 h-5 relative',
              active: 'text-white ',
              inactive: 'text-gray-400 group-hover:text-gray-700',
            },
          }"
        />
      </div>
      <div class="fixed bottom-0 left-3">
        <NuxtLink to="/" class="text-small text-gray-500">MEP 2024</NuxtLink>
      </div>
    </aside>
  </div>
</template>
