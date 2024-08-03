<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { useRouter } from "vue-router";
import { format, parseISO } from "date-fns";

const router = useRouter();
const requirementsStore = useRequirementsStore();
const { t } = useI18n();
const requirementId = useRoute().params;

const isLoading = ref(false);
const requirement = ref();
const totalData = ref([]);
const requirementData = ref([]);
const ChartData = ref();
const phases = ref();
const tabPhases = ref();
const isPhasePage = ref(false);
let dataLabels = [];
let dataValues = [];
let isEditRequirement = ref(false);
const step = ref(1);
</script>
<template>
  <div class="sub-container h-full flex-1">
    <AddOrEditPhaseForm
      @updateRequirementsView="$emit('showRequirementDetails')"
    >
      <template #cancelBtn>
        <UButton
          type="button"
          color="gray"
          class="justify-center text-base rounded mb-3 me-3"
          @click="$emit('showRequirementDetails')"
        >
          {{ $t("CANCEL") }}
        </UButton>
      </template>
    </AddOrEditPhaseForm>
  </div>
</template>
