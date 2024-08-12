<template>
  <div class="sub-container h-full flex-1">
    <AddOrEditPhaseForm
      @updateRequirementsView="$emit('showRequirementDetails')"
    >
      <template #cancelBtn>
        <UButton
          type="button"
          color="gray"
          class="justify-center text-base rounded mb-3 me-3"
          @click="$emit('showRequirementDetails')"
        >
          {{ $t("CANCEL") }}
        </UButton>
      </template>
    </AddOrEditPhaseForm>
  </div>
</template>
