<script setup>
import { useI18n } from "vue-i18n";
import { useClustersStore } from "@/stores/clusters";

const clustersStore = useClustersStore();
const { t } = useI18n();

const props = defineProps({
  clusterId: Number,
});

async function OnSubmit() {
  try {
    await clustersStore.deleteCluster(props.clusterId);
  } catch (e) {
    console.log(e);
  }
}

//deleteCluster
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
          {{ $t("DELETE_CLUSTER") }}
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
          @click="
            OnSubmit();
            $emit('isOpen', false);
          "
        />
      </div>
    </template>
  </UCard>
</template>
