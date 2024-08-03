// https://nuxt.com/docs/api/configuration/nuxt-config

export default defineNuxtConfig({
  ssr: false,
  spaLoadingTemplate: true, // per default disabled since Nuxt 3.7
  devtools: { enabled: true },
  modules: [
    "@nuxt/ui",
    "@pinia/nuxt",
    "@nuxtjs/i18n",
    "@nuxtjs/color-mode",
    "@nuxtjs/tailwindcss",
  ],
  components: [{ path: "~/components", pathPrefix: false }],
  router: {
    options: {
      linkActiveClass: "active",
      linkExactActiveClass: "exact-active",
    },
  },
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.API_BASE_URL,
      apiVersion: process.env.API_VERSION,
    },
  },
  colorMode: {
    classSuffix: "",
  },
  css: ["~/assets/css/main.scss", "~/assets/css/tailwind.css"],
  i18n: {
    vueI18n: "./i18n.config.ts",
    locales: [
      { code: "en", iso: "en-US", name: "English", file: "en.json" },
      { code: "de", iso: "de-DE", name: "German", file: "de.json" },
    ],
    defaultLocale: "de",
    lazy: true,
    langDir: "locales/",
  },
});
