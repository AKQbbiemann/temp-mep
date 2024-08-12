<script setup>
definePageMeta({
  middleware: "guest",
});

import { object, string } from "yup";
import { reactive } from "vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const { t } = useI18n();

const loading = ref(false);

const schema = object({
  email: string()
    .email(t("THIS_FIELD_MUST_BE_AN_EMAIL"))
    .required(t("THIS_FIELD_IS_REQUIRED")),
  password: string()
    .min(8, t("THIS_FIELD_MUST_BE_AT_LEAST_8_CHARACTERS"))
    .required(t("THIS_FIELD_IS_REQUIRED")),
});

const state = reactive({
  email: "admin@example.com",
  password: "password",
});

const onSubmit = async () => {
  if (schema.validateSync(state)) {
    try {
      loading.value = true;
      await authStore.login(state.email, state.password);
    } catch (e) {
      console.log(e);
    } finally {
      loading.value = false;
    }
  }
};
</script>

<template>
  <div class="container max-w-md mx-auto my-10">
    <h1 class="mb-4 text-2xl font-bold">{{ $t("LOGIN") }}</h1>

    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <UFormGroup :label="$t('EMAIL')" name="email">
        <UInput
          v-model="state.email"
          icon="i-heroicons-envelope"
          color="akq-green"
        />
      </UFormGroup>

      <UFormGroup :label="$t('PASSWORD')" name="password">
        <UInput
          v-model="state.password"
          type="password"
          icon="i-heroicons-key"
          color="akq-green"
        />
      </UFormGroup>
      <div class="pt-4">
        <UButton type="submit" color="akq-green" :loading="loading" block>
          {{ $t("LOGIN") }}
        </UButton>
      </div>
    </UForm>
  </div>
</template>
