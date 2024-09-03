<script setup>
const { t } = useI18n();
const props = defineProps({
  item: Object,
  loadProfiles: Array,
});

const emit = defineEmits("editLoadProfileChange", "deleteLoadProfileFTE");

const editLoadProfileChange = (id, profileId) => {
  emit("editLoadProfileChange", {
    id,
    profileId,
  });
};
</script>

<template>
  <div class="grid grid-cols-4 gap-1 mt-8">
    <div
      v-for="change in props.loadProfiles.find(
        (profile) => profile.id === props.item['fullTimeEmployees'].profileId
      ).profile_changes"
      :key="change.id"
      class="bg-white p-4 rounded m-2 cursor-pointer hover:shadow-lg transition duration-300"
      @click="
        editLoadProfileChange(
          change.id,
          props.item['fullTimeEmployees'].profileId
        )
      "
    >
      <div class="flex flex-col">
        <div>
          <div class="text-sm font-medium truncate">
            {{ t("CHANGED_TO").concat(" : ", change.change || "---") }}
          </div>
          <div class="text-sm font-medium truncate">
            {{ t("REASON").concat(" : ", change.reason) }}
          </div>
          <div class="text-sm font-medium truncate">
            {{ t("FROM").concat(" ", change.start_date) }}
          </div>
          <div class="text-sm font-medium truncate">
            {{ t("TO").concat(" ", change.end_date ? change.end_date : "---") }}
          </div>
          <UDivider class="mt-3 mb-1" />

          <div class="w-full self-end flex justify-between align-middle">
            <UButton
              variant="link"
              :label="t('EDIT')"
              name="i-line-md-edit-twotone-full"
              class="text-akq-green"
              dynamic
              @click="
                editLoadProfileChange(
                  change.id,
                  props.item['fullTimeEmployees'].profileId
                )
              "
            />
            <UDivider orientation="vertical" />
            <UButton
              variant="link"
              :label="t('DELETE')"
              name="i-line-md-document-remove-twotone"
              class="text-akq-red"
              dynamic
              @click.stop="emit('deleteLoadProfileFTE', change.id)"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
