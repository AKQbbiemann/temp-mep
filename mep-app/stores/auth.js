import { defineStore } from "pinia";
import useCustomFetch from "@/composables/useCustomFetch";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("token") || null,
    user: localStorage.getItem("user") || null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.user,
    userMe: (state) => JSON.parse(state.user),
    userRole: (state) => (state.user ? state.user.role : null),
  },

  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem("token", token);
    },
    setUser(user) {
      this.user = user;
      localStorage.setItem("user", JSON.stringify(user));
    },
    async login(email, password) {
      try {
        const appToast = useAppToast();
        const localeRoute = useLocaleRoute();

        const data = await useCustomFetch(`/login`, {
          method: "POST",
          body: { email, password },
        });

        this.setToken(data.token);
        this.setUser(data.user);

        appToast.toastSuccess({
          title: "LOGIN_SUCCESS",
          description: "LOGIN_SUCCESS_DESCRIPTION",
        });

        navigateTo(localeRoute("index").fullPath);
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "LOGIN_ERROR",
          description: "LOGIN_ERROR_DESCRIPTION",
        });
      }
    },

    async logout() {
      const appToast = useAppToast();
      const localeRoute = useLocaleRoute();

      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");

      navigateTo(localeRoute({ name: "login" }).fullPath);

      appToast.toastSuccess({
        title: "LOGOUT_SUCCESS",
        description: "LOGOUT_SUCCESS_DESCRIPTION",
      });
    },

    async fetchUser() {
      try {
        const { data } = await useCustomFetch(`/users/me`);

        this.setUser(data);
      } catch (e) {
        this.logout();
      }
    },

    async updateProfile(name) {
      const appToast = useAppToast();

      try {
        const { data } = await useCustomFetch(
          `/users/${JSON.parse(this.user).id}`,
          {
            method: "PUT",
            body: {
              name: name,
            },
          }
        );

        this.setUser(data);

        appToast.toastSuccess({
          title: "PROFILE_UPDATED_SUCCESSFULLY",
          description: "PROFILE_UPDATED_SUCCESSFULLY_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "PROFILE_UPDATED_ERROR",
          description: "PROFILE_UPDATED_ERROR_DESCRIPTION",
        });
      }
    },

    async updatePassword(state) {
      const appToast = useAppToast();

      try {
        await useCustomFetch(`/users/change-password`, {
          method: "POST",
          body: {
            current_password: state.oldPassword,
            new_password: state.newPassword,
            new_password_confirmation: state.confirmPassword,
          },
        });

        appToast.toastSuccess({
          title: "PASSWORD_UPDATED_SUCCESSFULLY",
          description: "PASSWORD_UPDATED_SUCCESSFULLY_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "PASSWORD_UPDATED_ERROR",
          description: "PASSWORD_UPDATED_ERROR_DESCRIPTION",
        });
      }
    },

    async changeAvatar(formData) {
      const appToast = useAppToast();

      try {
        const { avatar } = await useCustomFetch(`/users/avatar`, {
          method: "POST",
          body: formData,
        });

        this.user.avatar = avatar;

        localStorage.setItem("user", JSON.stringify(this.user));

        appToast.toastSuccess({
          title: "AVATAR_UPDATED_SUCCESSFULLY",
          description: "AVATAR_UPDATED_SUCCESSFULLY_DESCRIPTION",
        });
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "AVATAR_UPDATED_ERROR",
          description: "AVATAR_UPDATED_ERROR_DESCRIPTION",
        });
      }
    },

    async getLoginUrl() {
      try {
        const data = await useCustomFetch("auth");
        return data.redirect_url;
      } catch (e) {
        console.log(e);
        appToast.toastError({
          title: "GET_CLUSTER_ERROR",
          description: "GET_CLUSTER_ERRROR_DESCRIPTION",
        });
      }
    },
  },
});
