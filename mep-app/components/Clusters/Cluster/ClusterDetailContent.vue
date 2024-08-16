<script setup>
const props = defineProps({
  isDetailsCluster: Boolean,
  cluster: Object,
  id: Object,
});

const emit = defineEmits(["editClusterView"]);
</script>

<template>
  <div>
    <section v-if="isDetailsCluster" class="mt-10 ps-2">
      <ClusterBox
        v-if="cluster && cluster.description"
        :data="{
          data: cluster ? cluster.description : '',
          label: $t('DESCRIPTION'),
        }"
      />
    </section>
    <section v-if="!isDetailsCluster">
      <AddOrEditCluster
        :clusterId="id.id"
        @closeDetailsView="emit('editClusterView')"
      >
        <template #cancelBtn>
          <UButton
            type="button"
            color="gray"
            class="justify-center text-base rounded mb-3 me-3"
            @click="emit('editClusterView')"
          >
            {{ $t("CANCEL") }}
          </UButton>
        </template>
      </AddOrEditCluster>
    </section>
  </div>
</template>
