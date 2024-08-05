import { defineStore } from "pinia";
import useCustomFetch from "@/composables/useCustomFetch";

export const useClustersStore = defineStore("clusters", {
  state: () => ({
    clusters: [],
  }),

  getters: {
    getClustersList: (state) => {
      return state.clusters;
    },
  },
  actions: {
    setClustersList(list) {
      this.clusters = list;
    },
    async addOrEdit(name, description, cluster_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      const body = { name, description };

      if (cluster_id) {
        body.cluster_id = cluster_id;
      }

      try {
        await useCustomFetch(`/clusters`, {
          method: "POST",
          body: body,
        });

        appToast.toastSuccess({
          title: cluster_id ? "EDIT_CLUSTER_SUCCESS" : "ADD_CLUSTER_SUCCESS",
          description: cluster_id
            ? "EDIT_CLUSTER_DESCRIPTION"
            : "ADD_CLUSTER_DESCRIPTION",
        });
        this.fill();
        navigateTo(
          localeRoute(
            cluster_id ? "/clusters".concat("/", cluster_id) : "/clusters"
          )
        );
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: cluster_id ? "EDIT_CLUSTER_ERROR" : "ADD_CLUSTER_ERROR",
          description: cluster_id
            ? "EDIT_CLUSTER_ERROR_DESCRIPTION"
            : "ADD_CLUSTER_ERROR_DESCRIPTION",
        });
      }
    },
    async fill() {
      try {
        const { data } = await useCustomFetch(`clusters`);
        this.setClustersList(data);
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "CLUSTERS_LIST_ERROR",
          description: "CLUSTERS_LIST_ERROR_DESCRIPTION",
        });
      }
    },
    async deleteCluster(cluster_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      try {
        const data = await useCustomFetch(`/clusters`, {
          method: "DELETE",
          body: { cluster_id },
        });
        appToast.toastSuccess({
          title: "DELETE_CLUSTER_MSG_SUCCESS",
          description: "DELETE_CLUSTER_MSG_SUCCESS_DESCRIPTION",
        });
        this.fill();
        navigateTo(localeRoute("/clusters"));
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DELETE_CLUSTER_ERROR",
          description: "DELETE_CLUSTER_ERROR_DESCRIPTION",
        });
      }
    },
    async getCluster(id) {
      try {
        const { data } = await useCustomFetch("clusters".concat("/", id));
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_CLUSTER_ERROR",
          description: "GET_CLUSTER_ERRROR_DESCRIPTION",
        });
      }
    },
    async getFTEsChartData(clusterId, profileId) {
      try {
        const { data } = await useCustomFetch(
          "clusters".concat("/", clusterId, "/profiles/", profileId, "/chart")
        );
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: t("GET_FTES_CHART_ERROR"),
          description: t("GET_FTES_CHART_ERRROR_DESCRIPTION"),
        });
      }
    },
    async addOrEditFTEChanges(
      profile_id,
      clusterId,
      state,
      employee_change_id
    ) {
      const appToast = useAppToast();
      const body = {
        profile_id,
        start_date: state.start_date,
        end_date: state.end_date,
        fte_change: state.fte_change,
        reason: state.reason,
        comprehensive_load: state.comprehensive_load,
        base_load: state.base_load,
        organisation_load: state.organisation_load,
        local_load: state.local_load,
      };
      if (employee_change_id) {
        body.employee_change_id = employee_change_id;
      }
      try {
        await useCustomFetch(
          `/clusters/${clusterId}/profiles/${profile_id}/changes`,
          {
            method: "POST",
            body: body,
          }
        );
        appToast.toastSuccess({
          title: employee_change_id
            ? "EDIT_FTE_CHANGE_SUCCESS"
            : "ADD_FTE_CHANGE_SUCCESS",
          description: employee_change_id
            ? "EDIT_FTE_CHANGE_SUCCESS_DESCRIPTION"
            : "ADD_FTE_CHANGE_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: employee_change_id
            ? "EDIT_FTE_CHANGE_ERROR"
            : "ADD_FTE_CHANGE_ERROR",
          description: employee_change_id
            ? "EDIT_FTE_CHANGE_ERROR_DESCRIPTION"
            : "ADD_FTE_CHANGE_ERROR_DESCRIPTION",
        });
      }
    },
    async getChange(clusterId, profileId, changeId) {
      try {
        const { data } = await useCustomFetch(
          "clusters/".concat(
            clusterId,
            "/profiles/",
            profileId,
            "/changes/",
            changeId
          )
        );
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_CLUSTERS_LOAD_PROFILE_ERROR",
          description: e.message,
        });
      }
    },
    async deleteChange(profile_change_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      try {
        await useCustomFetch(`/clusters/profiles/changes/`, {
          method: "DELETE",
          body: { profile_change_id },
        });
        appToast.toastSuccess({
          title: "DELETE_CLUSTERS_LOAD_PROFILE_MSG_SUCCESS",
          description: "DELETE_CLUSTERS_LOAD_PROFILE_MSG_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DELETE_CLUSTERS_LOAD_PROFILE_ERROR",
          description: e.message,
        });
      }
    },
  },
});
