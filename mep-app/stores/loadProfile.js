import { defineStore } from "pinia";
import useCustomFetch from "@/composables/useCustomFetch";

export const useloadProfileStore = defineStore("loadProfile", {
  state: () => ({}),

  getters: {},
  actions: {
    async addOrEdit(
      name,
      comprehensive_load,
      local_load,
      base_load,
      organisation_load,
      full_time_employees,
      cluster_id,
      profile_id
    ) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      const body = {
        name,
        comprehensive_load,
        local_load,
        base_load,
        organisation_load,
        cluster_id,
      };

      if (profile_id) {
        body.profile_id = profile_id;
      }
      if (full_time_employees !== "") {
        body.full_time_employees = full_time_employees;
      }

      try {
        await useCustomFetch(`/clusters/profiles`, {
          method: "POST",
          body: body,
        });
        appToast.toastSuccess({
          title: profile_id ? "EDIT_PROFILE_SUCCESS" : "ADD_PROFILE_SUCCESS",
          description: profile_id
            ? "EDIT_PROFILE_SUCCESS_DESCRIPTION"
            : "ADD_PROFILE_SUCCESS_DESCRIPTION",
        });
        navigateTo(localeRoute("/clusters".concat("/", cluster_id)));
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: profile_id ? "EDIT_PROFILE_ERROR" : "ADD_PROFILE_ERROR",
          description: profile_id
            ? "EDIT_PROFILE_ERROR_DESCRIPTION"
            : "ADD_PROFILE_ERROR_DESCRIPTION",
        });
      }
    },

    async attachCompetence(load_profile_id, competence_id) {
      const appToast = useAppToast();

      try {
        await useCustomFetch(`/competences/profiles`, {
          method: "PUT",
          body: { load_profile_id, competence_id },
        });

        appToast.toastSuccess({
          title: "ATTACH_COMPETENCE_SUCCESS",
          description: "ATTACH_COMPETENCE_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "ATTACH_COMPETENCE_ERROR",
          description: "ATTACH_COMPETENCE_ERROR_DESCRIPTION",
        });
      }
    },

    async detachCompetence(load_profile_id, competence_id) {
      const appToast = useAppToast();
      try {
        await useCustomFetch(`/competences/profiles`, {
          method: "DELETE",
          body: { load_profile_id, competence_id },
        });

        appToast.toastSuccess({
          title: "DETACH_COMPETENCE_SUCCESS",
          description: "DETACH_COMPETENCE_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DETACH_COMPETENCE_ERROR",
          description: "DETACH_COMPETENCE_ERROR_DESCRIPTION",
        });
      }
    },

    async getLoadProfile(clusterId, profileId) {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch(
          "clusters".concat("/", clusterId, "/profiles/", profileId)
        );
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_LOADPROFILE_ERROR",
          description: "GET_LOADPROFILE_ERROR_DESCRIPTION",
        });
      }
    },

    async deleteLoadProfile(profile_id) {
      const appToast = useAppToast();
      try {
        await useCustomFetch(`/clusters/profiles`, {
          method: "DELETE",
          body: { profile_id },
        });
        appToast.toastSuccess({
          title: "DELETE_LOADPROFILE_SUCCESS",
          description: "DELETE_LOADPROFILE_SUCCESS_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "DELETE_LOADPROFILE_ERROR",
          description: "DELETE_LOADPROFILE_ERROR_DESCRIPTION",
        });
      }
    },

    async getLoadProfiles(clusterId) {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch(
          "clusters".concat("/", clusterId, "/profiles")
        );
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_LOADPROFILES_ERROR",
          description: "GET_LOADPROFILES_ERROR_DESCRIPTION",
        });
      }
    },

    async getLoadProfiles() {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch("clusters".concat("/profiles"));
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_LOADPROFILES_ERROR",
          description: "GET_LOADPROFILES_ERROR_DESCRIPTION",
        });
      }
    },
  },
});
