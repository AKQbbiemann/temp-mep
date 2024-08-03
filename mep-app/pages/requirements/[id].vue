<script setup>
import { reactive } from "vue";
import { toRaw } from "vue";
import { useRequirementsStore } from "@/stores/requirements";
import { useRouter } from "vue-router";
import { format, parseISO } from "date-fns";

const router = useRouter();
const requirementsStore = useRequirementsStore();
const { t } = useI18n();
const localeRoute = useLocaleRoute();
const id = useRoute().params;

const isLoading = ref(false);
const requirement = ref();
const totalData = ref([]);
const requirementData = ref([]);
const ChartData = ref();
const phases = ref();
const tabPhases = ref();
const isPhasePage = ref(false);
const requirementHasNoPhasesYet = ref(true);
const isOpenDeleteRequirement = ref(false);
const isOpenDeletePhase = ref(false);
const deletedPhase = ref();
let dataLabels = [];
let dataValues = [];
let isEditRequirement = ref(false);
const step = ref(1);
onMounted(async () => {
  await getReqirement();
});
async function getReqirement() {
  try {
    isLoading.value = true;

    totalData.value = toRaw(await requirementsStore.getRequirement(id.id));

    requirementData.value = totalData.value[0];
    ChartData.value = totalData.value[1];
    dataLabels = Object.keys(ChartData.value);
    dataValues = Object.values(ChartData.value);
    phases.value = requirementData.value.phases;

    tabPhases.value = phases.value.map((item) => {
      let newItem = Object.assign(item, { label: item.title });
      return newItem;
    });
  } catch (e) {
    console.log(e);
  } finally {
    isLoading.value = false;
    if (isLoading.value === false && phases.value.length > 0) {
      requirementHasNoPhasesYet.value = false;
    } else if (isLoading.value === false && phases.value.length === 0) {
      requirementHasNoPhasesYet.value = true;
    }
  }
}

function changeStep(event) {
  step.value = event;
}
</script>

