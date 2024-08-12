<script setup>
definePageMeta({
  layout: "clusters",
});
</script>

<template>
  <div class="sub-container ms-3 flex-1">
    <h1 class="pt-3 text-larg text-akq-green">{{ $t("NEW_CLUSTER") }}</h1>
    <add-or-edit-cluster>
      <template #cancelBtn>
        <UButton
          type="button"
          color="gray"
          class="justify-center text-base rounded mb-3 me-3"
          @click="navigateTo(localeRoute('/clusters').fullPath)"
        >
          {{ $t("CANCEL") }}
        </UButton>
      </template>
    </add-or-edit-cluster>
  </div>
</template>
