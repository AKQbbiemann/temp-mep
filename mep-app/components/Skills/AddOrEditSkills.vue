<script setup>
import { object, string } from "yup";
import { reactive } from "vue";
import { toRaw } from "vue";
import { useSkillsStore } from "@/stores/skills";
import { useRouter } from "vue-router";

const router = useRouter();
const skillsStore = useSkillsStore();
const { t } = useI18n();
const localeRoute = useLocaleRoute();

definePageMeta({
  layout: "clusters",
});

const props = defineProps({
  id: String,
});
const emit = defineEmits(["isOpen"]);

const schema = object({
  name: string().required(t("THIS_FIELD_IS_REQUIRED")),
});

const state = reactive({
  name: undefined,
});

const loading = ref(false);
const competence = ref();
const isLoading = ref(false);

onMounted(async () => {
  if (props.id) {
    await getCompetenc();
  } else {
    competence.value = {
      name: "",
    };
  }
});

async function getCompetenc() {
  try {
    isLoading.value = true;
    competence.value = toRaw(await skillsStore.getCompetenc(props.id));
    state.name = competence.value.name;
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}

async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      loading.value = true;
      await skillsStore.addOrEdit(
        state.name,
        props.id ? parseInt(props.id) : ""
      );
    } catch (e) {
      console.log(e);
    } finally {
      loading.value = false;
      emit("isOpen", false);
    }
  }
}
</script>

<template>
  <div>
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
              {{ props.id ? $t("EDIT_COMPETENCE") : $t("NEW_COMPETENCE") }}
            </h3>
            <UButton
              color="gray"
              icon="i-heroicons-x-mark-20-solid"
              class="-my-1"
              @click="$emit('isOpen', false)"
            />
          </div>
        </template>

        <div class="">
          <UFormGroup label="Name" name="name">
            <UInput v-model="state.name" class="rounded-input" />
          </UFormGroup>
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
              :disabled="loading"
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
