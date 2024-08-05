<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { useRouter } from "vue-router";
const router = useRouter();
const requirementsStore = useRequirementsStore();
const { t } = useI18n();

const isLoading = ref(false);

// Columns
const columns = [
  {
    key: "id",
    label: "#",
    sortable: true,
  },
  {
    key: "customer",
    label: t("CUSTOMER"),
    sortable: true,
  },
  {
    key: "title",
    label: t("TITLE"),
    sortable: true,
  },
  {
    key: "type",
    label: t("REQUEST_TYPE"),
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
    key: "participation",
    label: t("PARTICIPATION"),
    sortable: true,
  },
  {
    key: "total_load",
    label: t("TOTAL_LOAD"),
    sortable: true,
  },
  {
    key: "priority",
    label: t("PRIORITY"),
    sortable: true,
  },
  {
    key: "created_at",
    label: t("CREATED_AT"),
    sortable: true,
  },
];

/* const selectedColumns = ref(columns);
const columnsTable = computed(() =>
  columns.filter((column) => selectedColumns.value.includes(column))
); */

/* // Actions
const actions = [
  [
    {
      key: "completed",
      label: "Completed",
      icon: "i-heroicons-check",
    },
  ],
  [
    {
      key: "uncompleted",
      label: "In Progress",
      icon: "i-heroicons-arrow-path",
    },
  ],
]; */

/* // Filters
const requiermentStatus = [
  {
    key: "uncompleted",
    label: "In Progress",
    value: false,
  },
  {
    key: "completed",
    label: "Completed",
    value: true,
  },
]; */

/* const search = ref("");
const selectedStatus = ref([]);
const searchStatus = computed(() => {
  if (selectedStatus.value?.length === 0) {
    return "";
  }

  if (selectedStatus?.value?.length > 1) {
    console.log(
      `?completed=${selectedStatus.value[0].value}&completed=${selectedStatus.value[1].value}`
    );
    return `?completed=${selectedStatus.value[0].value}&completed=${selectedStatus.value[1].value}`;
  }
  return `?completed=${selectedStatus.value[0].value}`;
}); */

/* const resetFilters = () => {
  search.value = "";
  selectedStatus.value = [];
}; */

/* // Pagination
const sort = ref({ column: "id", direction: "asc" });
const page = ref(1);
const pageCount = ref(10);

const pageFrom = computed(() => (page.value - 1) * pageCount.value + 1);
const pageTo = computed(() =>
  Math.min(page.value * pageCount.value, pageTotal.value)
); */

const pageCount = ref(); // This value should be dynamic coming from the API

const list = ref();
// Data
onMounted(async () => {
  try {
    let data = toRaw(await requirementsStore.fill());
    list.value = data.data;
    console.log(data);
    pageCount.value = data.per_page;
    console.log(pageCount.value);
  } catch {}
});

/* const { data: todos, pending } =
  (await useLazyAsyncData) <
  {
    id: number,
    title: string,
    completed: string,
  } >
  ("todos",
  () =>
    $fetch(`https://jsonplaceholder.typicode.com/todos${searchStatus.value}`, {
      query: {
        q: search.value,
        _page: page.value,
        _limit: pageCount.value,
        _sort: sort.value.column,
        _order: sort.value.direction,
      },
    }),
  {
    default: () => [],
    watch: [page, search, searchStatus, pageCount, sort],
  });
console.log(`https://jsonplaceholder.typicode.com/todos${searchStatus.value}`); */
</script>

<template>
  <div class="">
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
      <!-- Filters -->
      <div class="flex items-center justify-between gap-3 px-4 py-3">
        <UInput
          v-model="search"
          icon="i-heroicons-magnifying-glass-20-solid"
          :placeholder="$t('SEARCH')"
        />

        <USelectMenu
          v-model="selectedStatus"
          :options="requiermentStatus"
          multiple
          placeholder="Status"
          class="w-40"
        />
      </div>

      <!-- Header and Action buttons -->
      <div class="flex justify-between items-center w-full px-4 py-3">
        <div class="flex items-center gap-1.5">
          <span class="text-sm leading-5"> {{ $t("ROWS_PRO_PAGE") }}</span>

          <USelect
            v-model="pageCount"
            :options="[3, 5, 10, 20, 30, 40]"
            class="me-2 w-20"
            size="xs"
          />
        </div>

        <div class="flex gap-1.5 items-center">
          <UDropdown :items="actions" :ui="{ width: 'w-36' }">
            <UButton
              icon="i-heroicons-chevron-down"
              trailing
              color="gray"
              size="xs"
            >
              Mark as
            </UButton>
          </UDropdown>

          <USelectMenu v-model="selectedColumns" :options="columns" multiple>
            <UButton icon="i-heroicons-view-columns" color="gray" size="xs">
              {{ $t("COLUMNS") }}
            </UButton>
          </USelectMenu>

          <UButton
            icon="i-heroicons-funnel"
            color="gray"
            size="xs"
            :disabled="search === '' && selectedStatus.length === 0"
            @click="resetFilters"
          >
            {{ $t("RESET") }}
          </UButton>
        </div>
      </div>

      <!-- Table -->
      <UTable
        :rows="list"
        :columns="columns"
        :loading="pending"
        class="w-full"
        :ui="{
          td: { base: 'max-w-[0] truncate' },
          default: { checkbox: { color: 'gray' } },
        }"
        @select="navigateTo(`/requirements/${$event.id}`)"
      >
        <template #completed-data="{ row }">
          <UBadge
            size="xs"
            :label="row.completed ? 'Completed' : 'In Progress'"
            :color="row.completed ? 'emerald' : 'orange'"
            variant="subtle"
          />
        </template>

        <template #actions-data="{ row }">
          <UButton
            v-if="!row.completed"
            icon="i-heroicons-check"
            size="2xs"
            color="emerald"
            variant="outline"
            :ui="{ rounded: 'rounded-full' }"
            square
          />

          <UButton
            v-else
            icon="i-heroicons-arrow-path"
            size="2xs"
            color="orange"
            variant="outline"
            :ui="{ rounded: 'rounded-full' }"
            square
          />
        </template>
      </UTable>

      <!-- Number of rows & Pagination -->
      <!--      <template #footer>
        <div class="flex flex-wrap justify-between items-center">
          <div>
            <span class="text-sm leading-5">
              Showing
              <span class="font-medium">{{ pageFrom }}</span>
              to
              <span class="font-medium">{{ pageTo }}</span>
              of
              <span class="font-medium">{{ pageTotal }}</span>
              results
            </span>
          </div>

          <UPagination
            v-model="page"
            :page-count="pageCount"
            :total="pageTotal"
            :ui="{
              wrapper: 'flex items-center gap-1',
              rounded: '!rounded-full min-w-[32px] justify-center',
              default: {
                activeButton: {
                  variant: 'outline',
                },
              },
            }"
          />
        </div>
      </template> -->
    </UCard>
  </div>
</template>
