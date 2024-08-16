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
const competenceId = ref();
const FTEProfileId = ref();
const changeId = ref();
const list = ref();

let tabLoadProfiles = [];

onMounted(async () => {
  await updateData();
});

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

const openAddLoadProfileEvent = () => {
  isOpenAddLoadProfile.value = true;
  editLoadProfile.value = false;
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
          <ClusterDetailHeader
            :isDetailsCluster="isDetailsCluster"
            :cluster="cluster"
            :id="id"
            @editClusterView="editClusterView"
          />
        </template>
        <template #content>
          <ClusterDetailContent
            :isDetailsCluster="isDetailsCluster"
            :cluster="cluster"
            :id="id"
            @editClusterView="editClusterView"
          />
        </template>
      </ClusterDetails>
      <div v-if="showLoadProfileDetails">
        <UDivider class="my-10" />
        <LoadProfileTemplate>
          <template #header>
            <LoadProfileTemplateHeader
              :isDetailsProfile="isDetailsProfile"
              :editLoadProfile="editLoadProfile"
              @openAddLoadProfile="openAddLoadProfileEvent"
            />
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
