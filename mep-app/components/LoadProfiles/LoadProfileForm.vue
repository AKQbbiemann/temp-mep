<script setup>
import { object, string, number } from "yup";
import { toRaw } from "vue";
import { useloadProfileStore } from "@/stores/loadProfile";

const loadProfileStore = useloadProfileStore();
const { t } = useI18n();

const valueBase = ref(0);
const valueLocal = ref(0);
const valueOrganisation = ref(0);
const valueComprehensive = ref(0);
const fullTimeEmployeesIsFilled = ref(false);

const emit = defineEmits("updateLoadProfiles");
const props = defineProps({
  profileId: Number | String,
  isEditLoadProfile: Boolean,
  clusterId: Number | String,
});

const state = reactive({
  name: undefined,
  full_time_employees: undefined,
  valueBase: 0,
  valueLocal: 0,
  valueOrganisation: 0,
  valueComprehensive: 0,
});

let patternTwoDigisAfterComma = /^\d+(\.\d{0,2})?$/;

const schema = object({
  name: string().required(t("THIS_FIELD_IS_REQUIRED")),
  full_time_employees: number(t("THE_FIELD_MUST_BE_A_NUMBER"))
    .nullable()
    .test(
      "is-decimal",
      t("THE_AMOUNT_MUST_BE_A_DECIMAL_NUMBER_WITH_MAX_TWO_DECIMALS"),
      (val) => {
        if (val != undefined) {
          return patternTwoDigisAfterComma.test(val);
        }
        return true;
      }
    ),
  valueBase: number(t("THE_FIELD_MUST_BE_A_NUMBER"))
    .test(
      "len",
      t("MUST_BE_BETWEEN_0_AND_100_AND_SUM_OF_VALUES_SHOULD_BE_100"),
      (val) => {
        return 0 <= val <= 100 && sumOfLoadValues.value === 100;
      }
    )
    .required(t("THIS_FIELD_IS_REQUIRED"))
    .min(0, t("THE_VALUE_SHOULD_BE_GREATER_THAN_OR_EQUAL_TO_0"))
    .max(100, t("THE_VALUE_SHOULD_BE_LESS_THAN_OR_EQUAL_TO_100")),
  valueLocal: number(t("THE_FIELD_MUST_BE_A_NUMBER"))
    .test(
      "len",
      t("MUST_BE_BETWEEN_0_AND_100_AND_SUM_OF_VALUES_SHOULD_BE_100"),
      (val) => {
        return 0 <= val <= 100 && sumOfLoadValues.value === 100;
      }
    )
    .required(t("THIS_FIELD_IS_REQUIRED"))
    .min(0, t("THE_VALUE_SHOULD_BE_GREATER_THAN_OR_EQUAL_TO_0"))
    .max(100, t("THE_VALUE_SHOULD_BE_LESS_THAN_OR_EQUAL_TO_100")),
  valueOrganisation: number(t("THE_FIELD_MUST_BE_A_NUMBER"))
    .test(
      "len",
      t("MUST_BE_BETWEEN_0_AND_100_AND_SUM_OF_VALUES_SHOULD_BE_100"),
      (val) => {
        return 0 <= val <= 100 && sumOfLoadValues.value === 100;
      }
    )
    .required(t("THIS_FIELD_IS_REQUIRED"))
    .min(0, t("THE_VALUE_SHOULD_BE_GREATER_THAN_OR_EQUAL_TO_0"))
    .max(100, t("THE_VALUE_SHOULD_BE_LESS_THAN_OR_EQUAL_TO_100")),
  valueComprehensive: number(t("THE_FIELD_MUST_BE_A_NUMBER"))
    .test(
      "len",
      t("MUST_BE_BETWEEN_0_AND_100_AND_SUM_OF_VALUES_SHOULD_BE_100"),
      (val) => {
        return 0 <= val <= 100 && sumOfLoadValues.value === 100;
      }
    )
    .required(t("THIS_FIELD_IS_REQUIRED"))
    .min(0, t("THE_VALUE_SHOULD_BE_GREATER_THAN_OR_EQUAL_TO_0"))
    .max(100, t("THE_VALUE_SHOULD_BE_LESS_THAN_OR_EQUAL_TO_100")),
});

