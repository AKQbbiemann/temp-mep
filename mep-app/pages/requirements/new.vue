<script setup>
const step = ref(1);

function changeStep(event) {
  step.value = event;
}
</script>

<template>
  <div class="flex m-4">
    <div class="sub-container w-[425px] min-h-screen me-4">
      <AddEditProgressSteps :step="step" @step="changeStep($event)" />
    </div>
    <div class="sub-container grow">
      <AddOrEditForm :currentStep="step" @step="changeStep($event)">
        <template #cancelBtn>
          <UButton
            type="button"
            color="gray"
            class="justify-center text-base rounded mb-3 me-3"
            @click="navigateTo(localeRoute('/requirements').fullPath)"
          >
            {{ $t("CANCEL") }}
          </UButton>
        </template>
      </AddOrEditForm>
    </div>
  </div>
</template>
