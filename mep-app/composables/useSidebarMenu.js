export const getSidebarMenu = () => {
  return [
    {
      label: "Dashboard",
      to: "/",
      icon: "i-heroicons-home",
    },
    {
      label: "Cluster",
      to: "/clusters",
      icon: "i-heroicons-square-3-stack-3d",
    },
    {
      label: "LoadProfiles",
      to: "/loadprofiles",
      icon: "i-heroicons-squares-2x2-20-solid",
    },
    {
      label: "Anforderung",
      to: "/requirements",
      icon: "i-heroicons-check-badge",
    },
  ];
};
