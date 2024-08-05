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
            <div class="w-[70px] flex justify-between align-middle">
              <UIcon
                name="i-line-md-edit-twotone-full"
                class="w-6 h-6 icon-edit self-center"
                dynamic
                @click="editClusterView"
              />
              <UDivider orientation="vertical" />
              <UIcon
                name="i-line-md-document-remove-twotone"
                class="w-6 h-6 icon-delete self-center"
                dynamic
                @click="isOpenDeleteCluster = true"
              />
              <template>
                <div>
                  <UModal v-model="isOpenDeleteCluster" prevent-close>
                    <div class="p-4 bg-akq-green-50">
                      <delete-cluster-modal
                        :clusterId="id"
                        @isOpen="isOpenDeleteCluster = $event"
                      />
                    </div>
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
                class="justify-center text-base rounded mb-3"
                @click="
                  isDetailsProfile = !isDetailsProfile;
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
                  orientation="vertical"
                  :default-index="loadIndex"
                  @change="getLoadProfile"
                  :ui="{
                    wrapper: 'pt-10  px-4 flex items-start flex-col gap-4',
                    list: {
                      base: '',
                      width: 'w-48',
                      background: 'bg-white dark:bg-gray-800 ',
                      tab: {
                        base: 'justify-start',
                        active: 'text-akq-green dark:text-white shadow-none',
                      },
                    },
                  }"
                >
                  <template #item="{ item }">
                    <div class="sub-container-gray flex flex-col">
                      <div class="flex justify-between">
                        <div class="text-akq-green text-xl">
                          {{ item.name }}
                        </div>
                        <div class="w-[70px] flex justify-between align-middle">
                          <UIcon
                            name="i-line-md-edit-twotone-full"
                            class="w-6 h-6 icon-edit self-center"
                            dynamic
                            @click="
                              isDetailsProfile = !isDetailsProfile;
                              editLoadProfile = true;
                              loadProfileId = item.id;
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
                            container: 'rounded-lg flex flex-col w-full',
                          }"
                        >
                          <div v-if="Object.keys(item)[0] === 'loadProfile'">
                            <div
                              class="flex justify-start align-middle pt-10 pl-4"
                            >
                              <div v-if="isLoadingCompetences">
                                <cluster-list-skeleton />
                              </div>

                              <competence-chart
                                v-else
                                :base_load="item['loadProfile'].base_load"
                                :comprehensive_load="
                                  item['loadProfile'].comprehensive_load
                                "
                                :local_load="item['loadProfile'].local_load"
                                :organisation_load="
                                  item['loadProfile'].organisation_load
                                "
                              ></competence-chart>

                              <div class="pl-2">
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
                                        class="bg-white p-2 rounded m-2 flex justify-between"
                                      >
                                        <p class="text-sm font-medium truncate">
                                          {{ competence.name }}
                                        </p>
                                        <div>
                                          <UIcon
                                            name="i-heroicons-x-mark-20-solid"
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
                              </div>
                            </div>
                          </div>
                          <div v-else class="border-t-2 mt-12 w-full">
                            <div
                              class="flex justify-start align-middle pt-10 px-6 w-full"
                            >
                              <FullTimeEmployeesChart
                                :clusterId="id.id"
                                :profileId="item['fullTimeEmployees'].profileId"
                              ></FullTimeEmployeesChart>
                              <div>
                                <div v-if="item['fullTimeEmployees'].fte">
                                  <UButton
                                    type="button"
                                    color="akq-green"
                                    icon="i-heroicons-plus-circle"
                                    class="justify-center text-base rounded mb-3 break-words ml-2"
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
                                  <div class="grid grid-cols-3 gap-1">
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
                                                change.change
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
                                    class="flex justify-start align-middle py-2"
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
            <section v-if="!isDetailsProfile">
              <LoadProfileForm
                @backToOverview="isDetailsProfile = !isDetailsProfile"
                @updateLoadProfiles="
                  isDetailsProfile = !isDetailsProfile;
                  updateCompetences();
                "
                :profileId="loadProfileId"
                :isEditLoadProfile="editLoadProfile"
                :clusterId="id.id"
              />
            </section>
          </template>
        </LoadProfileTemplate>
      </div>
      <template>
        <div>
          <UModal v-model="isOpenDeleteLoadProfile" prevent-close>
            <div class="p-4 bg-akq-green-50">
              <delete-load-profile-modal
                :loadProfileId="loadProfileId"
                :clusterId="id.id"
                @isOpen="isOpenDeleteLoadProfile = $event"
                @updateLoadProfiles="updateCompetences()"
              />
            </div>
          </UModal>
          <UModal v-model="isOpenDeleteCompetence" prevent-close>
            <div class="p-4 bg-akq-green-50">
              <detach-competence-modal
                :loadProfileId="loadProfileId"
                :competenceId="competenceId"
                @isOpen="isOpenDeleteCompetence = $event"
                @deleteCompetence="deleteCompetence()"
              ></detach-competence-modal>
            </div>
          </UModal>

          <UModal v-model="isOpenChangeFTE" prevent-close>
            <div class="p-4 bg-akq-green-50">
              <AddOrEditFTEForm
                :profileId="FTEProfileId"
                :clusterId="id.id"
                :employeeChangeId="changeId"
                @isOpen="isOpenChangeFTE = $event"
                @updateView="updateCompetences()"
              ></AddOrEditFTEForm>
            </div>
          </UModal>
          <UModal v-model="isOpenDeleteChange" prevent-close>
            <div class="p-4 bg-akq-green-50">
              <DeleteFTEForm
                :employeeChangeId="changeId"
                @isOpen="isOpenDeleteChange = $event"
                @updateView="updateCompetences()"
              />
            </div>
          </UModal>
        </div>
      </template>
    </div>
  </div>
</template>
