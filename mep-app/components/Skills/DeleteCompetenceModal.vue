<script setup>
import { useSkillsStore } from "@/stores/skills";
const skillsStore = useSkillsStore();
const { t } = useI18n();

const props = defineProps({
  competenceId: Number,
});

const loadingDelete = ref(false);

const emit = defineEmits(["deleteCompetence", "isOpen"]);

async function OnSubmit() {
  try {
    loadingDelete.value = true;
    await skillsStore.deleteCompetence(props.competenceId);
    emit("deleteCompetence");
  } catch (e) {
    console.log(e);
  } finally {
    loadingDelete.value = false;
  }
}

//deleteCluster
</script>
<template>
  <UCard
    :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }"
  >
    <template #header>
      <div class="flex items-center justify-between">
        <h3
          class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
        >
          {{ $t("DELETE_COMPETENCE") }}
        </h3>
        <UButton
          color="gray"
          icon="i-heroicons-x-mark-20-solid"
          class="-my-1"
          @click="$emit('isOpen', false)"
        />
      </div>
    </template>
    <p>{{ $t("DELETE_COMPETENCE_MSG") }}</p>
    <template #footer>
      <div class="flex justify-start">
        <UButton
          type="button"
          color="gray"
          class="justify-center text-base rounded mb-3 me-3"
          rounded
          trailing
          :label="$t('CANCEL')"
          @click="$emit('isOpen', false)"
        />
        <UButton
          type="button"
          color="red"
          class="justify-center text-base rounded mb-3"
          rounded
          trailing
          :label="$t('DELETE')"
          :loading="loadingDelete"
          :disabled="loadingDelete"
          @click="
            $emit('isOpen', false);
            OnSubmit();
          "
        />
      </div>
    </template>
  </UCard>
</template>
