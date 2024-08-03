import { useAuthStore } from "~/stores/auth";

export default defineNuxtRouteMiddleware(async (to, from) => {
  const authStore = useAuthStore();

  if (!authStore.isLoggedIn) {
    console.log("login");
    //navigateTo(await authStore.getLoginUrl(), { external: true });
    console.log(await authStore.getLoginUrl());

    // return navigateTo("/login");
  }

  if (!authStore.user) {
    return authStore
      .fetchUser()
      .then(() => {
        const userRole = authStore.user?.role || null;
        const requiredRole = to.meta.role;

        if (requiredRole && userRole !== requiredRole) {
          return navigateTo("/unauthorized");
        }
      })
      .catch((e) => {
        console.error(e);
        authStore.logout();
        return navigateTo("/login");
      });
  } else {
    const userRole = authStore.user?.role || null;
    const requiredRole = to.meta.role;

    if (requiredRole && userRole !== requiredRole) {
      return navigateTo("/unauthorized");
    }
  }
});
