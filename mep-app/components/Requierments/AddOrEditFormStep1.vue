<script setup>
import { object, string } from "yup";
import { useRequirementsStore } from "@/stores/requirements";

const requirementsStore = useRequirementsStore();
const { requirements } = requirementsStore;

const { t } = useI18n();

const customersList = ref();

const props = defineProps({
  data: Object,
  typesList: Array,
});

const emit = defineEmits(["step"]);

onMounted(async () => {
  await getCustomers();
});

const schema = object({
  title: string().required(t("THIS_FIELD_IS_REQUIRED")),
  type: string().required(t("THIS_FIELD_IS_REQUIRED")),
  owner: string().nullable(),
  creator: string().nullable(),
  customer: string().nullable(),
  description: string().required(t("THIS_FIELD_IS_REQUIRED")),
  infos: string().nullable(),
});

const state = reactive({
  title: props.data ? props.data.title : requirements.title,
  type: props.data ? props.data.type : requirements.type,
  owner: props.data ? props.data.owner : requirements.owner,
  creator: props.data ? props.data.creator : requirements.creator,
  customer: props.data ? props.data.customer : requirements.customer,
  description: props.data ? props.data.description : requirements.description,
  infos: props.data ? props.data.infos : requirements.infos,
});

async function getCustomers() {
  const customers = await requirementsStore.getCustomers();
  if (customers) {
    customersList.value = customers.map((customer) => {
      return {
        value: customer.id,
        label: customer.name,
      };
    });
  }
}

async function onSubmit(event) {
  requirements.title = state.title;
  requirements.type = state.type;
  requirements.owner = state.owner;
  requirements.creator = state.creator;
  requirements.customer = state.customer;
  requirements.description = state.description;
  requirements.infos = state.infos;

  if (schema.validateSync(state)) {
    emit("step", 2);
  }
}
</script>

<template>
  <UForm :schema="schema" :state="state" class="space-y-4">
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
        :options="customersList"
        class="rounded-input customer-select"
      />
      <UIcon
        v-if="state.customer"
        name="i-heroicons-x-mark-20-solid"
        class="w-5 h-5 text-gray-600 absolute left-2 top-1.5 cursor-pointer"
        @click="state.customer = null"
      />
    </UFormGroup>

    <UFormGroup :label="$t('DESCRIPTION')" name="description">
      <UTextarea v-model="state.description" class="rounded-input" />
    </UFormGroup>

    <UFormGroup :label="$t('INFOS')" name="infos">
      <UInput v-model="state.infos" class="rounded-input" />
    </UFormGroup>

    <div class="pt-10">
      <UButton
        color="gray"
        class="justify-center text-base rounded mb-3 me-3"
        @click="navigateTo(localeRoute('/requirements').fullPath)"
      >
        {{ $t("BACK") }}
      </UButton>

      <UButton
        color="akq-green"
        type="submit"
        class="justify-center text-base rounded mb-3"
        @click="onSubmit"
      >
        {{ $t("NEXT") }}
      </UButton>
    </div>
  </UForm>
</template>
