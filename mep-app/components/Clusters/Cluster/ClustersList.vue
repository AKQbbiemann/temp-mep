<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";
import { useRouter } from "vue-router";
const router = useRouter();
const clustersStore = useClustersStore();
const { t } = useI18n();
const localeRoute = useLocaleRoute();

const isLoading = ref(false);
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
        @click="navigateTo(localeRoute('/clusters/new').fullPath)"
      />

      <ul>
        <li v-for="item in lists" :key="item.id" class="py-3 cluster-list">
          <nuxt-link
            :to="localeRoute(`/clusters/${item.id}`).fullPath"
            class="text-base wrap"
          >
            {{ item.name }}
          </nuxt-link>
        </li>
      </ul>
    </div>
  </div>
</template>
