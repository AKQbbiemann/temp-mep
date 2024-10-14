import { mount } from "@vue/test-utils";
import { describe, it, expect, vi, beforeEach } from "vitest";
import AddOrEditCluster from "./AddOrEditCluster.vue"; // Adjust the path as needed
import { useRoute } from "vue-router";
import { useClustersStore } from "@/stores/clusters";
import { nextTick } from "vue";
import { createI18n } from "vue-i18n"; // Import vue-i18n

// Mock the useRoute and useClustersStore functions
vi.mock("vue-router", () => ({
  useRoute: vi.fn(),
}));
vi.mock("@/stores/clusters", () => ({
  useClustersStore: vi.fn(),
}));

// Create i18n instance for testing (non-legacy mode)
const i18n = createI18n({
  legacy: false, // Use Composition API mode
  locale: "en",
  messages: {
    en: {
      EDIT_CLUSTER: "Edit Cluster",
      NEW_CLUSTER: "New Cluster",
      THIS_FIELD_IS_REQUIRED: "This field is required",
      CANCEL: "Cancel",
      SAVE: "Save",
    },
    // Add more locales if necessary
  },
});

describe("AddOrEditCluster.vue", () => {
  let wrapper;
  let clustersStoreMock;

  beforeEach(() => {
    // Mocking the route params
    useRoute.mockReturnValue({
      params: { id: "1" },
    });

    // Mocking the clusters store
    clustersStoreMock = {
      getCluster: vi.fn().mockResolvedValue({
        name: "Test Cluster",
        description: "Test Description",
      }),
      addOrEdit: vi.fn(),
    };
    useClustersStore.mockReturnValue(clustersStoreMock);

    // Mounting the component with i18n (non-legacy mode)
    wrapper = mount(AddOrEditCluster, {
      global: {
        plugins: [i18n], // Include the i18n instance
      },
      props: {
        type: "edit",
      },
    });
  });

  it("loads the cluster data on mount when editing", async () => {
    await nextTick();
    expect(clustersStoreMock.getCluster).toHaveBeenCalledWith("1");
    expect(wrapper.vm.state.name).toBe("Test Cluster");
    expect(wrapper.vm.state.description).toBe("Test Description");
  });

  it("initializes with empty cluster data when adding", async () => {
    useRoute.mockReturnValueOnce({
      params: { id: null },
    });
    wrapper = mount(AddOrEditCluster, {
      global: {
        plugins: [i18n],
      },
      props: {
        type: "add",
      },
    });

    await nextTick();
    expect(wrapper.vm.state.name).toBe("");
    expect(wrapper.vm.state.description).toBe("");
  });

  it("submits the form and calls addOrEdit", async () => {
    wrapper.vm.state.name = "Updated Cluster";
    wrapper.vm.state.description = "Updated Description";

    await wrapper.vm.onSubmit();

    expect(clustersStoreMock.addOrEdit).toHaveBeenCalledWith(
      "Updated Cluster",
      "Updated Description",
      1 // The ID from the route
    );
  });

  it("emits 'isOpen' event with false after submission", async () => {
    await wrapper.vm.onSubmit();
    expect(wrapper.emitted().isOpen[0]).toEqual([false]);
  });
});
