<script setup>
import { object, string } from "yup";
import { reactive } from "vue";
import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";

const id = useRoute().params;
const emit = defineEmits(["closeDetailsView"]);
const clustersStore = useClustersStore();
const { t } = useI18n();

const isLoading = ref(false);
const cluster = ref();

onMounted(async () => {
  await getCluster();
});

async function getCluster() {
  if (id.id) {
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
        id.id ? parseInt(id.id) : ""
      );
    } catch (e) {
      console.log(e);
    } finally {
      isLoading.value = false;
      emit("closeDetailsView");
    }
  }
}
</script>

<template>
  <div class="mt-10">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <UFormGroup label="Name" name="name">
        <UInput v-model="state.name" class="rounded-input" />
      </UFormGroup>

      <UFormGroup label="Beschreibung" name="description">
        <UInput v-model="state.description" class="rounded-input" />
      </UFormGroup>
      <div class="pt-10">
        <slot name="cancelBtn" />
        <UButton
          type="submit"
          color="akq-green"
          class="justify-center text-base rounded mb-3"
          @submit="onSubmit($event)"
        >
          {{ $t("SAVE") }}
        </UButton>
      </div>
    </UForm>
  </div>
</template>
