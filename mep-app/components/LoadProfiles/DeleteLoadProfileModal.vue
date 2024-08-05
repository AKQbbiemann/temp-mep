<script setup>
import { useloadProfileStore } from "@/stores/loadProfile";

const loadProfileStore = useloadProfileStore();
const { t } = useI18n();

const emit = defineEmits(["updateLoadProfiles", "isOpen"]);

const props = defineProps({
  loadProfileId: Number,
  clusterId: Number,
});

async function deleteLoadprofile() {
  try {
    await loadProfileStore.deleteLoadProfile(parseInt(props.loadProfileId));
  } catch (e) {
    console.log(e);
  }
}
</script>
<template>
  <UCard
    :ui="{
      ring: '',
      divide: 'divide-y divide-gray-100 dark:divide-gray-800',
      rounded: 'rounded',
    }"
  >
    <template #header>
      <div class="flex items-center justify-between">
        <h3
          class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
        >
          {{ $t("DELETE_LOAD_PROFILE") }}
        </h3>
        <UButton
          color="gray"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="$emit('isOpen', false)"
        />
      </div>
    </template>
    <p>{{ $t("DELETE_LOAD_PROFILE_MSG") }}</p>
    <template #footer>
      <div class="flex justify-end">
        <UButton
          type="button"
          color="gray"
          class="justify-center text-base rounded mb-3 me-3"
          rounded
          trailing
          :label="$t('CANCEL')"
          @click="$emit('isOpen', false)"
        />
        <UButton
          type="button"
          color="akq-green"
          class="justify-center text-base rounded mb-3"
          rounded
          trailing
          :label="$t('DELETE')"
          @click="
            $emit('isOpen', false);
            deleteLoadprofile();
            $emit('updateLoadProfiles');
          "
        />
      </div>
    </template>
  </UCard>
</template>
