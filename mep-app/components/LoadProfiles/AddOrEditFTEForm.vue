<script setup>
import { object, string, date, boolean, number, array } from "yup";
import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";
import { format, parseISO } from "date-fns";
const clustersStore = useClustersStore();
const { t } = useI18n();
const localeRoute = useLocaleRoute();
const appToast = useAppToast();
const emit = defineEmits(["isOpen", "updateView"]);
const props = defineProps({
  clusterId: Number | String,
  profileId: Number | String,
  employeeChangeId: Number | String,
});
onMounted(async () => {
  await getChange();
});

async function getChange() {
  if (props.employeeChangeId) {
    try {
      let data = toRaw(
        await clustersStore.getChange(
          props.clusterId,
          props.profileId,
          props.employeeChangeId
        )
      );
      state.change = data.change;
      state.start_date = data.start_date;
      state.end_date = data.end_date;
      state.reason = data.reason;
      console.log(data);
    } catch {}
  }
}
let patternTwoDigisAfterComma = /^-?\d+(\.\d{0,2})?$/;

const schema = object({
  start_date: date().required(t("THIS_FIELD_IS_REQUIRED")),
  end_date: date().nullable(),
  reason: string().required(t("THIS_FIELD_IS_REQUIRED")),
  change: number(t("The field must be a number"))
    .required()
    .test(
      "is-decimal",
      "The amount should be a decimal with maximum two digits after comma",
      (val) => {
        if (val != undefined) {
          return patternTwoDigisAfterComma.test(val);
        }
        return true;
      }
    ),
});
const state = reactive({
  start_date: format(new Date(), "yyyy-MM-dd"),
  end_date: undefined,
  change: undefined,
  reason: undefined,
});
onMounted(async () => {});

async function onSubmit(event) {
  console.log(props.employeeChangeId === "");
  if (schema.validateSync(state)) {
    try {
      await clustersStore.addOrEditFTEChanges(
        props.profileId,
        state.start_date,
        state.end_date,
        state.change,
        state.reason,
        props.employeeChangeId === "" ? "" : parseInt(props.employeeChangeId)
      );
      emit("isOpen", false);
      emit("updateView");
    } catch (e) {
      console.log(e);
    }
  }
}
</script>

<template>
  <div class=" ">
    <UCard
      :ui="{
        ring: '',
        divide: 'divide-y divide-gray-100 dark:divide-gray-800',
      }"
    >
      <template #header>
        <div class="flex items-center justify-between">
          <h3
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
          >
            {{ $t("Change FTE") }}
          </h3>
          <UButton
            color="gray"
            icon="i-heroicons-x-mark-20-solid"
            class="-my-1"
            @click="$emit('isOpen', false)"
          />
        </div>
      </template>

      <UForm
        :schema="schema"
        :state="state"
        class="space-y-4 flex flex-col h-full"
        @submit="onSubmit"
      >
        <UFormGroup :label="t('CHANGE')" name="change">
          <UInput
            v-model="state.change"
            class="rounded-input"
            type="number"
            step="0.01"
          />
        </UFormGroup>

        <UFormGroup :label="t('REASON')" name="reason">
          <UInput v-model="state.reason" class="rounded-input" />
        </UFormGroup>

        <UFormGroup :label="t('START_DATE')" name="start_date">
          <UInput
            v-model="state.start_date"
            class="rounded-input"
            type="date"
          />
        </UFormGroup>

        <UFormGroup :label="t('END_DATE')" name="end_date">
          <UInput v-model="state.end_date" class="rounded-input" type="date" />
        </UFormGroup>
      </UForm>
      <template #footer>
        <div class="flex justify-start pt-10">
          <UButton
            type="button"
            color="gray"
            class="text-base rounded mb-3 me-3"
            @click="$emit('isOpen', false)"
          >
            {{ $t("CANCEL") }}
          </UButton>
          <UButton
            type="submit"
            color="akq-green"
            class="text-base rounded mb-3"
            @click="onSubmit($event)"
          >
            {{ $t("SAVE") }}
          </UButton>
        </div>
      </template>
    </UCard>
  </div>
</template>
