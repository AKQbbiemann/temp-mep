<script setup>
import { useRequirementsStore } from "@/stores/requirements";
const requirementsStore = useRequirementsStore();
const { t } = useI18n();

const props = defineProps({
  id: Number,
});

const emit = defineEmits(["isOpen", "updateRequirement"]);

async function OnSubmit() {
  try {
    await requirementsStore.deletePhase(props.id);
  } catch (e) {
    console.log(e);
  }
}
</script>
<template>
  <UCard
    :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }"
  >
    <template #header>
      <div class="flex items-center justify-between">
        <h3
          class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
        >
          {{ $t("DELETE_PHASE") }}
        </h3>
        <UButton
          color="gray"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="$emit('isOpen', false)"
        />
      </div>
    </template>
    <p>{{ $t("DELETE_PHASE_MSG") }}</p>
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
            OnSubmit();
            $emit('updateRequirement');
          "
        />
      </div>
    </template>
  </UCard>
</template>
