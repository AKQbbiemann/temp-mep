<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";

const requirementsStore = useRequirementsStore();

const props = defineProps({
  data: Object,
  currentStep: Number,
});

const emit = defineEmits(["step"]);

const step = ref(1);
const typesList = ref();
const probabilitiesList = ref();
const statesList = ref();
const priority = ref();

onMounted(async () => {
  await getDropdownLists();
});

async function getDropdownLists() {
  const [
    states,
    probabilities,
    types,
    priorities,
    certainty,
    estimationAccuracies,
  ] = await requirementsStore.getDropdownLists();
  if (types) {
    typesList.value = Object.entries(types).map((type) => {
      return {
        shortCode: type[0],
        description: type[1],
      };
    });
  }
  if (probabilities) {
    probabilitiesList.value = Object.entries(probabilities).map(
      (probability) => {
        return {
          key: probability[0],
          value: probability[1],
        };
      }
    );
  }
  if (states) {
    statesList.value = Object.entries(states).map((state) => {
      return {
        key: state[0],
        value: state[1],
      };
    });
  }
  if (priorities) {
    priority.value = Object.entries(priorities).map((priority) => {
      return {
        key: parseInt(priority[0]),
        value: priority[1],
      };
    });
  }
}

// change ste
function changeStep(event) {
  step.value = event;
  emit("step", event);
}
</script>

<template>
  <div class="">
    <div v-if="currentStep === 1 || step === 1">
      <AddOrEditFormStep1
        :typesList="typesList"
        :data="props.data"
        @step="changeStep($event)"
      />
    </div>
    <div v-else-if="currentStep === 2 || step === 2">
      <AddOrEditFormStep2
        :data="props.data"
        :probabilitiesList="probabilitiesList"
        :statesList="statesList"
        :priority="priority"
        @step="changeStep($event)"
      />
    </div>
  </div>
</template>
