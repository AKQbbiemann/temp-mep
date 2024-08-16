<script setup>
import { useloadProfileStore } from "@/stores/loadProfile";

const loadProfileStore = useloadProfileStore();
const { t } = useI18n();

const props = defineProps({
  id: Object,
  loadProfiles: Array,
  isLoadingCompetences: Boolean,
  tabLoadProfiles: Array,
});

const emit = defineEmits(
  "updateLoadProfiles",
  "editLoadProfile",
  "deleteLoadProfile",
  "deleteLoadProfileCompetence",
  "addLoadProfileFTE",
  "deleteLoadProfileFTE"
);

const todaysFTE = ref([]);
const loadIndex = ref(0);

const loadItems = (item) => {
  return [
    {
      label: "organisation_load",
      percentage: item.organisation_load,
      color: "#32A546",
    },
    {
      label: "base_load",
      percentage: item.base_load,
      color: "#05806d",
    },
    {
      label: "local_load",
      percentage: item.local_load,
      color: "#FFCC00",
    },
    {
      label: "comprehensive_load",
      percentage: item.comprehensive_load,
      color: "#E7324C",
    },
  ];
};

function getLoadProfile(index) {
  loadIndex.value = index;
}

// computed property for get todays FTE value from the array of todaysFTE values for the selected load profile
const getTodaysFTE = (loadProfile) => {
  return todaysFTE.value.find((item) => item.id === loadProfile.id)?.value;
};

async function attachCompetence(competenceId, loadProfileId) {
  try {
    await loadProfileStore.attachCompetence(loadProfileId, competenceId);
    emit("updateLoadProfiles");
  } catch (e) {
    console.log(e);
  }
}

const todaysFTEShow = (id, value) => {
  todaysFTE.value.push({ id, value });
};

const editLoadProfileChange = (id, profileId) => {
  emit("editLoadProfileChange", {
    id,
    profileId,
  });
};
</script>

