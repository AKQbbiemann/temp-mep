import { defineStore } from "pinia";
import useCustomFetch from "@/composables/useCustomFetch";

const initialState = () => {
  return {
    requirements: {
      title: "",
      type: "",
      owner: "",
      creator: "",
      customer: "",
      description: "",
      infos: "",
      probability: "",
      start_date: "",
      end_date: "",
      start_date_is_strict: "",
      end_date_is_strict: "",
      time_period_description: "",
      state: "",
      company_priority: "",
      company_priority_description: "",
      requested_priority: "",
      requested_priority_description: "",
    },
  };
};

export const useRequirementsStore = defineStore("requirements", {
  state: () => initialState(),

  getters: {},
  actions: {
    async fill() {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch(`/requirements`);
        return data;
      } catch (e) {
        appToast.toastError({
          title: "REQUIREMENTS_LIST_ERROR",
          description: "REQUIREMENTS_LIST_ERROR_DESCRIPTION",
        });
      }
    },
    async getRequirementsWithCustomQuery(query) {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch(
          "/requirements".concat(query ? "?" : "", query)
        );
        return data;
      } catch (e) {
        appToast.toastError({
          title: "REQUIREMENTS_LIST_ERROR",
          description: "REQUIREMENTS_LIST_ERROR_DESCRIPTION",
        });
      }
    },
    async getRequirement(requirementId) {
      const appToast = useAppToast();
      try {
        const { data, cluster_participation } = await useCustomFetch(
          "requirements".concat("/", requirementId)
        );
        return [data, cluster_participation];
      } catch (e) {
        appToast.toastError({
          title: "GET_REQUIREMENT_ERROR",
          description: "GET_REQUIREMENT_ERROR_DESCRIPTION",
        });
      }
    },
    async getPhase(PhaseId) {
      const appToast = useAppToast();
      try {
        const { data, cluster_participation } = await useCustomFetch(
          "requirements".concat("/phase/", PhaseId)
        );
        return [data, cluster_participation];
      } catch (e) {
        appToast.toastError({
          title: "GET_PHASE_ERROR",
          description: "GET_PHASE_ERROR_DESCRIPTION",
        });
      }
    },
    async getDropdownLists() {
      const appToast = useAppToast();
      try {
        const {
          states,
          probabilities,
          types,
          priorities,
          certainty,
          estimation_accuracies,
        } = await useCustomFetch("requirements/dropdown-data");

        return [
          states,
          probabilities,
          types,
          priorities,
          certainty,
          estimation_accuracies,
        ];
      } catch (e) {
        appToast.toastError({
          title: "GET_DROPDOWN_LISTS_ERROR",
          description: "GET_DROPDOWN_LISTS_ERROR_DESCRIPTION",
        });
      }
    },

    async getCustomers() {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch("/customers");
        return data;
      } catch (e) {
        appToast.toastError({
          title: "GET_CUSTOMERS_ERROR",
          description: "GET_CUSTOMERS_ERROR_DESCRIPTION",
        });
      }
    },

    async addOrEdit(requestData, requirement_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      const body = {
        title: requestData.title,
        type: requestData.type,
        owner: requestData.owner.toString(),
        creator: requestData.creator,
        customer: requestData.customer,
        description: requestData.description,
        infos: requestData.infos,
        probability: requestData.probability,
        start_date: requestData.start_date,
        end_date: requestData.end_date,
        start_date_is_strict: requestData.start_date_is_strict,
        end_date_is_strict: requestData.end_date_is_strict,
        time_period_description: requestData.time_period_description,
        state: requestData.state,
        company_priority: requestData.company_priority,
        company_priority_description: requestData.company_priority_description,
        requested_priority: requestData.requested_priority,
        requested_priority_description:
          requestData.requested_priority_description,
      };

      if (requirement_id) {
        body.requirement_id = requirement_id;
      }
      try {
        const data = await useCustomFetch(`/requirements`, {
          method: "POST",
          body: body,
        });

        this.$reset();

        appToast.toastSuccess({
          title: requirement_id
            ? "EDIT_REQUIREMENT_SUCCESS"
            : "ADD_REQUIREMENT_SUCCESS",
          description: requirement_id
            ? "EDIT_REQUIREMENT_SUCCESS_DESCRIPTION"
            : "ADD_REQUIREMENT_SUCCESS_DESCRIPTION",
        });
        navigateTo(localeRoute("/requirements").fullPath);
      } catch (e) {
        appToast.toastError({
          title: requirement_id
            ? "EDIT_REQUIREMENT_ERROR"
            : "ADD_REQUIREMENT_ERROR",
          description: requirement_id
            ? "EDIT_REQUIREMENT_ERROR_DESCRIPTION"
            : "ADD_REQUIREMENT_ERROR_DESCRIPTION",
        });
      }
    },
    async deleteRequirement(requirement_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();
      try {
        await useCustomFetch(`/requirements`, {
          method: "DELETE",
          body: { requirement_id },
        });
        appToast.toastSuccess({
          title: "DELETE_REQUIREMENT_SUCCESS",
          description: "DELETE_REQUIREMENT_SUCCESS_DESCRIPTION",
        });
        navigateTo(localeRoute("/requirements"));
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DELETE_REQUIREMENT_ERROR",
          description: "DELETE_REQUIREMENT_ERROR_DESCRIPTION",
        });
      }
    },
    async addOrEditPhases(requirement_id, requestData, phase_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      const body = {
        requirement_id,
        title: requestData.title,
        start_date: requestData.start_date,
        end_date: requestData.end_date,
        dates_are_strict: requestData.dates_are_strict,
        description: requestData.description,
        probability: requestData.probability,
        state: requestData.state,
        estimation_accuracy: requestData.estimationAccuracy,
        certainty_of_date: requestData.certainty,
        cluster_participation: requestData.cluster_participation,
      };

      if (phase_id) {
        body.phase_id = phase_id;
      }

      try {
        await useCustomFetch(`/requirements/phase`, {
          method: "POST",
          body: body,
        });
        appToast.toastSuccess({
          title: phase_id ? "EDIT_PHASE_SUCCESS" : "ADD_PHASE_SUCCESS",
          description: phase_id
            ? "EDIT_PHASE_SUCCESS_DESCRIPTION"
            : "ADD_PHASE_SUCCESS_DESCRIPTION",
        });
        navigateTo(localeRoute("/requirements".concat("/", requirement_id)));
      } catch (e) {
        appToast.toastError({
          title: phase_id ? "EDIT_PHASE_ERROR" : "ADD_PHASE_ERROR",
          description: phase_id
            ? "EDIT_PHASE_ERROR_DESCRIPTION"
            : "ADD_PHASE_ERROR_DESCRIPTION",
        });
      }
    },
    async deletePhase(phase_id) {
      const appToast = useAppToast();
      try {
        await useCustomFetch(`/requirements/phase`, {
          method: "DELETE",
          body: { phase_id },
        });
        appToast.toastSuccess({
          title: "DELETE_PHASE_SUCCESS",
          description: "DELETE_PHASE_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DELETE_PHASE_ERROR",
          description: "DELETE_PHASE_ERROR_DESCRIPTION",
        });
      }
    },
    async getClusterParticipation(id) {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch(
          "requirements".concat("/phase/participation/", id)
        );
        return data;
      } catch (e) {
        appToast.toastError({
          title: "GET_CLUSTER_PARTICIPATION_ERROR",
          description: "GET_CLUSTER_PARTICIPATION_ERROR_DESCRIPTION",
        });
      }
    },
    async addOrEditClusterParticipation(
      requirement_id,
      phase_id,
      cluster_id,
      cluster_name,
      profile_id,
      profile_name,
      competence_id,
      competence_name,
      load,
      participation_id
    ) {
      const appToast = useAppToast();

      const body = {
        requirement_id,
        phase_id,
        cluster_id,
        cluster_name,
        profile_id,
        profile_name,
        competence_id,
        competence_name,
        load,
      };

      if (participation_id) {
        body.participation_id = participation_id;
      }

      try {
        await useCustomFetch(`/requirements/phase/participation`, {
          method: "POST",
          body: body,
        });
        appToast.toastSuccess({
          title: participation_id
            ? "EDIT_CLUSTER_PARTICIPATION_SUCCESS"
            : "ADD_CLUSTER_PARTICIPATION_SUCCESS",
          description: participation_id
            ? "EDIT_CLUSTER_PARTICIPATION_SUCCESS_DESCRIPTION"
            : "ADD_CLUSTER_PARTICIPATION_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        appToast.toastError({
          title: participation_id
            ? "EDIT_CLUSTER_PARTICIPATION_ERROR"
            : "ADD_CLUSTER_PARTICIPATION_ERROR",
          description: participation_id
            ? "EDIT_CLUSTER_PARTICIPATION_ERROR_DESCRIPTION"
            : "ADD_CLUSTER_PARTICIPATION_ERROR_DESCRIPTION",
        });
      }
    },
    async deleteClusterParticipation(participation_id) {
      const appToast = useAppToast();
      try {
        await useCustomFetch(`/requirements/phase/participation`, {
          method: "DELETE",
          body: { participation_id },
        });
        appToast.toastSuccess({
          title: "DELETE_CLUSTER_PARTICIPATION_SUCCESS",
          description: "DELETE_CLUSTER_PARTICIPATION_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DELETE_CLUSTER_PARTICIPATION_ERROR",
          description: "DELETE_CLUSTER_PARTICIPATION_ERROR_DESCRIPTION",
        });
      }
    },
  },
});
