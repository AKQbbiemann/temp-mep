<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useSkillsStore } from "@/stores/skills";
import { useRouter } from "vue-router";
const router = useRouter();
const skillsStore = useSkillsStore();
const { t } = useI18n();
const localeRoute = useLocaleRoute();
const appToast = useAppToast();

const columns = [
  {
    key: "name",
  },
];
const list = computed(() => skillsStore.getskillsList);
const isLoading = ref(false);
const totalPages = ref(0);
const isOpenAddSkill = ref(false);
const selectedSkill = ref(null);

watch(list, (newValue, oldValue) => {
  list.value = skillsStore.getskillsList;
});

onMounted(async () => {
  await getList();
});

async function getList() {
  try {
    isLoading.value = true;
    list.value = toRaw(await skillsStore.fill());

    totalPages.value = list.value?.length || 0;
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}
const q = ref("");

const filteredRows = computed(() => {
  const newList = list.value;

  if (newList !== undefined && newList.length > 0) {
    if (!q.value) {
      totalPages.value = newList.length;
      return newList.slice(
        (page.value - 1) * pageCount,
        page.value * pageCount
      );
    }

    const filterdList = newList.filter((skill) => {
      return Object.values(skill).some((value) => {
        return String(value).toLowerCase().includes(q.value.toLowerCase());
      });
    });
    totalPages.value = filterdList.length;
    return filterdList.slice(
      (page.value - 1) * pageCount,
      page.value * pageCount
    );
  }
});

const page = ref(1);
const pageCount = 10;

/* const rows = computed(() => {
  return list.slice((page.value - 1) * pageCount, page.value * pageCount);
}); */
</script>

<template>
  <div class="mt-10">
    <UButton
      type="button"
      color="akq-green"
      icon="i-heroicons-plus-circle"
      class="w-full justify-center text-base rounded mb-3"
      rounded
      :trailing="false"
      :label="$t('ADD_COMPETENCE')"
      @click="
        isOpenAddSkill = true;
        selectedSkill = null;
      "
    />
    <div>
      <div
        class="flex py-3.5 border-b border-gray-200 dark:border-gray-700 w-full focus:ring-akq-green-700"
      >
        <UInput
          v-model="q"
          class="w-full px-0 rounded-input"
          :placeholder="$t('SEARCH')"
        />
      </div>

      <UTable
        :rows="filteredRows"
        :columns="columns"
        class="skill-list"
        @select="
          selectedSkill = $event.id;
          isOpenAddSkill = true;
        "
      />
      <div
        class="flex justify-center px-3 py-3.5 border-t border-gray-200 dark:border-gray-700"
      >
        <UPagination
          :activeButton="{
            color: 'akq-green',
          }"
          v-model="page"
          :page-count="pageCount"
          :total="totalPages"
        />
      </div>
    </div>

    <UModal v-model="isOpenAddSkill">
      <AddOrEditSkills :id="selectedSkill" @isOpen="isOpenAddSkill = $event" />
    </UModal>
  </div>
</template>
