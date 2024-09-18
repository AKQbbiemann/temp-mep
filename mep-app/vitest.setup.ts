import { config } from "@vue/test-utils";
import { vi } from "vitest";

// Example: Mocking Nuxt 3 composables
vi.mock("#app", () => ({
  useRouter: () => ({ push: vi.fn() }),
  useRoute: () => ({ path: "/" }),
  useAsyncData: () => ({ data: ref({ someData: "value" }) }),
  useFetch: () => ({ data: ref({ someFetch: "value" }) }),
}));

// Example: Global component stub
config.global.stubs = {
  NuxtLink: true,
};
