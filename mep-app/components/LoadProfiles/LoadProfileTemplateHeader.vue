<script setup>
const props = defineProps({
  isDetailsProfile: Boolean,
  editLoadProfile: Boolean,
});

const emit = defineEmits(["openAddLoadProfile"]);
</script>

<template>
  <div>
    <section class="ps-2 flex justify-between" v-if="isDetailsProfile">
      <ClusterBox :data="{ data: '', label: $t('LOAD_PROFILE') }" />
      <UButton
        type="button"
        color="akq-green"
        icon="i-heroicons-plus-circle"
        class="justify-center text-base rounded"
        @click="emit('openAddLoadProfile')"
      >
        {{ $t("ADD_LOAD_PROFILE") }}
      </UButton>
    </section>
    <section v-if="!isDetailsProfile">
      <h1 v-if="editLoadProfile" class="text-akq-green text-3xl ps-2">
        {{ $t("EDIT_LOAD_PROFILE") }}
      </h1>
      <h1 v-else class="text-akq-green text-3xl ps-2">
        {{ $t("NEW_LOAD_PROFILE") }}
      </h1>
    </section>
  </div>
</template>
