<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { useRouter } from "vue-router";
import { format, parseISO } from "date-fns";
import qs from "qs";

const router = useRouter();
const requirementsStore = useRequirementsStore();
const { t } = useI18n();
const isLoading = ref(false);
const stateList = ref();
const priorityList = ref();
const typesList = ref();
const pageCount = ref();
const page = ref(1);
const items = ref();
const filteredState = ref("");
const filteredPrio = ref("");
const filteredType = ref("");
const querys = ref();
const query = ref({ filters: { $or: [] } });
const search = ref();

watch(filteredState, async (newValue, oldValue) => {
  if (newValue === "all") {
    query.value.filters.state = {};
  } else {
    query.value.filters.state = { $eq: newValue };
  }
});
watch(filteredType, async (newValue, oldValue) => {
  if (newValue === "all") {
    query.value.filters.type = {};
  } else {
    query.value.filters.type = { $eq: newValue };
  }
});

watch(filteredPrio, async (newValue, oldValue) => {
  if (newValue === "all") {
    query.value.filters.requested_priority = {};
  } else {
    query.value.filters.requested_priority = { $eq: newValue };
  }
});

watch(query.value, async (newValue, oldValue) => {
  querys.value = toString(toRaw(newValue));

  await getRequirements(querys.value);
});

function setSearchedQuery() {
  query.value.filters.$or = [];
  query.value.filters.$or.push({ title: { $contains: search.value } });
  query.value.filters.$or.push({ customer: { $contains: search.value } });
}

function toString(query) {
  return qs.stringify(query, {
    encodeValuesOnly: true, // prettify URL
  });
}

async function resetFilters() {
  query.value.filters = { $or: [] };
}
const columns = [
  {
    key: "id",
    label: "#",
    sortable: true,
    class: "w-16",
  },
  {
    key: "customer",
    label: t("CUSTOMER"),
    sortable: true,
    class: "w-20",
  },
  {
    key: "title",
    label: t("TITLE"),
    sortable: true,
  },
  {
    key: "type",
    label: t("TYPE"),
    sortable: true,
  },
  {
    key: "owner",
    label: t("OWNER"),
    sortable: true,
  },
  {
    key: "start_date",
    label: t("START_DATE"),
    sortable: true,
  },
  {
    key: "end_date",
    label: t("END_DATE"),
    sortable: true,
  },
  {
    key: "state",
    label: t("STATE"),
    sortable: true,
  },
  {
    key: "cluster_participation",
    label: t("PARTICIPATION"),
    sortable: true,
  },
  {
    key: "total_load",
    label: t("TOTAL_LOAD"),
    sortable: true,
  },
  {
    key: "requested_priority",
    label: t("PRIORITY"),
    sortable: true,
  },
  {
    key: "created_at",
    label: t("CREATED_AT"),
    sortable: true,
  },
];

const selectedColumns = ref([...columns]);
const list = ref();

onMounted(async () => {
  await getRequirements("");
  await getDropdownLists();
});

async function getRequirements(querys) {
  try {
    let data = toRaw(
      await requirementsStore.getRequirementsWithCustomQuery(querys)
    );
    list.value = data?.data;
    items.value = data?.total || 0;
    pageCount.value = data?.per_page;
  } catch (e) {
    console.log(e);
  }
}

async function refreshData(pageNr) {
  querys.value = toString(query.value);
  let data = toRaw(
    await requirementsStore.getRequirementsWithCustomQuery(
      `${querys.value}&page=${pageNr}`
    )
  );
  list.value = data.data;
}

async function getDropdownLists() {
  try {
    const [
      states,
      probabilities,
      types,
      priorities,
      certainty,
      estimationAccuracies,
    ] = await requirementsStore.getDropdownLists();
    if (states) {
      stateList.value = Object.entries(states).map((state) => {
        return {
          key: state[0],
          value: state[1],
        };
      });
      stateList.value.push({ key: "all", value: t("ALL") });
    }
    if (priorities) {
      priorityList.value = Object.entries(priorities).map((priority) => {
        return {
          key: priority[0],
          value: priority[1],
        };
      });
      priorityList.value.push({ key: "all", value: t("ALL") });
    }
    if (types) {
      typesList.value = Object.entries(types).map((type) => {
        return {
          key: type[0],
          value: type[0],
        };
      });
      typesList.value.push({ key: "all", value: t("ALL") });
    }
  } catch (e) {
    console.log(e);
  }
}
</script>

