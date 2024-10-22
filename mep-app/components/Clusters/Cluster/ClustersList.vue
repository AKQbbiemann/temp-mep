<script setup>
import { useI18n } from "vue-i18n";
import { defineProps, defineEmits, onMounted, ref, computed, watch } from "vue";

import { reactive } from "vue";
import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";
import { useRouter } from "vue-router";
const router = useRouter();
const clustersStore = useClustersStore();
const { t } = useI18n();

const isLoading = ref(false);
const isOpenAddCluster = ref(false);

const lists = computed(() => clustersStore.getClustersList);

watch(lists, (newValue, oldValue) => {
  lists.value = clustersStore.getClustersList;
});

onMounted(async () => {
  await getList();
});

async function getList() {
  try {
    isLoading.value = true;
    lists.value = toRaw(await clustersStore.fill());
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}

function getClusterPath(clusterId) {
  return `/clusters/${clusterId}`; // Build the path manually
}
</script>
<template>
  <div class="mt-10">
    <div v-if="isLoading">
      <ClusterListSkeleton />
    </div>
    <div v-else>
      <UButton
        type="button"
        color="akq-green"
        icon="i-heroicons-plus-circle"
        class="w-full justify-center text-base rounded mb-3"
        rounded
        block
        :trailing="false"
        :label="$t('ADD_CLUSTER')"
        @click="isOpenAddCluster = true"
      />

      <ul>
        <nuxt-link
          v-for="item in lists"
          :key="item.id"
          :to="getClusterPath(item.id)"
          class="text-base wrap"
        >
          <li
            class="py-4 cluster-list cursor-pointer text-gray-500 text-sm flex justify-between"
          >
            <span>
              {{ item.name }}
            </span>
            <UButton
              icon="i-heroicons-trash-20-solid"
              size="2xs"
              color="red"
              variant="ghost"
              :ui="{ rounded: 'rounded-full' }"
              square
              :disabled="true"
              @click.stop="true"
            />
          </li>
        </nuxt-link>
      </ul>

      <UModal v-model="isOpenAddCluster">
        <AddOrEditCluster type="add" @isOpen="isOpenAddCluster = $event" />
      </UModal>
    </div>
  </div>
</template>
