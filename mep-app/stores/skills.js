import { defineStore } from "pinia";
import useCustomFetch from "@/composables/useCustomFetch";

export const useSkillsStore = defineStore("skills", {
  state: () => ({
    skills: [],
  }),

  getters: {
    getskillsList: (state) => {
      return state.skills;
    },
  },
  actions: {
    setSkillsList(list) {
      this.skills = list;
    },
    async addOrEdit(name, competence_id) {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      const body = { name };

      if (competence_id) {
        body.competence_id = competence_id;
      }
      try {
        await useCustomFetch(`/competences`, {
          method: "POST",
          body: body,
        });

        appToast.toastSuccess({
          title: competence_id ? "EDIT_SKILL_SUCCESS" : "ADD_SKILL_SUCCESS",
          description: competence_id
            ? "EDIT_SKILL_SUCCESS_DESCRIPTION"
            : "ADD_SKILL_SUCCESS_DESCRIPTION",
        });
        this.fill();
        navigateTo(localeRoute("/clusters/skills"));
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: competence_id ? "EDIT_SKILL_ERROR" : "ADD_SKILL_ERROR",
          description: competence_id
            ? "EDIT_SKILL_DESCRIPTION"
            : "ADD_SKILL_DESCRIPTION",
        });
      }
    },
    async fill() {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch(`competences`);
        this.setSkillsList(data);
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_SKILLS_ERROR",
          description: "GET_SKILLS_ERROR_DESCRIPTION",
        });
      }
    },
    async getCompetenc(id) {
      const appToast = useAppToast();
      try {
        const { data } = await useCustomFetch("competences".concat("/", id));
        return data;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_SKILL_ERROR",
          description: "GET_SKILL_ERROR_DESCRIPTION",
        });
      }
    },
  },
});
