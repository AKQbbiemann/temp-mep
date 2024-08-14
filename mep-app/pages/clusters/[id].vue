<script setup>
definePageMeta({
  layout: "clusters",
});

import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";
import { useSkillsStore } from "@/stores/skills";
import { useloadProfileStore } from "@/stores/loadProfile";

const clustersStore = useClustersStore();
const skillsStore = useSkillsStore();
const loadProfileStore = useloadProfileStore();
const { t } = useI18n();

const isOpenDeleteCluster = ref(false);
const isOpenDeleteLoadProfile = ref(false);
const isOpenAddLoadProfile = ref(false);
const isOpenDeleteCompetence = ref(false);
const isDetailsCluster = ref(true);
const showLoadProfileDetails = ref(true);
const editLoadProfile = ref(true);
const isDetailsProfile = ref(true);
const isLoading = ref(false);
const isLoadingCompetences = ref(false);
const id = useRoute().params;
const loadProfileId = ref("");
const cluster = ref();
const loadProfiles = ref();
const isOpenChangeFTE = ref(false);
const isOpenDeleteChange = ref(false);
let tabLoadProfiles = [];
const competenceId = ref();
const FTEProfileId = ref();
const employeeChangeId = ref();
const changeId = ref();
const tabIndex = ref();
const todaysFTE = ref([]);

onMounted(async () => {
  await updateData();
});
const list = ref();
function updateCluster(id) {
  cluster.load_profiles[0].competence.push(id);
}

async function attachCompetence(competenceId, loadProfileId) {
  try {
    await loadProfileStore.attachCompetence(loadProfileId, competenceId);
    await updateCompetences();
  } catch (e) {
    console.log(e);
  } finally {
  }
}

async function deleteCompetence() {
  try {
    await loadProfileStore.detachCompetence(
      parseInt(loadProfileId.value),
      parseInt(competenceId.value)
    );
  } catch (e) {
    console.log(e);
  } finally {
    await updateCompetences();
  }
}

tabLoadProfiles.forEach((loadProfile) => {
  loadProfile.label = loadProfile.name;
});

let loadIndex = ref(0);
function getLoadProfile(index) {
  loadIndex.value = index;
}
async function editClusterView() {
  isDetailsCluster.value = !isDetailsCluster.value;
  showLoadProfileDetails.value = !showLoadProfileDetails.value;
  await updateData();
}