onMounted(async () => {
  await getLoadProfile();
});

async function getLoadProfile() {
  if (props.isEditLoadProfile) {
    try {
      let data = await loadProfileStore.getLoadProfile(
        props.clusterId,
        props.profileId
      );
      state.name = data.name;
      state.valueComprehensive = data.comprehensive_load;
      state.valueLocal = data.local_load;
      state.valueBase = data.base_load;
      state.valueOrganisation = data.organisation_load;
      if (data.full_time_employees) {
        fullTimeEmployeesIsFilled.value = true;
        state.full_time_employees = data.full_time_employees;
      } else {
        fullTimeEmployeesIsFilled.value = false;
      }
    } catch (e) {
      console.log(e);
    }
  }
}
async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      await loadProfileStore.addOrEdit(
        state.name,
        state.valueComprehensive,
        state.valueLocal,
        state.valueBase,
        state.valueOrganisation,
        fullTimeEmployeesIsFilled.value ? "" : state.full_time_employees,

        parseInt(props.clusterId),
        props.profileId ? parseInt(props.profileId) : ""
      );
      emit("updateLoadProfiles");
      emit("isOpen", false);
    } catch (e) {
      console.log(e);
    }
  }
}

// computed property for sum of valueBase, valueLocal, valueOrganisation, valueComprehensive
const sumOfLoadValues = computed(() => {
  return (
    Number(state.valueBase) +
    Number(state.valueLocal) +
    Number(state.valueOrganisation) +
    Number(state.valueComprehensive)
  );
});
</script>

<template>
  <div>
    <UCard
      :ui="{
        ring: '',
        divide: 'divide-y divide-gray-100 dark:divide-gray-800',
        rounded: 'rounded',
      }"
    >
      <template #header>
        <div class="flex items-center justify-between">
          <h3
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
          >
            {{ $t("ADD_LOAD_PROFILE") }}
          </h3>
          <UButton
            color="gray"
            icon="i-heroicons-x-mark-20-solid"
            class="-my-1"
            @click="$emit('isOpen', false)"
          />
        </div>
      </template>
      <div class="ps-2">
        <UForm
          :schema="schema"
          :state="state"
          class="space-y-4 flex flex-col h-full"
          @submit="onSubmit"
        >
          <section class="grid gap-5 grid-cols-2">
            <UFormGroup label="Name" name="name">
              <UInput v-model="state.name" class="rounded-input" />
            </UFormGroup>

            <UFormGroup
              label="FTE"
              name="full_time_employees"
              v-if="!fullTimeEmployeesIsFilled"
            >
              <UInput
                v-model="state.full_time_employees"
                class="rounded-input"
              />
            </UFormGroup>
          </section>
          <h4 class="font-medium">{{ t("LOADS") }}</h4>

          <section class="grid gap-5 grid-cols-2">
            <UFormGroup :label="t('base_load'.toUpperCase())" name="valueBase">
              <UInput
                v-model="state.valueBase"
                class="rounded-input"
                type="number"
              />
            </UFormGroup>
            <UFormGroup
              :label="t('local_load'.toUpperCase())"
              name="valueLocal"
            >
              <UInput
                v-model="state.valueLocal"
                class="rounded-input"
                type="number"
              />
            </UFormGroup>
            <UFormGroup
              :label="t('organisation_load'.toUpperCase())"
              name="valueOrganisation"
            >
              <UInput
                v-model="state.valueOrganisation"
                class="rounded-input"
                type="number"
              />
            </UFormGroup>
            <UFormGroup
              :label="t('comprehensive_load'.toUpperCase())"
              name="valueComprehensive"
            >
              <UInput
                v-model="state.valueComprehensive"
                class="rounded-input"
                type="number"
              />
            </UFormGroup>
          </section>
        </UForm>
      </div>

      <template #footer>
        <div class="flex justify-start">
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
