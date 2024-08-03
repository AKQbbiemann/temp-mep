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
let patternTwoDigisAfterComma = /^\d+(\.\d{0,2})?$/;

/* const schema = (condition) =>
  object({
    name: string().required(t("THIS_FIELD_IS_REQUIRED")),
    full_time_employees: number().when([], {
      is: () => condition,
      then: number().nullable(),
      otherwise: number()
        .required(t("THIS_FILED_IS_REQUIRED"))
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
    }),
  });
 */

const schema = object({
  name: string().required(t("THIS_FIELD_IS_REQUIRED")),
  full_time_employees: number(t("The field must be a number"))
    .nullable()
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
  name: undefined,
  full_time_employees: undefined,
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
      valueComprehensive.value = data.comprehensive_load;
      valueLocal.value = data.local_load;
      valueBase.value = data.base_load;
      valueOrganisation.value = data.organisation_load;
      if (data.full_time_employees) {
        fullTimeEmployeesIsFilled.value = true;
        state.full_time_employees = data.full_time_employees;
      } else {
        fullTimeEmployeesIsFilled.value = false;
      }

      console.log(data);
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
        valueComprehensive.value,
        valueLocal.value,
        valueBase.value,
        valueOrganisation.value,
        fullTimeEmployeesIsFilled.value ? "" : state.full_time_employees,

        parseInt(props.clusterId),
        props.profileId ? parseInt(props.profileId) : ""
      );
      emit("updateLoadProfiles");
    } catch (e) {
      console.log(e);
    }
  }
}
</script>

<template>
  <div class="mt-10 ps-2">
    <UForm
      :schema="schema"
      :state="state"
      class="space-y-4 flex flex-col h-full"
      @submit="onSubmit"
    >
      <UFormGroup label="Name" name="name">
        <UInput v-model="state.name" class="rounded-input" />
      </UFormGroup>

      <UFormGroup
        label="FTE"
        name="full_time_employees"
        v-if="!fullTimeEmployeesIsFilled"
      >
        <UInput v-model="state.full_time_employees" class="rounded-input" />
      </UFormGroup>
      <h4 class="font-medium pt-10">Lasten</h4>

      <section class="grid gap-5 grid-cols-2">
        <UFormGroup
          :label="t('base_load'.toUpperCase())"
          :hint="valueBase.toString()"
        >
          <URange
            class="range"
            v-model="valueBase"
            color="akq-green"
            :min="0"
            :max="100"
            :step="1"
          />
        </UFormGroup>
        <UFormGroup
          :label="t('local_load'.toUpperCase())"
          :hint="valueLocal.toString()"
        >
          <URange
            v-model="valueLocal"
            color="akq-green"
            :min="0"
            :max="100"
            :step="1"
          />
        </UFormGroup>
        <UFormGroup
          :label="t('organisation_load'.toUpperCase())"
          :hint="valueOrganisation.toString()"
        >
          <URange
            v-model="valueOrganisation"
            color="akq-green"
            :min="0"
            :max="100"
            :step="1"
          />
        </UFormGroup>
        <UFormGroup
          :label="t('comprehensive_load'.toUpperCase())"
          :hint="valueComprehensive.toString()"
        >
          <URange
            v-model="valueComprehensive"
            color="akq-green"
            :min="0"
            :max="100"
            :step="1"
          />
        </UFormGroup>
      </section>
      <div class="flex justify-start pt-10">
        <UButton
          type="button"
          color="gray"
          class="text-base rounded mb-3 me-3"
          @click="$emit('backToOverview')"
        >
          {{ $t("CANCEL") }}
        </UButton>
        <UButton
          type="submit"
          color="akq-green"
          class="text-base rounded mb-3"
          @submit="onSubmit($event)"
        >
          {{ $t("SAVE") }}
        </UButton>
      </div>
    </UForm>
  </div>
</template>
