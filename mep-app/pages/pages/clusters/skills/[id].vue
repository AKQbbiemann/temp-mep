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
const isLoading = ref(false);

definePageMeta({
  layout: "clusters",
});
const id = useRoute().params;
const competence = ref();

onMounted(async () => {
  await getCompetenc();
});

async function getCompetenc() {
  try {
    isLoading.value = true;
    competence.value = toRaw(await skillsStore.getCompetenc(id.id));
    state.name = competence.value.name;
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}

const schema = object({
  name: string().required(t("THIS_FIELD_IS_REQUIRED")),
});

const state = reactive({
  name: undefined,
});

async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      isLoading.value = true;
      await skillsStore.addOrEdit(state.name, id.id);
    } catch (e) {
      console.log(e);
    } finally {
      isLoading.value = false;
    }
  }
}
</script>

<template>
  <div class="sub-container ms-3 grow">
    <h1 class="pt-3 text-larg text-akq-green">{{ $t("EDIT_COMPETENCE") }}</h1>
    <div class="mt-10">
      <UForm
        :schema="schema"
        :state="state"
        class="space-y-4"
        @submit="onSubmit"
      >
        <UFormGroup label="Name" name="name">
          <UInput v-model="state.name" class="rounded-input" />
        </UFormGroup>

        <UButton
          type="button"
          color="gray"
          class="justify-center text-base rounded mb-3 me-3"
          @click="navigateTo(localeRoute('/clusters/skills').fullPath)"
        >
          {{ $t("CANCEL") }}
        </UButton>
        <UButton
          color="akq-green"
          class="justify-center text-base rounded mb-3"
          type="submit"
          @submit="onSubmit($event)"
        >
          {{ $t("SAVE") }}
        </UButton>
      </UForm>
    </div>
  </div>
</template>
