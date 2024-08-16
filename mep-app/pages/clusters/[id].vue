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

onMounted(async () => {
  await updateData();
});
const list = ref();
function updateCluster(id) {
  cluster.load_profiles[0].competence.push(id);
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

const editLoadProfileEvent = (id) => {
  loadProfileId.value = id;
  editLoadProfile.value = true;
  isOpenAddLoadProfile.value = true;
};

const deleteLoadProfileEvent = (id) => {
  loadProfileId.value = id;
  isOpenDeleteLoadProfile.value = true;
};

const deleteLoadProfileCompetenceEvent = (event) => {
  competenceId.value = event.competenceId;
  loadProfileId.value = event.loadProfileId;
  isOpenDeleteCompetence.value = true;
};

const addLoadProfileFTEEvent = (event) => {
  isOpenChangeFTE.value = true;
  changeId.value = "";
  FTEProfileId.value = event;
};

const editLoadProfileChangeEvent = (event) => {
  isOpenChangeFTE.value = true;
  changeId.value = event.id;
  FTEProfileId.value = event.profileId;
};

const deleteLoadProfileFTEEvent = (event) => {
  changeId.value = event;
  isOpenDeleteChange.value = true;
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
            <LoadProfileTabItem
              v-else-if="isDetailsProfile"
              :tabLoadProfiles="tabLoadProfiles"
              :id="id"
              :loadProfiles="loadProfiles"
              :isLoadingCompetences="isLoadingCompetences"
              @editLoadProfile="editLoadProfileEvent($event)"
              @deleteLoadProfile="deleteLoadProfileEvent($event)"
              @deleteLoadProfileCompetence="
                deleteLoadProfileCompetenceEvent($event)
              "
              @addLoadProfileFTE="addLoadProfileFTEEvent($event)"
              @editLoadProfileChange="editLoadProfileChangeEvent($event)"
              @deleteLoadProfileFTE="deleteLoadProfileFTEEvent($event)"
            />
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
