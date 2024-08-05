<script setup>
import { useRequirementsStore } from "@/stores/requirements";
const requirementsStore = useRequirementsStore();
const { t } = useI18n();

const props = defineProps({
  id: Number | String,
});

const emit = defineEmits([
  "isOpen",
  "updateClusterParticipationsView",
  "removeClusterFromParticipation",
]);

async function OnSubmit() {
  if (props.id) {
    try {
      await requirementsStore.deleteClusterParticipation(props.id);
      emit("updateClusterParticipationsView");
      emit("isOpen", false);
    } catch (e) {
      console.log(e);
    }
  } else {
    emit("removeClusterFromParticipation");
    emit("isOpen", false);
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
          {{ $t("DELETE_CLUSTER_PATITCIPATION") }}
        </h3>
        <UButton
          color="gray"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="$emit('isOpen', false)"
        />
      </div>
    </template>
    <p>{{ $t("DELETE_CLUSTER_MSG") }}</p>
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
          @click="OnSubmit()"
        />
      </div>
    </template>
  </UCard>
</template>
