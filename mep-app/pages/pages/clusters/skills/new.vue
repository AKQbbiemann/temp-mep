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

const schema = object({
  name: string().required(t("THIS_FIELD_IS_REQUIRED")),
});

const state = reactive({
  name: undefined,
});

const loading = ref(false);
async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      loading.value = true;
      await skillsStore.addOrEdit(state.name);
    } catch (e) {
      console.log(e);
    } finally {
      loading.value = false;
    }
  }
}
</script>

<template>
  <div class="sub-container ms-3 grow">
    <h1 class="pt-3 text-larg text-akq-green">{{ $t("NEW_COMPETENCE") }}</h1>
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
