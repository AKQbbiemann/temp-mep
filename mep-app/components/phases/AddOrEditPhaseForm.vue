<script setup>
import { object, string, date, boolean, number, array } from "yup";
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { format, parseISO } from "date-fns";
const id = useRoute().params;

const emit = defineEmits(["updateRequirementsView"]);
const requirementsStore = useRequirementsStore();
const { t } = useI18n();

const isLoading = ref(false);
const cluster = ref();
const step = ref(1);
const typesList = ref();
const probabilitiesList = ref();
const statesList = ref();
const certaintyList = ref();
const estimationAccuraciesList = ref();
const clusterParticipation = ref([]);
const isOpen = ref(false);
const isOpenDeleteParticipation = ref(false);
const phase = ref();
const clusters = ref([]);
const clusterParticipationId = ref("");
const clustersStore = useClustersStore();
const loadProfileStore = useloadProfileStore();
const selectedIndex = ref(0);
const participation = ref();
const clusterIndex = ref();
const selectedCluster = ref();
const loadProfileList = ref();
const selectedProfile = ref();
const loadingList = ref(false);
const isLoadingClusterParticipation = ref(false);

const schema = object({
  title: string().required(t("THIS_FIELD_IS_REQUIRED")),
  start_date: string().required(t("THIS_FIELD_IS_REQUIRED")),
  end_date: string().required(t("THIS_FIELD_IS_REQUIRED")),
  description: string().required(t("THIS_FIELD_IS_REQUIRED")),
  dates_are_strict: boolean(),
  probability: string().required(t("THIS_FIELD_IS_REQUIRED")),
  state: string().required(t("THIS_FIELD_IS_REQUIRED")),
  certainty: string().required(t("THIS_FIELD_IS_REQUIRED")),
  estimationAccuracy: string().required(t("THIS_FIELD_IS_REQUIRED")),
  cluster_participation: array().min(1, t("THIS_FIELD_IS_REQUIRED")),
});

const state = reactive({
  title: undefined,
  start_date: undefined,
  end_date: undefined,
  description: undefined,
  dates_are_strict: false,
  probability: undefined,
  certainty: undefined,
  estimationAccuracy: undefined,
  state: undefined,
  cluster_participation: [],
});

onMounted(async () => {
  await getDropdownLists();
  if (id.idPhase) {
    try {
      await getPhase();
    } catch {}
  }
});
async function getPhase() {
  try {
    isLoadingClusterParticipation.value = true;
    phase.value = toRaw(await requirementsStore.getPhase(id.idPhase))[0];
    clusters.value = phase.value.cluster_participations;
    state.title = phase.value.title;
    state.start_date = format(parseISO(phase.value.start_date), "yyyy-MM-dd");
    state.end_date = format(parseISO(phase.value.end_date), "yyyy-MM-dd");
    state.dates_are_strict = phase.value.dates_are_strict;
    state.description = phase.value.description;
    state.state = phase.value.state;
    state.probability = phase.value.probability;
    state.certainty = phase.value.certainty_of_date;
    state.estimationAccuracy = phase.value.estimation_accuracy;
    state.cluster_participation = phase.value.cluster_participations;
  } catch {
  } finally {
    isLoadingClusterParticipation.value = false;
  }
}
async function getDropdownLists() {
  try {
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
    if (certainty) {
      certaintyList.value = Object.entries(certainty).map((item) => {
        return {
          key: item[0],
          value: item[1],
        };
      });
    }
    if (estimationAccuracies) {
      estimationAccuraciesList.value = Object.entries(estimationAccuracies).map(
        (estimationAccuracy) => {
          return {
            key: estimationAccuracy[0],
            value: estimationAccuracy[1],
          };
        }
      );
    }
  } catch (e) {
    console.log(e);
  }
}

function setSelectedCluster(id) {
  // console.log(id);
}
async function onSubmit(event) {
  if (schema.validateSync(state)) {
    try {
      isLoading.value = true;
      await requirementsStore.addOrEditPhases(
        id.id,
        state,
        id.idPhase ? id.idPhase : ""
      );
    } catch (e) {
      console.log(e);
    } finally {
      isLoading.value = false;
      emit("updateRequirementsView");
    }
  }
}

async function updateClusterParticipationsView() {
  try {
    await getPhase();
  } catch {}
}

