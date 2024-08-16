<script setup>
const props = defineProps({
  isDetailsCluster: Boolean,
  cluster: Object,
  id: Object,
});

const emit = defineEmits(["editClusterView"]);

const isOpenDeleteCluster = ref(false);
</script>

<template>
  <div>
    <div v-if="isDetailsCluster" class="flex justify-between ps-2">
      <h1 class="text-akq-green text-3xl font-bold">
        {{ cluster ? cluster.name : "" }}
      </h1>
      <div class="w-[70px] flex justify-end align-middle">
        <UIcon
          name="i-line-md-edit-twotone-full"
          class="w-6 h-6 icon-edit self-center"
          dynamic
          @click="emit('editClusterView')"
        />
        <!-- <UDivider orientation="vertical" />
              <UIcon
                name="i-line-md-document-remove-twotone"
                class="w-6 h-6 icon-delete self-center"
                dynamic
                @click="isOpenDeleteCluster = true"
              /> -->
        <template>
          <div>
            <UModal v-model="isOpenDeleteCluster">
              <delete-cluster-modal
                :clusterId="id"
                @isOpen="isOpenDeleteCluster = $event"
              />
            </UModal>
          </div>
        </template>
      </div>
    </div>
    <div v-if="!isDetailsCluster">
      <h1 class="pt-3 text-larg text-akq-green">
        {{ $t("EDIT_CLUSTER") }}
      </h1>
    </div>
  </div>
</template>
