<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const { t, localePath } = useI18n();
const runtimeConfig = useRuntimeConfig();
const router = useRouter();
const authStore = useAuthStore();
const localeRoute = useLocaleRoute();

const dropdownItemsArray = computed(() => [
  [
    {
      label: "",
      slot: "account",
      disabled: true,
      isActive: true,
    },
  ],
  [
    {
      label: t("SETTINGS"),
      icon: "i-heroicons-cog-8-tooth",
      click: () => {
        const route = localeRoute({ name: "user-settings" });
        navigateTo(route.fullPath);
      },
      isActive: true,
    },
    {
      label: t("CHANGE_PASSWORD"),
      icon: "i-heroicons-key",
      click: () => {
        const route = localeRoute({ name: "user-change-password" });
        navigateTo(route.fullPath);
      },
      isActive: true,
    },
    {
      label: t("CHANGE_AVATAR"),
      icon: "i-heroicons-photo",
      click: () => {
        const route = localeRoute({ name: "user-change-avatar" });
        navigateTo(route.fullPath);
      },
      isActive: true,
    },
  ],
  [
    {
      label: t("REQUIREMENTS"),
      icon: "i-heroicons-building-office",
      click: () => {
        const route = localeRoute({ name: "requirements" });
        navigateTo(route.fullPath);
      },
      // isActive: isAdmin.value,
      isActive: true,
    },
  ],
  [
    {
      label: t("LOGOUT"),
      icon: "i-heroicons-arrow-left-on-rectangle",
      click: async () => {
        await authStore.logout();
      },
      isActive: true,
    },
  ],
]);

const activeDropdownItems = computed(() => {
  return dropdownItemsArray.value.map((items) => {
    return items.filter((item) => {
      return item.isActive === true;
    });
  });
});

const isAuthenticated = computed(() => !!authStore.token);
const user = computed(() => authStore.user);

const isUser = computed(() => authStore.userRole === "user");
const isAdmin = computed(() => authStore.userRole === "admin");

const userAvatar = computed(() => {
  return user.value?.avatar
    ? runtimeConfig.public.apiBaseUrl + user.value?.avatar
    : "https://cdn-icons-png.flaticon.com/128/847/847969.png";
});
</script>

<template>
  <UDropdown
    :items="activeDropdownItems"
    mode="hover"
    :ui="{ item: { disabled: 'cursor-text select-text' } }"
    :popper="{ placement: 'bottom-end' }"
  >
    <UAvatar :src="userAvatar" />

    <template #account="{ item }">
      <div v-if="user" class="text-left">
        <p>
          {{ $t("SIGNED_IN_AS") }} <b>{{ user?.role }}</b>
        </p>

        <p class="font-medium text-gray-900 truncate dark:text-white">
          {{ item.label }}{{ user.name }}
        </p>
        <p class="font-medium text-gray-900 truncate dark:text-white">
          {{ item.label }}{{ user.email }}
        </p>
      </div>
    </template>

    <template #item="{ item }">
      <span class="truncate">{{ item.label }}</span>
      <UIcon
        :name="item.icon"
        class="flex-shrink-0 w-4 h-4 text-gray-400 dark:text-gray-500 ms-auto"
      />
    </template>
  </UDropdown>
</template>