const Participations = ref([]);
function addCluster(cluster) {
  isLoadingClusterParticipation.value = true;
  cluster["requirement_id"] = id.id;
  cluster["competence_id"] = null;
  cluster["competence_name"] = null;
  state.cluster_participation.push(cluster);
  Participations.value.push(cluster);
  isLoadingClusterParticipation.value = false;
  schema.validateSync(state);
}
function updateCluster(cluster) {
  isLoadingClusterParticipation.value = true;
  cluster["requirement_id"] = id.id;
  cluster["competence_id"] = null;
  cluster["competence_name"] = null;
  state.cluster_participation[clusterIndex.value] = cluster;
  Participations.value[clusterIndex.value] = cluster;
  isLoadingClusterParticipation.value = false;
}
function removeClusterFromParticipation() {
  state.cluster_participation.splice(clusterIndex, 1);
  Participations.value.splice(clusterIndex, 1);
}
</script>
<template>
  <div class="mt-10">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <div class="">
        <div class="">
          <UFormGroup label="Title" name="title">
            <UInput v-model="state.title" class="rounded-input" />
          </UFormGroup>
          <div class="flex">
            <div class="w-1/2 pe-2">
              <UFormGroup :label="t('START_DATE')" name="start_date">
                <UInput
                  v-model="state.start_date"
                  class="rounded-input"
                  type="date"
                />
              </UFormGroup>
            </div>
            <div class="w-1/2">
              <UFormGroup :label="t('END_DATE')" name="end_date">
                <UInput
                  v-model="state.end_date"
                  class="rounded-input"
                  type="date"
                />
              </UFormGroup>
            </div>
          </div>

          <UFormGroup name="dates_are_strict">
            <UCheckbox
              class="pt-3"
              v-model="state.dates_are_strict"
              :label="$t('DATES_ARE_STRICT')"
            />
          </UFormGroup>

          <UFormGroup :label="$t('DESCRIPTION')" name="description">
            <UTextarea v-model="state.description" class="rounded-input" />
          </UFormGroup>

          <UFormGroup :label="$t('PROBABILITY')" name="probability">
            <USelectMenu
              v-model="state.probability"
              :options="probabilitiesList"
              class="rounded-input"
              placeholder="Select..."
              value-attribute="key"
              option-attribute="value"
            ></USelectMenu>
          </UFormGroup>

          <UFormGroup :label="$t('STATE')" name="state">
            <USelectMenu
              v-model="state.state"
              :options="statesList"
              class="rounded-input"
              placeholder="Select..."
              value-attribute="key"
              option-attribute="value"
            ></USelectMenu>
          </UFormGroup>

          <UFormGroup :label="$t('CERTAINTY')" name="certainty">
            <USelectMenu
              v-model="state.certainty"
              :options="certaintyList"
              class="rounded-input"
              placeholder="Select..."
              value-attribute="key"
              option-attribute="value"
            ></USelectMenu>
          </UFormGroup>

          <UFormGroup
            :label="$t('estimationAccuracy')"
            name="estimationAccuracy"
          >
            <USelectMenu
              v-model="state.estimationAccuracy"
              :options="estimationAccuraciesList"
              class="rounded-input"
              placeholder="Select..."
              value-attribute="key"
              option-attribute="value"
            ></USelectMenu>
          </UFormGroup>
        </div>
        <UDivider
          class="py-5"
          label="CLuster Beteiligung"
          :ui="{ label: 'text-akq-green font-medium dark:text-primary-400' }"
        />
        <UFormGroup name="cluster_participation">
          <div class="">
            <UButton
              :padded="false"
              color="gray"
              variant="link"
              :label="t('ADD_CLUSTER')"
              icon="i-heroicons-plus-circle-20-solid"
              @click="
                isOpen = true;
                clusterParticipationId = '';
                participation = null;
              "
            />
          </div>
        </UFormGroup>

        <UModal v-model="isOpen">
          <AddClusterParticipationModal
            :id="clusterParticipationId"
            :participation="participation"
            @isOpen="isOpen = $event"
            @addClusterParticipation="addCluster($event)"
            @updateClusterParticipation="updateCluster($event)"
            @updateClusterParticipationsView="updateClusterParticipationsView()"
          />
        </UModal>
        <UModal v-model="isOpenDeleteParticipation">
          <DeleteClusterParticipationModal
            :id="clusterParticipationId"
            @isOpen="isOpenDeleteParticipation = $event"
            @removeClusterFromParticipation="removeClusterFromParticipation()"
            @updateClusterParticipationsView="updateClusterParticipationsView()"
          />
        </UModal>
      </div>
      <div>
        <div v-if="id.idPhase">
          <div v-if="isLoadingClusterParticipation">loading</div>
          <div v-else>
            <div class="flex justify-between" v-if="Participations">
              <div class="relative w-full min-h-64">
                <div
                  v-for="(cluster, index) in clusters"
                  :key="cluster.id"
                  class=" "
                >
                  <div
                    @click="selectedCluster = cluster.id"
                    class="ps-2 py-1 cursor-pointer font-medium"
                    :class="
                      selectedCluster == cluster.id ? 'text-akq-green' : ''
                    "
                  >
                    {{ cluster.cluster_name }} - {{ cluster.profile_name }}
                  </div>
                  <div
                    class="w-1/2 sub-container-gray absolute right-0 top-0"
                    v-if="selectedCluster === cluster.id || index === 0"
                  >
                    <div class="flex justify-end">
                      <div class="w-[70px] flex justify-between align-middle">
                        <UIcon
                          name="i-line-md-edit-twotone-full"
                          class="w-6 h-6 icon-edit self-center"
                          dynamic
                          @click="
                            isOpen = true;
                            clusterParticipationId = cluster.id;
                          "
                        />
                        <UDivider orientation="vertical" />
                        <UIcon
                          name="i-line-md-document-remove-twotone"
                          class="w-6 h-6 icon-delete self-center"
                          dynamic
                          @click="
                            isOpenDeleteParticipation = true;
                            clusterParticipationId = cluster.id;
                          "
                        />
                      </div>
                    </div>
                    <div>
                      <span class="font-medium text-sm pe-2">{{
                        $t("CLUSTER_NAME")
                      }}</span>
                      {{ cluster.cluster_name }}
                    </div>
                    <div>
                      <span class="font-medium text-sm pe-2">{{
                        $t("PROFILE_NAME")
                      }}</span>
                      {{ cluster.profile_name }}
                    </div>
                    <div>
                      <span class="font-medium text-sm pe-2">{{
                        $t("LOAD")
                      }}</span
                      >{{ cluster.load }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <div class="flex justify-between" v-if="Participations">
            <div class="relative w-full min-h-64">
              <div
                v-for="(cluster, index) in Participations"
                :key="index"
                class=" "
              >
                <div
                  @click="selectedIndex = index"
                  class="ps-2 py-1 cursor-pointer font-medium"
                  :class="selectedIndex == index ? 'text-akq-green' : ''"
                >
                  {{ cluster.cluster_name }}
                </div>
                <div
                  class="w-1/2 sub-container-gray absolute right-0 top-0"
                  v-if="selectedIndex === index"
                >
                  <div class="flex justify-end">
                    <div class="w-[70px] flex justify-between align-middle">
                      <UIcon
                        name="i-line-md-edit-twotone-full"
                        class="w-6 h-6 icon-edit self-center"
                        dynamic
                        @click="
                          isOpen = true;
                          clusterParticipationId = '';
                          clusterIndex = index;
                          participation = state.cluster_participation[index];
                        "
                      />
                      <UDivider orientation="vertical" />
                      <UIcon
                        name="i-line-md-document-remove-twotone"
                        class="w-6 h-6 icon-delete self-center"
                        dynamic
                        @click="
                          isOpenDeleteParticipation = true;
                          clusterParticipationId = '';
                          clusterIndex = index;
                          participation = state.cluster_participation[index];
                        "
                      />
                    </div>
                  </div>
                  <div>
                    <span class="font-medium text-sm pe-2">{{
                      $t("CLUSTER_NAME")
                    }}</span>
                    {{ cluster.cluster_name }}
                  </div>
                  <div>
                    <span class="font-medium text-sm pe-2">{{
                      $t("PROFILE_NAME")
                    }}</span>
                    {{ cluster.profile_name }}
                  </div>
                  <div>
                    <span class="font-medium text-sm pe-2">{{
                      $t("LOAD")
                    }}</span
                    >{{ cluster.load }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-10">
        <slot name="cancelBtn" />
        <UButton
          type="submit"
          color="akq-green"
          class="justify-center text-base rounded mb-3"
        >
          {{ $t("SAVE") }}
        </UButton>
      </div>
    </UForm>
  </div>
</template>
