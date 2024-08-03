<script setup>
import { object, string, date, boolean, number, array } from "yup";
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { format, parseISO } from "date-fns";
const id = useRoute().params;

const emit = defineEmits(["closeDetailsView", "step", "updateRequirement"]);
const requirementsStore = useRequirementsStore();
const { t } = useI18n();

const isLoading = ref(false);
const cluster = ref();
const step = ref(1);
const typesList = ref();
const probabilitiesList = ref();
const statesList = ref();
const priority = ref();

onMounted(async () => {
  await getDropdownLists();
});

async function getDropdownLists() {
  const [
    states,
    probabilities,
    types,
    priorities,
    certainty,
    estimationAccuracies,
  ] = await requirementsStore.getDropdownLists();
  if (types) {
    typesList.value = Object.entries(types).map((type) => {
      return {
        shortCode: type[0],
        description: type[1],
      };
    });
  }
  if (probabilities) {
    probabilitiesList.value = Object.entries(probabilities).map(
      (probability) => {
        return {
          key: probability[0],
          value: probability[1],
        };
      }
    );
  }
  if (states) {
    statesList.value = Object.entries(states).map((state) => {
      return {
        key: state[0],
        value: state[1],
      };
    });
  }
  if (priorities) {
    priority.value = Object.entries(priorities).map((priority) => {
      return {
        key: parseInt(priority[0]),
        value: priority[1],
      };
    });
  }
}

const props = defineProps({
  data: Object,
});

const schema = object({
  title: string().required(t("THIS_FIELD_IS_REQUIRED")),
  type: string().required(t("THIS_FIELD_IS_REQUIRED")),
  owner: string().nullable(),
  creator: string().nullable(),
  customer: string().nullable(),
  description: string().required(t("THIS_FIELD_IS_REQUIRED")),
  infos: string().nullable(),
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
  title: props.data ? props.data.title : undefined,
  type: props.data ? props.data.type : undefined,
  owner: props.data ? props.data.owner : undefined,
  creator: props.data ? props.data.creator : undefined,
  customer: props.data ? props.data.customer : undefined,
  description: props.data ? props.data.description : undefined,
  infos: props.data ? props.data.infos : undefined,
  probability: props.data ? props.data.probability : undefined,
  start_date: props.data
    ? format(parseISO(props.data.start_date), "yyyy-MM-dd")
    : format(new Date(), "yyyy-MM-dd"),
  end_date: props.data
    ? format(parseISO(props.data.end_date), "yyyy-MM-dd")
    : format(new Date(), "yyyy-MM-dd"),
  start_date_is_strict: props.data ? props.data.start_date_is_strict : false,
  end_date_is_strict: props.data ? props.data.end_date_is_strict : false,
  time_period_description: props.data
    ? props.data.time_period_description
    : undefined,
  state: props.data ? props.data.state : undefined,
  company_priority: props.data ? props.data.company_priority : undefined,
  company_priority_description: props.data
    ? props.data.company_priority_description
    : undefined,
  requested_priority: props.data ? props.data.requested_priority : undefined,
  requested_priority_description: props.data
    ? props.data.requested_priority_description
    : undefined,
});

const customers = ["ESA", "customer1", "customer2", "customer3"];
async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      isLoading.value = true;
      await requirementsStore.addOrEdit(state, id.id ? parseInt(id.id) : "");
    } catch (e) {
      console.log(e);
    } finally {
      isLoading.value = false;
      emit("closeDetailsView");
      emit("updateRequirement");
    }
  }
}
</script>

<template>
  <div class="mt-10">
    <UForm :schema="schema" :state="state" class="space-y-4">
      <div v-if="step == 1">
        <UFormGroup :label="$t('TITLE')" name="title">
          <UInput v-model="state.title" class="rounded-input" />
        </UFormGroup>

        <UFormGroup :label="$t('TYPE')" name="type">
          <USelectMenu
            v-model="state.type"
            :options="typesList"
            class="rounded-input"
            placeholder="Select..."
            value-attribute="shortCode"
            option-attribute="shortCode"
          ></USelectMenu>
          <div v-for="item in typesList" :key="item.shortCode">
            <div
              v-if="item.shortCode == state.type"
              class="flex justify-start align-middle py-2"
            >
              <UIcon
                name="i-heroicons-exclamation-circle "
                class="w-6 h-6 pt-2 me-2 text-gray-600"
              />
              <span class="text-sm italic text-gray-600">
                {{ item.description }}</span
              >
            </div>
          </div>
        </UFormGroup>

        <UFormGroup :label="$t('OWNER')" name="owner">
          <UInput v-model="state.owner" class="rounded-input">
            <template #trailing>
              <span class="text-gray-500 dark:text-gray-400 text-xs"
                >@akquinet.de</span
              >
            </template>
          </UInput>
        </UFormGroup>

        <UFormGroup :label="$t('CREATOR')" name="creator">
          <UInput v-model="state.creator" class="rounded-input">
            <template #trailing>
              <span class="text-gray-500 dark:text-gray-400 text-xs"
                >@akquinet.de</span
              >
            </template>
          </UInput>
        </UFormGroup>

        <UFormGroup :label="$t('CUSTOMER')" name="customer">
          <USelect
            v-model="state.customer"
            :options="customers"
            class="rounded-input"
          />
        </UFormGroup>

        <UFormGroup :label="$t('DESCRIPTION')" name="description">
          <UTextarea v-model="state.description" class="rounded-input" />
        </UFormGroup>

        <UFormGroup :label="$t('INFOS')" name="infos">
          <UInput v-model="state.infos" class="rounded-input" />
        </UFormGroup>

        <div class="pt-10">
          <slot name="cancelBtn" />
          <UButton
            color="akq-green"
            class="justify-center text-base rounded mb-3"
            @click="
              step = 2;
              $emit('step', 2);
            "
          >
            {{ $t("NEXT") }}
          </UButton>
        </div>
      </div>
      <div v-if="step == 2">
        <!--    <UPopover :popper="{ placement: 'bottom-start' }">
          <UButton
            icon="i-heroicons-calendar-days-20-solid"
            :label="format(date, 'd MMM, yyy')"
          />

          <template #panel="{ close }">
            <DatePicker v-model="state.start_date" is-required @close="close" />
          </template>
        </UPopover> -->
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
              />
            </UFormGroup>
          </div>
          <div class="w-1/2 ps-2">
            <UFormGroup :label="$t('END_DATE')" name="end_date">
              <UInput
                v-model="state.end_date"
                class="rounded-input"
                type="date"
              />
            </UFormGroup>
            <UFormGroup name="end_date_is_strict">
              <UCheckbox
                class="pt-3"
                v-model="state.end_date_is_strict"
                :label="$t('END_DATE_IS_STRICT')"
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
            @click="
              step = 1;
              $emit('step', 1);
            "
          >
            {{ $t("BACK") }}
          </UButton>
          <UButton
            type="submit"
            color="akq-green"
            class="justify-center text-base rounded mb-3"
            @click="onSubmit()"
          >
            {{ $t("SAVE") }}
          </UButton>
        </div>
      </div>
    </UForm>
  </div>
</template>
