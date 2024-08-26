<script setup>
import { object, string, date, boolean, number } from "yup";
import { format, parseISO } from "date-fns";
import { useRequirementsStore } from "@/stores/requirements";

const requirementsStore = useRequirementsStore();
const { requirements } = requirementsStore;

const { t } = useI18n();

const id = useRoute().params;

const isLoading = ref(false);

const emit = defineEmits(["step"]);

const props = defineProps({
  data: Object,
  probabilitiesList: Array,
  statesList: Array,
  priority: Array,
});

const schema = object({
  probability: string().required(t("THIS_FIELD_IS_REQUIRED")),
  start_date: date().required(t("THIS_FIELD_IS_REQUIRED")),
  end_date: date().required(t("THIS_FIELD_IS_REQUIRED")),
  start_date_is_strict: boolean().required(t("THIS_FIELD_IS_REQUIRED")),
  end_date_is_strict: boolean().required(t("THIS_FIELD_IS_REQUIRED")),
  time_period_description: string().nullable(),
  state: string().required(t("THIS_FIELD_IS_REQUIRED")),
  company_priority: number().nullable(),
  company_priority_description: string().nullable(),
  requested_priority: number().nullable(),
  requested_priority_description: string().required(
    t("THIS_FIELD_IS_REQUIRED")
  ),
});

const state = reactive({
  probability: props.data ? props.data.probability : requirements.probability,
  start_date: props.data
    ? format(parseISO(props.data.start_date), "yyyy-MM-dd")
    : format(new Date(), "yyyy-MM-dd"),
  end_date: props.data
    ? format(parseISO(props.data.end_date), "yyyy-MM-dd")
    : format(new Date(), "yyyy-MM-dd"),
  start_date_is_strict: props.data
    ? props.data.start_date_is_strict
    : requirements.start_date_is_strict,
  end_date_is_strict: props.data
    ? props.data.end_date_is_strict
    : requirements.end_date_is_strict,
  time_period_description: props.data
    ? props.data.time_period_description
    : requirements.time_period_description,
  state: props.data ? props.data.state : requirements.state,
  company_priority: props.data
    ? props.data.company_priority
    : requirements.company_priority,
  company_priority_description: props.data
    ? props.data.company_priority_description
    : requirements.company_priority_description,
  requested_priority: props.data
    ? props.data.requested_priority
    : requirements.requested_priority,
  requested_priority_description: props.data
    ? props.data.requested_priority_description
    : requirements.requested_priority_description,
});

async function onSubmit(event) {
  requirements.probability = state.probability;
  requirements.start_date = state.start_date;
  requirements.end_date = state.end_date;
  requirements.start_date_is_strict = state.start_date_is_strict;
  requirements.end_date_is_strict = state.end_date_is_strict;
  requirements.time_period_description = state.time_period_description;
  requirements.state = state.state;
  requirements.company_priority = state.company_priority;
  requirements.company_priority_description =
    state.company_priority_description;
  requirements.requested_priority = state.requested_priority;
  requirements.requested_priority_description =
    state.requested_priority_description;

  if (schema.validateSync(state)) {
    try {
      isLoading.value = true;
      await requirementsStore.addOrEdit(
        requirements,
        id.id ? parseInt(id.id) : ""
      );
    } catch (e) {
      console.log(e);
    } finally {
      isLoading.value = false;
    }
  }
}
</script>

<template>
  <UForm :schema="schema" :state="state" class="space-y-4">
    <div class="flex justify-between">
      <div class="w-1/2 pe-2">
        <UFormGroup :label="$t('START_DATE')" name="start_date">
          <UInput
            v-model="state.start_date"
            class="rounded-input"
            type="date"
          />
        </UFormGroup>
        <UFormGroup name="start_date_is_strict">
          <UCheckbox
            class="pt-3"
            v-model="state.start_date_is_strict"
            :label="$t('START_DATE_IS_STRICT')"
            color="akq-green"
          />
        </UFormGroup>
      </div>
      <div class="w-1/2 ps-2">
        <UFormGroup :label="$t('END_DATE')" name="end_date">
          <UInput v-model="state.end_date" class="rounded-input" type="date" />
        </UFormGroup>
        <UFormGroup name="end_date_is_strict">
          <UCheckbox
            class="pt-3"
            v-model="state.end_date_is_strict"
            :label="$t('END_DATE_IS_STRICT')"
            color="akq-green"
          />
        </UFormGroup>
      </div>
    </div>
    <UFormGroup
      :label="$t('TIME_PERIOD_DESCRIPTION')"
      name="time_period_description"
    >
      <UTextarea
        v-model="state.time_period_description"
        class="rounded-input"
        type="date"
      />
    </UFormGroup>

    <UFormGroup :label="$t('PROBABILITY')" name="probability">
      <USelectMenu
        v-model="state.probability"
        :options="probabilitiesList"
        class="rounded-input"
        placeholder="Select..."
        value-attribute="key"
        option-attribute="value"
      ></USelectMenu>
    </UFormGroup>

    <UFormGroup :label="$t('COMPANY_PRIORITY')" name="company_priority">
      <USelectMenu
        v-model="state.company_priority"
        :options="priority"
        class="rounded-input"
        placeholder="Select..."
        value-attribute="key"
        option-attribute="value"
      ></USelectMenu>
    </UFormGroup>

    <UFormGroup
      :label="$t('COMPANY_PRIORITY_DESCRIPTION')"
      name="company_priority_description"
    >
      <UTextarea
        v-model="state.company_priority_description"
        class="rounded-input"
      />
    </UFormGroup>
    <UFormGroup :label="$t('REQUESTED_PRIORITY')" name="requested_priority">
      <USelectMenu
        v-model="state.requested_priority"
        :options="priority"
        class="rounded-input"
        placeholder="Select..."
        value-attribute="key"
        option-attribute="value"
      ></USelectMenu>
    </UFormGroup>

    <UFormGroup
      :label="$t('REQUESTED_PRIORITY_DESCRIPTION')"
      name="requested_priority_description"
    >
      <UTextarea
        v-model="state.requested_priority_description"
        class="rounded-input"
      />
    </UFormGroup>

    <UFormGroup :label="$t('STATE')" name="state">
      <USelectMenu
        v-model="state.state"
        :options="statesList"
        class="rounded-input"
        placeholder="Select..."
        value-attribute="key"
        option-attribute="value"
      ></USelectMenu>
    </UFormGroup>

    <div class="mt-10">
      <UButton
        color="gray"
        class="justify-center text-base rounded mb-3 me-3"
        @click="$emit('step', 1)"
      >
        {{ $t("BACK") }}
      </UButton>
      <UButton
        type="submit"
        color="akq-green"
        :disabled="isLoading"
        @click="onSubmit"
        class="justify-center text-base rounded mb-3"
      >
        {{ $t("SAVE") }}
      </UButton>
    </div>
  </UForm>
</template>
