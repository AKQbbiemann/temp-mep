<script setup>
import { useClustersStore } from "@/stores/clusters";
import { useSkillsStore } from "@/stores/skills";

const clustersStore = useClustersStore();
const skillsStore = useSkillsStore();
const isOpen = ref(false);
const { t } = useI18n();
const list = ref();
const isLoading = ref(false);
const emit = defineEmits(["newCompetence"]);
const selected = ref();

watch(selected, async (newValue, oldValue) => {
  if (newValue !== null) {
    emit("newCompetence", selected.value.id);
  }
  isOpen.value = false;
});
onMounted(async () => {
  await getList();
});

async function getList() {
  try {
    isLoading.value = true;
    list.value = toRaw(await skillsStore.getskillsList).map((item) => {
      let newItem = Object.assign(item, { label: item.name });
      return newItem;
    });
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}
</script>

<template>
  <div>
    <UButton
      :label="$t('ATTACH_COMPETENCE')"
      @click="isOpen = true"
      type="button"
      color="akq-green"
      icon="i-heroicons-link"
      class="justify-center text-base rounded"
      rounded
      block
      :trailing="false"
    />
    <UModal v-model="isOpen">
      <div>
        <UCommandPalette
          v-model="selected"
          nullable
          :placeholder="t('SEARCH')"
          :groups="[{ key: 'list', commands: list }]"
        />
      </div>
    </UModal>
  </div>
</template>