<template>
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
        class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700 justify-between"
      >
        <div class="flex">
          <UInput
            v-model="search"
            :placeholder="t('SEARCH')"
            class="rounded-input"
          />
          <UButton
            icon="i-heroicons-magnifying-glass-20-solid"
            class="hover:bg-white"
            size="sm"
            color="akq-green"
            square
            variant="ghost"
            @click="setSearchedQuery"
          />
        </div>
        <div class="flex">
          <UButton
            color="white"
            size="sm"
            @click="resetFilters"
            class="me-3 rounded"
          >
            <UIcon
              name="i-heroicons-funnel"
              class="h-4 w-4 text-akq-green align-middle"
            >
            </UIcon>
            <span class="text-gray-500"> {{ $t("RESET") }} </span>
          </UButton>
          <USelectMenu
            v-model="selectedColumns"
            :options="columns"
            multiple
            class="rounded-input"
          >
            <template #label>
              <span v-if="selectedColumns" class="text-gray-500">
                <UIcon
                  name="i-heroicons-view-columns"
                  class="h-4 w-4 text-akq-green align-middle"
                />
                {{ selectedColumns.length }}
                {{ $t("COLUMNS_ARE_DISPLAYED") }}</span
              >
              <span v-else>
                <UButton icon="i-heroicons-view-columns" color="gray" size="xs">
                  {{ $t("COLUMNS") }}
                </UButton>
              </span>
            </template>
          </USelectMenu>
        </div>
      </div>

      <UTable
        :columns="selectedColumns"
        :rows="list"
        @select="navigateTo(`/requirements/${$event.id}`)"
        :empty-state="{
          icon: 'i-heroicons-circle-stack-20-solid',
          label: t('NO_ITEMS'),
        }"
        :ui="{
          base: 'w-full table-fixed',
          td: {
            base: 'whitespace-nowrap',
            padding: 'px-2 py-4',
            color: 'text-gray-500 dark:text-gray-400',
            font: '',
            size: 'text-sm',
          },
          th: {
            base: 'text-left rtl:text-right',
            padding: 'px-2 py-3.5',
            color: 'text-gray-900 dark:text-white',
            font: 'font-semibold',
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
          v-slot:[`${col.key}-header`]="{ column }"
        >
          <div
            v-if="col.key === 'type'"
            class="flex align-middle justify-start"
          >
            <span class="text-wrap py-3.5">
              {{ column.label }}
            </span>
            <USelectMenu
              class="pt-3 px-0"
              v-model="filteredType"
              :options="typesList"
              value-attribute="key"
              option-attribute="value"
              :uiMenu="{
                container: 'z-20 group min-w-40 ',
              }"
            >
              <UButton
                class="text-gray-500 px-0 ps-1"
                color="white"
                variant="ghost"
              >
                <UIcon
                  name="i-heroicons-funnel"
                  class="h-4 w-4 text-akq-green align-middle"
                >
                </UIcon>
              </UButton>
            </USelectMenu>
          </div>
          <div
            v-else-if="col.key === 'state'"
            class="flex align-middle justify-start"
          >
            <span class="text-wrap py-3.5">{{ column.label }} </span>

            <USelectMenu
              class="pt-3 px-0"
              v-model="filteredState"
              :options="stateList"
              value-attribute="key"
              option-attribute="value"
              :uiMenu="{
                container: 'z-20 group min-w-40 ',
              }"
            >
              <UButton
                class="text-gray-500 px-0 ps-1"
                color="white"
                variant="ghost"
              >
                <UIcon
                  name="i-heroicons-funnel"
                  class="h-4 w-4 text-akq-green align-middle"
                >
                </UIcon>
              </UButton>
            </USelectMenu>
          </div>
          <div
            v-else-if="col.key === 'requested_priority'"
            class="flex align-middle justify-start"
          >
            <span class="text-wrap py-3.5">{{ column.label }} </span>

            <USelectMenu
              class="pt-3 px-0"
              v-model="filteredPrio"
              :options="priorityList"
              value-attribute="key"
              option-attribute="value"
              :uiMenu="{
                container: 'z-20 group min-w-40 ',
              }"
            >
              <UButton
                class="text-gray-500 px-0 ps-1"
                color="white"
                variant="ghost"
              >
                <UIcon
                  name="i-heroicons-funnel"
                  class="h-4 w-4 text-akq-green align-middle"
                >
                </UIcon>
              </UButton>
            </USelectMenu>
          </div>
          <div v-else>
            {{ column.label }}
          </div>
        </template>

        <template
          v-for="col in selectedColumns"
          :key="col"
          v-slot:[`${col.key}-data`]="{ row }"
        >
          <div v-if="col.key === 'cluster_participation'">
            <div
              v-for="participation in Object.entries(row[col.key])"
              :key="participation"
            >
              <div class="pe-2 truncate break-words">
                {{ participation[0] }}
              </div>
              <div class="font-bold italic mb-1">{{ participation[1] }} PT</div>
            </div>
          </div>
          <div
            v-else-if="
              col.key === 'start_date' ||
              col.key === 'end_date' ||
              col.key === 'created_at'
            "
          >
            {{ format(parseISO(row[col.key]), "yyyy-MM-dd") }}
          </div>
          <div v-else-if="col.key === 'state'">
            <div v-for="state in stateList" :key="state.key">
              <span
                class="text-wrap break-words"
                v-if="state.key === row[col.key]"
                >{{ state.value }}
              </span>
            </div>
          </div>
          <div v-else-if="col.key === 'requested_priority'">
            <div v-for="priority in priorityList" :key="priority.key">
              <span
                class="text-wrap break-words"
                v-if="priority.key == row[col.key]"
                >{{ priority.value }}
              </span>
            </div>
          </div>

          <div v-else>
            <span
              class="text-wrap break-words"
              :class="col.key === 'title' ? 'max-w-[150px] truncate' : ''"
            >
              {{ row[col.key] ? row[col.key] : "---" }}
            </span>
          </div>
          <!-- your td content -->
        </template>
      </UTable>
    </div>
    <div>
      <UPagination
        class="pt-2"
        v-model="page"
        :page-count="25"
        :total="items"
        :activeButton="{
          color: 'akq-green',
        }"
        @click="refreshData(page)"
      />
    </div>
  </UCard>
</template>