<template>
  <div class="flex m-4">
    <div
      v-if="isEditRequirement"
      class="sub-container w-[425px] min-h-screen me-4"
    >
      <AddEditProgressSteps isEdit="true" :step="step" />
    </div>
    <div v-if="isEditRequirement" class="sub-container flex-1">
      <AddOrEditForm
        :data="requirementData"
        @step="changeStep($event)"
        @updateRequirement="
          getReqirement();
          isEditRequirement = false;
        "
      >
        <template #cancelBtn>
          <UButton
            type="button"
            color="gray"
            class="justify-center text-base rounded mb-3 me-3"
            @click="isEditRequirement = false"
          >
            {{ $t("CANCEL") }}
          </UButton>
        </template>
      </AddOrEditForm>
    </div>
    <base-data v-if="!isEditRequirement" :data="id.id">
      <template #editDeleteBtns>
        <div class="w-[70px] flex justify-between align-middle">
          <UIcon
            name="i-line-md-edit-twotone-full"
            class="w-6 h-6 icon-edit self-center"
            dynamic
            @click="isEditRequirement = true"
          />
          <UDivider orientation="vertical" />
          <UIcon
            name="i-line-md-document-remove-twotone"
            class="w-6 h-6 icon-delete self-center"
            dynamic
            @click="isOpenDeleteRequirement = true"
          />
          <UModal v-model="isOpenDeleteRequirement" prevent-close>
            <div class="p-4 bg-akq-green-50">
              <delete-requirement-modal
                :id="id"
                @isOpen="isOpenDeleteRequirement = $event"
              ></delete-requirement-modal>
            </div>
          </UModal>
        </div>
      </template>
    </base-data>
    <div v-if="!isEditRequirement" class="grow flex min-h-screen">
      <div v-if="isPhasePage" class="grow flex flex-col">
        <NuxtPage
          @showRequirementDetails="
            getReqirement();
            isPhasePage = false;
          "
        />
      </div>
      <div v-else class="flex grow items-stretch">
        <div v-if="!isEditRequirement" class="flex flex-col flex-1 grow">
          <div class="sub-container min-h-[400px] mb-4">
            <title-box
              :data="{
                data: '',
                label: $t('CLUSTERS_PARTICIPATING'),
              }"
            ></title-box>
            <section class="flex justify-evenly">
              <div class="w-1/3">
                <div v-if="isLoading">loading</div>
                <div v-else>
                  <div
                    v-if="requirementHasNoPhasesYet"
                    class="flex justify-center align-middle mt-20"
                  >
                    <div class="flex justify-start align-middle py-2">
                      <UIcon
                        name="i-heroicons-exclamation-circle "
                        class="w-6 h-6 pt-2 me-2 text-gray-600"
                      />
                      <span class="text-sm italic text-gray-600">
                        {{ $t("NO_PHASES") }}</span
                      >
                    </div>
                  </div>
                  <div v-else>
                    <cluster-participation-chart
                      :dataValues="dataValues"
                      :dataLabels="dataLabels"
                    />
                  </div>
                </div>
              </div>
              <section>
                <div v-if="isLoading">loading</div>
                <div v-else>
                  <div>
                    <cluster-participation-boxes :data="ChartData" />
                  </div>
                </div>
              </section>
            </section>
          </div>
          <div class="sub-container flex-1">
            <title-box
              :data="{
                data: '',
                label: $t('REQUIREMENTS_PHASES'),
              }"
            ></title-box>

            <section>
              <UButton
                type="button"
                color="akq-green"
                icon="i-heroicons-plus-circle"
                class="justify-center text-base rounded mt-10"
                @click="
                  isPhasePage = true;
                  navigateTo(
                    localeRoute('/requirements/'.concat(id.id, '/phases/new'))
                  );
                "
              >
                {{ $t("NEW_PHASE") }}
              </UButton>
              <div v-if="isLoading">loading</div>
              <div v-else>
                <div
                  v-if="requirementHasNoPhasesYet"
                  class="flex justify-center align-middle mt-20"
                >
                  <div class="flex justify-start align-middle py-2">
                    <UIcon
                      name="i-heroicons-exclamation-circle "
                      class="w-6 h-6 pt-2 me-2 text-gray-600"
                    />
                    <span class="text-sm italic text-gray-600">
                      {{ $t("NO_PHASES") }}</span
                    >
                  </div>
                </div>
                <div v-else>
                  <UTabs
                    :items="tabPhases"
                    orientation="vertical"
                    @change="getLoadProfile"
                    :ui="{
                      wrapper: 'pt-2 flex ',
                      list: {
                        base: '',
                        width: 'w-48',
                        background: 'bg-white dark:bg-gray-800 ',
                        tab: {
                          base: 'justify-start ',
                          active: 'text-akq-green dark:text-white shadow-none',
                        },
                      },
                    }"
                  >
                    <template #item="{ item }">
                      <div
                        class="sub-container-gray md:w-[600px] absolute right-0 top-[-100px]"
                      >
                        <div class="px-3">
                          <div class="flex justify-end">
                            <div
                              class="w-[70px] flex justify-between align-middle"
                            >
                              <UIcon
                                name="i-line-md-edit-twotone-full"
                                class="w-6 h-6 icon-edit self-center"
                                dynamic
                                @click="
                                  isPhasePage = true;
                                  navigateTo(
                                    localeRoute(
                                      '/requirements/'.concat(
                                        id.id,
                                        '/phases/',
                                        item.id
                                      )
                                    )
                                  );
                                "
                              />
                              <UDivider orientation="vertical" />
                              <UIcon
                                name="i-line-md-document-remove-twotone"
                                class="w-6 h-6 icon-delete self-center"
                                dynamic
                                @click="
                                  isOpenDeletePhase = true;
                                  deletedPhase = item.id;
                                "
                              />
                            </div>
                          </div>
                          <div
                            class="w-[220px] h-[40px] bg-akq-green ml-[-36px] flex items-center ps-[36px]"
                          >
                            <div
                              class="text-white font-bold w-full truncate triangle"
                            >
                              {{ $t(`${item.state.toUpperCase()}`) }}
                            </div>
                            <div
                              class="w-0 h-0 border-[20px] border-transparent translate-x-5 border-l-akq-green border-r-0"
                            ></div>
                          </div>
                          <div class="mt-10 pb-10">
                            <h3
                              class="font-bold text-akq-green text-center mb-10 text-xl"
                            >
                              {{ item.title }}
                            </h3>
                            <section>
                              <div class="pb-2">
                                <span class="pe-2"> {{ $t("FROM") }} </span>
                                <span class="pe-2">
                                  {{
                                    format(
                                      parseISO(item.start_date),
                                      "yyyy-MM-dd"
                                    )
                                  }}
                                </span>
                                <span class="pe-2"> {{ $t("TO") }} </span>
                                <span>
                                  {{
                                    format(
                                      parseISO(item.end_date),
                                      "yyyy-MM-dd"
                                    )
                                  }}
                                </span>
                              </div>
                              <div class="pb-2">
                                <div
                                  v-for="cluster in item.cluster_participations"
                                  :key="cluster.id"
                                  class=""
                                >
                                  <span>
                                    {{ cluster.cluster_name }} -
                                    {{ cluster.profile_name }} -
                                    {{ cluster.load }} PT
                                  </span>
                                </div>
                              </div>
                              <div class="pb-2">
                                {{ $t("CERTAINTY") }} :
                                {{
                                  $t(`${item.certainty_of_date.toUpperCase()}`)
                                }}
                              </div>
                              <div class="pb-2">
                                <span>
                                  {{ $t("PROBABILITY") }} :
                                  {{ $t(`${item.probability.toUpperCase()}`) }}
                                </span>
                              </div>
                              <div class="pb-2">
                                {{ $t("ESTIMATION_ACCURACY") }} :
                                {{
                                  $t(
                                    `${item.estimation_accuracy.toUpperCase()}`
                                  )
                                }}
                              </div>
                            </section>
                          </div>
                        </div>
                      </div>
                    </template>
                  </UTabs>
                  <UModal v-model="isOpenDeletePhase" prevent-close>
                    <div class="p-4 bg-akq-green-50">
                      <delete-phase-modal
                        :id="deletedPhase"
                        @isOpen="isOpenDeletePhase = $event"
                        @updateRequirement="getReqirement()"
                      />
                    </div>
                  </UModal>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
