<script setup>
const props = defineProps({
  id: Number,
});

import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { useRouter } from "vue-router";
import { format, parseISO } from "date-fns";

const router = useRouter();
const requirementsStore = useRequirementsStore();
const { t } = useI18n();

const isLoading = ref(false);
const id = useRoute().params;
const requirement = ref();
const data = ref([]);

onMounted(async () => {
  await getReqirement();
});

async function getReqirement() {
  try {
    isLoading.value = true;
    requirement.value = toRaw(await requirementsStore.getRequirement(id.id))[0];
    data.value = Object.entries(requirement.value).filter((item) => {
      return (
        item[0] !== "phases" &&
        item[0] !== "id" &&
        item[0] !== "created_at" &&
        item[0] !== "updated_at"
      );
    });
  } catch {
  } finally {
    isLoading.value = false;
  }
}
</script>
<template>
  <div class="sub-container w-[425px] min-h-screen me-4">
    <div v-if="isLoading">
      <cluster-list-skeleton />
    </div>
    <div v-else>
      <div class="flex justify-between mb-4">
        <h3 class="text-akq-green text-xl font-bold">
          {{ $t("REQUIREMENTS_DETAILS") }}
        </h3>
        <slot name="editDeleteBtns" />
      </div>

      <div v-for="item in data" :key="item[0]">
        <RequiermentDetailsBlock
          v-if="
            item[0] !== 'end_date_is_strict' &&
            item[0] !== 'start_date_is_strict'
          "
          :lable="$t(item[0].toUpperCase())"
          :value="
            item[0] === 'start_date' || item[0] === 'end_date'
              ? item[0] === 'start_date'
                ? format(parseISO(item[1]), 'yyyy-MM-dd').concat(
                    data[11][1] === true ? t('DATE_IS_STRICT') : ''
                  )
                : format(parseISO(item[1]), 'yyyy-MM-dd').concat(
                    data[12][1] === true ? t('DATE_IS_STRICT') : ''
                  )
              : item[1]
          "
        />
      </div>
    </div>
  </div>
</template>
