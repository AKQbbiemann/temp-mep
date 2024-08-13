import { useAuthStore } from "~/stores/auth";

export default defineNuxtRouteMiddleware(async (to, from) => {
  const authStore = useAuthStore();

  if (!authStore.isLoggedIn) {
    //navigateTo(await authStore.getLoginUrl(), { external: true });
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