async function updateData() {
  try {
    isLoading.value = true;
    list.value = toRaw(await skillsStore.getskillsList);
    cluster.value = toRaw(await clustersStore.getCluster(id.id));
    loadProfiles.value = cluster.value.load_profiles;
    tabLoadProfiles = loadProfiles.value.map((item) => {
      let newItem = Object.assign(item, { label: item.name });
      return newItem;
    });
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}

async function updateCompetences() {
  try {
    isLoadingCompetences.value = true;
    cluster.value = toRaw(await clustersStore.getCluster(id.id));
    loadProfiles.value = cluster.value.load_profiles;
    tabLoadProfiles = loadProfiles.value.map((item) => {
      let newItem = Object.assign(item, { label: item.name });
      return newItem;
    });
  } catch (e) {
    console.log(e);
  } finally {
    isLoadingCompetences.value = false;
  }
}

const editLoadProfileChange = (id, profileId) => {
  changeId.value = id;
  FTEProfileId.value = profileId;
  isOpenChangeFTE.value = true;
};

const todaysFTEShow = (id, value) => {
  todaysFTE.value.push({ id, value });
};

// computed property for get todays FTE value from the array of todaysFTE values for the selected load profile
const getTodaysFTE = (loadProfile) => {
  return todaysFTE.value.find((item) => item.id === loadProfile.id)?.value;
};

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
</script>

<template>
  <div class="ms-3 px-10 flex-1 sub-container overflow-auto">
    <div v-if="isLoading">
      <ClusterListSkeleton />
    </div>
    <div v-else>
      <ClusterDetails>
        <template #header>
          <div v-if="isDetailsCluster" class="flex justify-between ps-2">
            <h1 class="text-akq-green text-3xl font-bold">
              {{ cluster ? cluster.name : "" }}
            </h1>
            <div class="w-[70px] flex justify-end align-middle">
              <UIcon
                name="i-line-md-edit-twotone-full"
                class="w-6 h-6 icon-edit self-center"
                dynamic
                @click="editClusterView"
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
        </template>
        <template #content>
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
              @closeDetailsView="editClusterView"
            >
              <template #cancelBtn>
                <UButton
                  type="button"
                  color="gray"
                  class="justify-center text-base rounded mb-3 me-3"
                  @click="editClusterView"
                >
                  {{ $t("CANCEL") }}
                </UButton>
              </template>
            </AddOrEditCluster>
          </section>
        </template>
      </ClusterDetails>
      <div v-if="showLoadProfileDetails">
        <UDivider class="my-10" />
        <LoadProfileTemplate>
          <template #header>
            <section class="ps-2 flex justify-between" v-if="isDetailsProfile">
              <ClusterBox :data="{ data: '', label: $t('LOAD_PROFILE') }" />
              <UButton
                type="button"
                color="akq-green"
                icon="i-heroicons-plus-circle"
                class="justify-center text-base rounded"
                @click="
                  isOpenAddLoadProfile = true;
                  editLoadProfile = false;
                "
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
          </template>

          <template #content>
            <div v-if="isLoadingCompetences">
              <cluster-list-skeleton />
            </div>
            <div v-else>
              <section v-if="isDetailsProfile">
                <UTabs
                  :items="tabLoadProfiles"
                  orientation="horizontal"
                  :default-index="loadIndex"
                  @change="getLoadProfile"
                  :ui="{
                    wrapper:
                      'pt-10 flex items-start flex-col load-profile-tabs',
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
                            @click="
                              editLoadProfile = true;
                              loadProfileId = item.id;
                              isOpenAddLoadProfile = true;
                            "
                          />
                          <UDivider orientation="vertical" />
                          <UIcon
                            name="i-line-md-document-remove-twotone"
                            class="w-6 h-6 icon-delete self-center"
                            dynamic
                            @click="
                              isOpenDeleteLoadProfile = true;
                              loadProfileId = item.id;
                            "
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
                          <template
                            v-if="Object.keys(item)[0] === 'loadProfile'"
                          >
                            <div
                              class="flex flex-col gap-4 justify-start align-middle pt-10 w-full"
                            >
                              <load-profile-competence
                                @newCompetence="
                                  attachCompetence(
                                    $event,
                                    item['loadProfile'].id
                                  )
                                "
                              >
                                <template #competence>
                                  <template
                                    v-for="competence in item['loadProfile']
                                      .competences"
                                    :key="competence.id"
                                  >
                                    <div
                                      class="bg-white p-2 rounded flex justify-between border-2 border-gray-300"
                                    >
                                      <p
                                        class="text-sm font-medium truncate pt-0.5"
                                      >
                                        {{ competence.name }}
                                      </p>
                                      <div class="pt-0.5 cursor-pointer">
                                        <UIcon
                                          name="i-heroicons-x-mark-20-solid "
                                          @click="
                                            isOpenDeleteCompetence = true;
                                            loadProfileId =
                                              item['loadProfile'].id;
                                            competenceId = competence.id;
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

                              <PercentageBar
                                v-else
                                :items="loadItems(item['loadProfile'])"
                              />

                              <span class="text-sm pt-4">
                                {{
                                  $t("FTE_FOR_TODAY") +
                                  ": " +
                                  getTodaysFTE(item["loadProfile"])
                                }}
                              </span>

                              <!-- <competence-chart
                                v-else
                                :base_load="item['loadProfile'].base_load"
                                :comprehensive_load="
                                  item['loadProfile'].comprehensive_load
                                "
                                :local_load="item['loadProfile'].local_load"
                                :organisation_load="
                                  item['loadProfile'].organisation_load
                                "
                              ></competence-chart> -->
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
                                  isOpenChangeFTE = true;
                                  changeId = '';
                                  FTEProfileId =
                                    item['fullTimeEmployees'].profileId;
                                  changeId = '';
                                "
                              >
                                <span class="text-wrap truncate">{{
                                  $t("NEW_CHANGE_FTES")
                                }}</span>
                              </UButton>

                              <FullTimeEmployeesChart
                                :clusterId="id.id"
                                :profileId="item['fullTimeEmployees'].profileId"
                                @todaysFTE="
                                  todaysFTEShow(
                                    item['fullTimeEmployees'].profileId,
                                    $event
                                  )
                                "
                              ></FullTimeEmployeesChart>
                              <div>
                                <div v-if="item['fullTimeEmployees'].fte">
                                  <div class="grid grid-cols-4 gap-1 mt-8">
                                    <div
                                      v-for="change in loadProfiles.find(
                                        (profile) =>
                                          profile.id ===
                                          item['fullTimeEmployees'].profileId
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
                                          <div
                                            class="text-sm font-medium truncate"
                                          >
                                            {{
                                              t("CHANGED_TO").concat(
                                                " : ",
                                                change.change || "---"
                                              )
                                            }}
                                          </div>
                                          <div
                                            class="text-sm font-medium truncate"
                                          >
                                            {{
                                              t("REASON").concat(
                                                " : ",
                                                change.reason
                                              )
                                            }}
                                          </div>
                                          <div
                                            class="text-sm font-medium truncate"
                                          >
                                            {{
                                              t("FROM").concat(
                                                " ",
                                                change.start_date
                                              )
                                            }}
                                          </div>
                                          <div
                                            class="text-sm font-medium truncate"
                                          >
                                            {{
                                              t("TO").concat(
                                                " ",
                                                change.end_date
                                                  ? change.end_date
                                                  : "---"
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
                                                  item['fullTimeEmployees']
                                                    .profileId
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
                                                changeId = change.id;
                                                isOpenDeleteChange = true;
                                              "
                                            />
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div v-else>
                                  <div
                                    class="flex justify-center align-middle py-2"
                                  >
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
              </section>
            </div>
          </template>
        </LoadProfileTemplate>
      </div>
      <template>
        <div>
          <UModal v-model="isOpenAddLoadProfile">
            <LoadProfileForm
              @backToOverview="isOpenAddLoadProfile = false"
              @updateLoadProfiles="
                isOpenAddLoadProfile = false;
                updateCompetences();
              "
              @isOpen="isOpenAddLoadProfile = $event"
              :profileId="loadProfileId"
              :isEditLoadProfile="editLoadProfile"
              :clusterId="id.id"
            />
          </UModal>
          <UModal v-model="isOpenDeleteLoadProfile">
            <delete-load-profile-modal
              :loadProfileId="loadProfileId"
              :clusterId="id.id"
              @isOpen="isOpenDeleteLoadProfile = $event"
              @updateLoadProfiles="updateCompetences()"
            />
          </UModal>
          <UModal v-model="isOpenDeleteCompetence">
            <detach-competence-modal
              :loadProfileId="loadProfileId"
              :competenceId="competenceId"
              @isOpen="isOpenDeleteCompetence = $event"
              @deleteCompetence="deleteCompetence()"
            ></detach-competence-modal>
          </UModal>

          <UModal v-model="isOpenChangeFTE">
            <AddOrEditFTEForm
              :profileId="FTEProfileId"
              :clusterId="id.id"
              :employeeChangeId="changeId"
              @isOpen="isOpenChangeFTE = $event"
              @updateView="updateCompetences()"
            />
          </UModal>
          <UModal v-model="isOpenDeleteChange">
            <DeleteFTEForm
              :changeId="changeId"
              @isOpen="isOpenDeleteChange = $event"
              @updateView="updateCompetences()"
            />
          </UModal>
        </div>
      </template>
    </div>
  </div>
</template>
