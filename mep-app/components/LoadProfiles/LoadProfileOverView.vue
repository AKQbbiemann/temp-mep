<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRouter } from "vue-router";
import { format, parseISO } from "date-fns";
import { useloadProfileStore } from "@/stores/loadProfile";

const loadProfileStore = useloadProfileStore();
const router = useRouter();
const { t } = useI18n();
const isLoading = ref(false);
const pageCount = 25;
const page = ref(1);
const items = ref();
const list = ref();
const updateFTEs = ref(true);

const q = ref("");

const filteredRows = computed(() => {
  const newList = list.value;
  if (newList !== undefined && newList.length > 0) {
    if (!q.value) {
      items.value = newList.length;
      return newList.slice(
        (page.value - 1) * pageCount,
        page.value * pageCount
      );
    }

    const filterdList = newList.filter((loadProfile) => {
      return Object.values(loadProfile).some((value) => {
        return String(value).toLowerCase().includes(q.value.toLowerCase());
      });
    });
    items.value = filterdList.length;
    return filterdList.slice(
      (page.value - 1) * pageCount,
      page.value * pageCount
    );
  }
});

/* const querys = qs.stringify(
  {
    filters: {
      cluster: {
        name: {
          $eq: "Service",
        },
      },
    },
  },
  {
    encodeValuesOnly: true, // prettify URL
  }
);
 */

const columns = [
  {
    key: "cluster",
    label: "Cluster",
    sortable: true,
  },
  {
    key: "name",
    label: t("Name"),
    sortable: true,
  },
  {
    key: "description",
    label: t("DESCRIPTION"),
    sortable: true,
  },
  {
    key: "loads",
    label: t("LOADS"),
    sortable: false,
  },
  {
    key: "competences",
    label: t("COMPETENCES"),
    sortable: true,
  },
  {
    key: "ftes",
    label: t("FTEs"),
    sortable: false,
  },
];

const selectedColumns = ref([...columns]);

onMounted(async () => {
  await getLoadProfiles("/profiles");
});

async function getLoadProfiles() {
  try {
    let data = toRaw(await loadProfileStore.getLoadProfiles());
    list.value = data;
    items.value = data?.length || 0;
  } catch (e) {
    console.log(e);
  }
}
async function refreshData(pageNr) {
  await getLoadProfiles(`/profiles?page=${pageNr}`);
}
</script>
<template>
  <div>
    <h1 class="pb-4 text-akq-green font-bold text-xl">
      {{ $t("LOAD_PROFILE") }}
    </h1>
    <UCard
      class="w-full"
      :ui="{
        base: '',
        ring: '',
        divide: 'divide-y divide-gray-200 dark:divide-gray-700',
        header: { padding: 'px-4 py-5' },
        body: {
          padding: '',
          base: 'divide-y divide-gray-200 dark:divide-gray-700',
        },
        footer: { padding: 'p-4' },
        rounded: 'rounded',
      }"
    >
      <div v-if="isLoading">loading</div>
      <div v-else>
        <div
          class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700"
        >
          <div class="flex justify-between w-full">
            <UInput
              v-model="q"
              class="rounded-input"
              :placeholder="t('SEARCH')"
            ></UInput>
            <USelectMenu
              v-model="selectedColumns"
              :options="columns"
              multiple
              class="rounded-input"
            >
              <template #label>
                <span v-if="selectedColumns" class=""
                  >{{ selectedColumns.length }}
                  {{ $t("COLUMNS_ARE_DISPLAYED") }}</span
                >
                <span v-else>{{ $t("COLUMNS") }}</span>
              </template>
            </USelectMenu>
          </div>
        </div>

        <UTable
          :columns="selectedColumns"
          :rows="filteredRows"
          :ui="{
            base: 'w-full table-fixed',
            td: {
              base: 'whitespace-nowrap',
              padding: 'px-4 py-4',
              color: 'text-gray-500 dark:text-gray-400',
              font: '',
              size: 'text-sm',
            },
          }"
        >
          <template #loading-state>
            <div class="h-[400px]">
              <loader />
            </div>
          </template>

          <template
            v-for="col in selectedColumns"
            :key="col"
            v-slot:[`${col.key}-data`]="{ row }"
          >
            <div v-if="col.key === 'cluster'">
              <span class="text-wrap break-words">{{ row[col.key].name }}</span>
            </div>
            <div v-else-if="col.key === 'competences'">
              <div
                v-for="competence in row[col.key]"
                :key="competence.id"
                class="mb-1"
              >
                <UBadge color="sky" variant="subtle"
                  ><span class="text-wrap">{{ competence.name }} </span></UBadge
                >
              </div>
            </div>
            <div v-else-if="col.key === 'loads'">
              <div
                class="rounded border-2 border-akq-green-ag px-2 py-1 w-min mb-1"
              >
                <span class="text-akq-green-ag font-semibold">
                  {{ $t("ORGANISATION_LOAD") }} =
                </span>
                <span class="text-akq-green-ag"
                  >{{ row["organisation_load"] }} %</span
                >
              </div>
              <div>
                <div
                  class="rounded border-2 border-akq-green px-2 py-1 w-min mb-1"
                >
                  <span class="text-akq-green font-semibold">
                    {{ $t("BASE_LOAD") }} =
                  </span>
                  <span class="text-akq-green">{{ row["base_load"] }} %</span>
                </div>
              </div>
              <div>
                <div
                  class="rounded border-2 border-akq-yellow px-2 py-1 w-min mb-1"
                >
                  <span class="text-akq-yellow font-semibold">
                    {{ $t("LOCAL_LOAD") }} =
                  </span>
                  <span class="text-akq-yellow">{{ row["local_load"] }} %</span>
                </div>
              </div>

              <div>
                <div
                  class="rounded border-2 border-akq-red px-2 py-1 w-min mb-1"
                >
                  <span class="text-akq-red font-semibold">
                    {{ $t("COMPREHENSIVE_LOAD") }} =
                  </span>
                  <span class="text-akq-red"
                    >{{ row["comprehensive_load"] }} %</span
                  >
                </div>
              </div>
            </div>
            <div v-else-if="col.key === 'ftes'">
              <div>{{ row["full_time_employees"] }}</div>
            </div>

            <div v-else>
              <span class="text-wrap break-words">
                {{ row[col.key] ? row[col.key] : "---" }}
              </span>
            </div>
          </template>
        </UTable>
      </div>
      <div>
        <UPagination
          :activeButton="{
            color: 'akq-green',
          }"
          class="pt-2"
          v-model="page"
          :page-count="pageCount"
          :total="items"
          :ui="{
            default: {
              activeButton: {
                color: 'akq-green',
              },
            },
          }"
          @click="updateFTEs = true"
        />
      </div>
    </UCard>
  </div>
</template>
