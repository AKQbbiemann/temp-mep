<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();

/* const isAuthenticated = computed(() => !!authStore.token); */
const isAuthenticated = true;
</script>

<template>
  <div class="place-items-center items-center flex gap-3">
    <ClientOnly>
      <!--<ColorModeSelector /> -->
    </ClientOnly>
    <!-- <LanguageSwitcher /> -->

    <div v-if="!isAuthenticated" class="flex gap-2">
      <nuxt-link :to="localePath('/login')">
        <UButton color="akq-green" variant="outline">
          <UIcon
            name="i-heroicons-arrow-right-end-on-rectangle"
            class="px-2 py-3"
          />
          {{ $t("LOGIN") }}
        </UButton>
      </nuxt-link>
    </div>
    <ProfileMenuDropdowns v-else />
  </div>
</template>
