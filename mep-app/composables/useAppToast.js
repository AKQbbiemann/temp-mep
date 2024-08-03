export const useAppToast = () => {
  const { $i18n } = useNuxtApp();
  const t = $i18n.t;
  const toast = useToast();

  return {
    toastSuccess: ({ title, description = null }) => {
      toast.add({
        title: t(title),
        description: t(description),
        icon: "i-heroicons-check-circle",
        color: "green",
      });
    },
    toastError: ({ title, description = null }) => {
      toast.add({
        title: t(title),
        description: t(description),
        icon: "i-heroicons-exclamation-circle",
        color: "red",
      });
    },
  };
};
