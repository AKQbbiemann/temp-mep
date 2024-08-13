<script setup>
import { object, string, date, boolean, number, array } from "yup";
import { reactive } from "vue";
import { toRaw } from "vue";
import { useClustersStore } from "@/stores/clusters";
import { useloadProfileStore } from "@/stores/loadProfile";
import { useRequirementsStore } from "@/stores/requirements";

import { format, parseISO } from "date-fns";
const { t } = useI18n();

const id = useRoute().params;
const isLoading = ref(false);
const clustersStore = useClustersStore();
const loadProfileStore = useloadProfileStore();
const requirementsStore = useRequirementsStore();

const clustersList = ref();
const selectedCluster = ref();
const loadProfileList = ref();
const selectedProfile = ref();
const loadingList = ref(false);

const emit = defineEmits([
  "addClusterParticipation",
  "isOpen",
  "updateClusterParticipationsView",
]);

const props = defineProps({
  id: Number | String,
  participation: Object,
});

onMounted(async () => {
  await getList();
  if (props.id !== "" && id.idPhase !== undefined) {
    getParticipation();
  } else if (props.participation) {
    state.cluster_name = props.participation.cluster_name;
    state.profile_name = props.participation.profile_name;
    state.load = props.participation.load;
    selectedCluster.value = props.participation.cluster_id;
    getLoadProfiles(props.participation.cluster_id);
  }
});
let data = null;
async function getParticipation() {
  data = toRaw(await requirementsStore.getClusterParticipation(props.id));

  state.cluster_name = data.cluster_name;
  state.profile_name = data.profile_name;
  state.load = data.load;
  selectedCluster.value = data.cluster_id;
  getLoadProfiles(data.cluster_id);
}

const schema = object({
  cluster_name: string().required(t("THIS_FIELD_IS_REQUIRED")),
  profile_name: string().required(t("THIS_FIELD_IS_REQUIRED")),
  load: string().required(t("THIS_FIELD_IS_REQUIRED")),
});

const state = reactive({
  cluster_name: undefined,
  profile_name: undefined,
  load: undefined,
});

watch(selectedCluster, (newVal) => {
  getLoadProfiles(newVal);
  clustersList.value.map((cluster) => {
    if (cluster.id == newVal) {
      state.cluster_name = cluster.name;
    }
  });
});

watch(selectedProfile, (newVal) => {
  loadProfileList.value.map((profile) => {
    if (profile.id == newVal) {
      state.profile_name = profile.name;
    }
  });
});

async function getLoadProfiles(val) {
  try {
    loadingList.value = true;
    loadProfileList.value = await loadProfileStore.getLoadProfiles(val);

    loadingList.value = false;
    if (data) {
      selectedProfile.value = data.profile_id;
    } else if (props.participation) {
      selectedProfile.value = props.participation.profile_id;
    }
  } catch (e) {
    console.log(e);
  } finally {
  }
}
async function getList() {
  try {
    isLoading.value = true;
    clustersList.value = Object.values(toRaw(await clustersStore.fill()));
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
  }
}

function handelAddClusterParticipation() {
  if (id.idPhase) {
    AddOrEditClusterParticipation();
    emit("updateClusterParticipationsView");
  } else {
    onSubmit();
  }
  emit("isOpen", false);
}

async function AddOrEditClusterParticipation() {
  await requirementsStore.addOrEditClusterParticipation(
    id.id,
    id.idPhase,
    selectedCluster.value,
    state.cluster_name,
    selectedProfile.value,
    state.profile_name,
    null,
    null,
    state.load,
    props.id ? props.id : ""
  );
}
function onSubmit() {
  let toSend = state;
  toSend["cluster_id"] = selectedCluster.value;
  toSend["profile_id"] = selectedProfile.value;
  if (props.participation) {
    emit("updateClusterParticipation", toSend);
  } else {
    emit("addClusterParticipation", toSend);
  }
}
</script>
<template>
  <div>
    <UCard
      :ui="{
        ring: '',
        divide: 'divide-y divide-gray-100 dark:divide-gray-800',
        rounded: 'rounded',
      }"
    >
      <template #header>
        <div class="flex items-center justify-between">
          <h3
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
            v-if="props.id"
          >
            {{ $t("EDIT_CLUSTER_PARTICIPATION") }}
          </h3>
          <h3
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
            v-else
          >
            {{ $t("ADD_CLUSTER_PARTICIPATION") }}
          </h3>
          <UButton
            color="gray"
            icon="i-heroicons-x-mark-20-solid"
            class="-my-1"
            @click="$emit('isOpen', false)"
          />
        </div>
      </template>
      <div v-if="isLoading" class="space-y-4">
        <USkeleton class="h-8 w-[430px]" />
        <USkeleton class="h-8 w-[430px]" />
        <USkeleton class="h-8 w-[430px]" />
      </div>
      <div v-else>
        <UForm
          :schema="schema"
          :state="state"
          class="space-y-4"
          @submit="onSubmit"
        >
          <UFormGroup :label="$t('CLUSTERS')" name="cluster_name">
            <USelectMenu
              v-model="selectedCluster"
              :options="clustersList"
              class="rounded-input"
              value-attribute="id"
              option-attribute="name"
            ></USelectMenu>
          </UFormGroup>
          <UFormGroup :label="$t('KOPMETNZEGRUPPEN')" name="profile_name">
            <USelectMenu
              v-model="selectedProfile"
              :options="loadProfileList"
              :loading="loadingList"
              class="rounded-input"
              value-attribute="id"
              option-attribute="name"
            ></USelectMenu>
          </UFormGroup>
          <UFormGroup :label="t('LOAD')" name="load">
            <UInput v-model="state.load" class="rounded-input" type="number" />
          </UFormGroup>
        </UForm>
      </div>
      <template #footer>
        <div class="flex justify-end">
          <UButton
            type="button"
            color="gray"
            class="justify-center text-base rounded mb-3 me-3"
            rounded
            trailing
            :label="$t('CANCEL')"
            @click="$emit('isOpen', false)"
          />
          <div v-if="props.id">
            <UButton
              type="button"
              color="akq-green"
              class="justify-center text-base rounded mb-3"
              rounded
              trailing
              :label="$t('EDIT')"
              @click="
                $emit('isOpen', false);
                handelAddClusterParticipation();
              "
            />
          </div>
          <div v-else>
            <UButton
              type="button"
              color="akq-green"
              class="justify-center text-base rounded mb-3"
              rounded
              trailing
              :label="$t('ADD')"
              @click="handelAddClusterParticipation()"
            />
          </div>
        </div>
      </template>
    </UCard>
  </div>
</template>
