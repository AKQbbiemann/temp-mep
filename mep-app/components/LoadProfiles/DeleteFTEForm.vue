<script setup>
import { useClustersStore } from "@/stores/clusters";
import { format, parseISO } from "date-fns";
const clustersStore = useClustersStore();
const { t } = useI18n();
const emit = defineEmits(["updateView", "isOpen"]);

const props = defineProps({
  changeId: Number,
});

async function deleteChange() {
  try {
    await clustersStore.deleteChange(parseInt(props.changeId));
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
          {{ $t("DELETE_CHANGE") }}
        </h3>
        <UButton
          color="gray"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="$emit('isOpen', false)"
        />
      </div>
    </template>
    <p>{{ $t("DELETE_CHANGE_MSG") }}</p>
    <template #footer>
      <div class="flex justify-start">
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
          color="red"
          class="justify-center text-base rounded mb-3"
          rounded
          trailing
          :label="$t('DELETE')"
          @click="
            $emit('isOpen', false);
            deleteChange();
            $emit('updateView');
          "
        />
      </div>
    </template>
  </UCard>
</template>
