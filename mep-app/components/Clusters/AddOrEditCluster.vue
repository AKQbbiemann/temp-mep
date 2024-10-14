<script setup>
import { useI18n } from "vue-i18n";
import { defineProps, defineEmits, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { object, string } from "yup";
import { reactive } from "vue";
import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";

const id = useRoute().params;
const emit = defineEmits(["closeDetailsView", "isOpen"]);
const clustersStore = useClustersStore();
const { t } = useI18n();

const isLoading = ref(false);
const cluster = ref();

const props = defineProps({
  type: String,
});

onMounted(async () => {
  if (id.id && props.type !== "add") {
    await getCluster();
  } else {
    cluster.value = {
      name: "",
      description: "",
    };
    state.name = "";
    state.description = "";
  }
});

async function getCluster() {
  try {
    isLoading.value = true;
    cluster.value = toRaw(await clustersStore.getCluster(id.id));
    state.name = cluster.value.name;
    state.description = cluster.value.description;
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}

const schema = object({
  name: string().required(t("THIS_FIELD_IS_REQUIRED")),
  description: string().optional(),
});

const state = reactive({
  name: undefined,
  description: undefined,
});

async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      isLoading.value = true;
      await clustersStore.addOrEdit(
        state.name,
        state.description,
        id.id && props.type !== "add" ? parseInt(id.id) : ""
      );
    } catch (e) {
      console.log(e);
    } finally {
      isLoading.value = false;
      emit("isOpen", false);
    }
  }
}
</script>

<template>
  <div class="">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
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
              {{
                id.id && props.type !== "add"
                  ? $t("EDIT_CLUSTER")
                  : $t("NEW_CLUSTER")
              }}
            </h3>
            <UButton
              color="gray"
              icon="i-heroicons-x-mark-20-solid"
              class="-my-1"
              @click="$emit('isOpen', false)"
            />
          </div>
        </template>

        <UFormGroup label="Name" name="name">
          <UInput v-model="state.name" class="rounded-input" />
        </UFormGroup>

        <UFormGroup label="Beschreibung" name="description">
          <UInput v-model="state.description" class="rounded-input" />
        </UFormGroup>

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
            >
              {{ $t("SAVE") }}
            </UButton>
          </div>
        </template>
      </UCard>
    </UForm>
  </div>
</template>