<template>
  <UTabs
    :items="tabLoadProfiles"
    orientation="horizontal"
    :default-index="loadIndex"
    @change="getLoadProfile"
    :ui="{
      wrapper: 'pt-10 flex items-start flex-col load-profile-tabs',
      list: {
        base: 'relative inline-flex items-center justify-center flex-shrink-0 w-full ui-focus-visible:outline-0 ui-focus-visible:ring-2 ui-focus-visible:ring-primary-500 dark:ui-focus-visible:ring-primary-400 ui-not-focus-visible:outline-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 transition-colors duration-200 ease-out !p-0',
        width: '',
        background: 'bg-white dark:bg-gray-800 ',
        tab: {
          base: 'justify-start',
          active:
            'text-white dark:text-white shadow-none border-2 bg-akq-green border-akq-green dark:border-akq-green justify-center border-b-0',
          inactive:
            'text-gray-600 dark:text-gray-400 shadow-none border-2 border-gray-300 dark:border-gray-300 justify-center border-b-0',
          padding: 'px-3',
          height: 'h-10',
          rounded: 'rounded-b-none rounded-t-md',
        },
      },
    }"
  >
    <template #item="{ item }">
      <div class="sub-container-gray flex flex-col">
        <div class="flex justify-between">
          <LoadProfileSearch
            @newCompetence="attachCompetence($event, item.id)"
          />
          <div class="w-[70px] flex justify-between align-middle">
            <UIcon
              name="i-line-md-edit-twotone-full"
              class="w-6 h-6 icon-edit self-center"
              dynamic
              @click="emit('editLoadProfile', item.id)"
            />
            <UDivider orientation="vertical" />
            <UIcon
              name="i-line-md-document-remove-twotone"
              class="w-6 h-6 icon-delete self-center"
              dynamic
              @click="emit('deleteLoadProfile', item.id)"
            />
          </div>
        </div>
        <div class="self-center flex justify-center w-full">
          <UCarousel
            v-slot="{ item }"
            :items="[
              { loadProfile: item },
              {
                fullTimeEmployees: {
                  fte: item.full_time_employees,
                  profileId: item.id,
                  employeeChanges: item.employee_changes,
                },
              },
            ]"
            :ui="{
              wrapper: 'w-full',
              item: 'w-full',
              container: 'rounded-md flex flex-col w-full',
            }"
          >
            <template v-if="Object.keys(item)[0] === 'loadProfile'">
              <div
                class="flex flex-col gap-4 justify-start align-middle pt-10 w-full"
              >
                <load-profile-competence
                  @newCompetence="
                    attachCompetence($event, item['loadProfile'].id)
                  "
                >
                  <template #competence>
                    <template
                      v-for="competence in item['loadProfile'].competences"
                      :key="competence.id"
                    >
                      <div
                        class="bg-white p-2 rounded flex justify-between border-2 border-gray-300"
                      >
                        <p class="text-sm font-medium truncate pt-0.5">
                          {{ competence.name }}
                        </p>
                        <div class="pt-0.5 cursor-pointer">
                          <UIcon
                            name="i-heroicons-x-mark-20-solid "
                            @click="
                              emit('deleteLoadProfileCompetence', {
                                loadProfileId: item['loadProfile'].id,
                                competenceId: competence.id,
                              })
                            "
                          />
                        </div>
                      </div>
                    </template>
                  </template>
                </load-profile-competence>

                <div v-if="isLoadingCompetences">
                  <cluster-list-skeleton />
                </div>

                <PercentageBar v-else :items="loadItems(item['loadProfile'])" />

                <span class="text-sm pt-4">
                  {{
                    $t("FTE_FOR_TODAY") +
                    ": " +
                    getTodaysFTE(item["loadProfile"])
                  }}
                </span>
              </div>
            </template>
            <div v-else class="border-t-2 mt-8 w-full">
              <div
                class="flex flex-col justify-start align-middle pt-10 w-full"
              >
                <UButton
                  type="button"
                  color="akq-green"
                  icon="i-heroicons-plus-circle"
                  class="justify-center text-base rounded mb-3 break-words"
                  :ui="{
                    link: 'break-words',
                  }"
                  @click="
                    emit(
                      'addLoadProfileFTE',
                      item['fullTimeEmployees'].profileId
                    )
                  "
                >
                  <span class="text-wrap truncate">{{
                    $t("NEW_CHANGE_FTES")
                  }}</span>
                </UButton>

                <FullTimeEmployeesChart
                  :clusterId="props.id.id"
                  :profileId="item['fullTimeEmployees'].profileId"
                  @todaysFTE="
                    todaysFTEShow(item['fullTimeEmployees'].profileId, $event)
                  "
                ></FullTimeEmployeesChart>
                <div>
                  <div v-if="item['fullTimeEmployees'].fte">
                    <div class="grid grid-cols-4 gap-1 mt-8">
                      <div
                        v-for="change in props.loadProfiles.find(
                          (profile) =>
                            profile.id === item['fullTimeEmployees'].profileId
                        ).profile_changes"
                        :key="change.id"
                        class="bg-white p-4 rounded m-2 cursor-pointer hover:shadow-lg transition duration-300"
                        @click="
                          editLoadProfileChange(
                            change.id,
                            item['fullTimeEmployees'].profileId
                          )
                        "
                      >
                        <div class="flex flex-col">
                          <div>
                            <div class="text-sm font-medium truncate">
                              {{
                                t("CHANGED_TO").concat(
                                  " : ",
                                  change.change || "---"
                                )
                              }}
                            </div>
                            <div class="text-sm font-medium truncate">
                              {{ t("REASON").concat(" : ", change.reason) }}
                            </div>
                            <div class="text-sm font-medium truncate">
                              {{ t("FROM").concat(" ", change.start_date) }}
                            </div>
                            <div class="text-sm font-medium truncate">
                              {{
                                t("TO").concat(
                                  " ",
                                  change.end_date ? change.end_date : "---"
                                )
                              }}
                            </div>
                            <UDivider class="mt-3 mb-1" />

                            <div
                              class="w-full self-end flex justify-between align-middle"
                            >
                              <UButton
                                variant="link"
                                :label="t('EDIT')"
                                name="i-line-md-edit-twotone-full"
                                class="text-akq-green"
                                dynamic
                                @click="
                                  editLoadProfileChange(
                                    change.id,
                                    item['fullTimeEmployees'].profileId
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
                                @click.stop="
                                  emit('deleteLoadProfileFTE', change.id)
                                "
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-else>
                    <div class="flex justify-center align-middle py-2">
                      <UIcon
                        name="i-heroicons-exclamation-circle "
                        class="w-6 h-6 pt-2 me-2 text-gray-600"
                      />
                      <span class="text-sm italic text-gray-600">
                        {{ t("FTE_INITIAL_VALUE_MSG") }}</span
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </UCarousel>
        </div>
      </div>
    </template>
  </UTabs>
</template>
