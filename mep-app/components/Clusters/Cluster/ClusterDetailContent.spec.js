import { mount } from "@vue/test-utils";
import { describe, it, expect, vi, beforeEach } from "vitest";
import { createPinia, setActivePinia } from "pinia"; // Import Pinia
import { createI18n } from "vue-i18n"; // Import vue-i18n

import ClusterDetailContent from "./ClusterDetailContent.vue";
import ClusterBox from "./../ClusterBox.vue"; // Adjust the path based on where ClusterBox is located
import AddOrEditCluster from "./../AddOrEditCluster.vue"; // Adjust the path for AddOrEditCluster
import { useRouter } from "vue-router";

// Mocking vue-router and useRoute
vi.mock("vue-router", async (importOriginal) => {
  const actual = await importOriginal(); // Import the real vue-router functions
  return {
    ...actual,
    useRoute: () => ({
      params: { id: "test-id" }, // Mock route params
    }),
    useRouter: () => ({
      push: vi.fn(),
    }),
    useLocaleRoute: () =>
      vi.fn((route) => ({ fullPath: `/mocked-path/${route.name}` })),
  };
});

// Create i18n instance for testing
const i18n = createI18n({
  legacy: false, // Use Composition API mode
  locale: "en",
  messages: {
    en: {
      DESCRIPTION: "Description",
      CANCEL: "Cancel",
    },
    // Add more locales if needed
  },
});

describe("ClusterDetailContent.vue", () => {
  let wrapper;
  let pinia;

  const mockProps = {
    isDetailsCluster: true,
    cluster: {
      description: "This is a test description",
    },
    id: { id: "test-id" },
  };

  beforeEach(() => {
    pinia = createPinia(); // Create a new Pinia instance
    setActivePinia(pinia); // Set the active Pinia instance for the store

    wrapper = mount(ClusterDetailContent, {
      props: mockProps,
      global: {
        plugins: [pinia, i18n], // Install Pinia and i18n as plugins
        mocks: {
          $t: (msg) => msg, // Mocking the i18n $t function
        },
        components: {
          ClusterBox,
          AddOrEditCluster,
        },
        stubs: {
          UButton: true, // Stub UButton
        },
      },
    });
  });

  it("renders the ClusterBox when isDetailsCluster is true and cluster.description is present", () => {
    expect(wrapper.findComponent(ClusterBox).exists()).toBe(true);
    expect(wrapper.findComponent(ClusterBox).props("data").data).toBe(
      "This is a test description"
    );
    expect(wrapper.findComponent(ClusterBox).props("data").label).toBe(
      "DESCRIPTION"
    );
  });

  it("does not render AddOrEditCluster when isDetailsCluster is true", () => {
    expect(wrapper.findComponent(AddOrEditCluster).exists()).toBe(false);
  });

  it("emits editClusterView when the closeDetailsView event is triggered", async () => {
    await wrapper.setProps({ isDetailsCluster: false });
    wrapper.findComponent(AddOrEditCluster).vm.$emit("closeDetailsView");
    expect(wrapper.emitted("editClusterView")).toBeTruthy();
  });
});
