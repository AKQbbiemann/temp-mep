import { mount } from "@vue/test-utils";
import { describe, it, expect, vi, beforeEach, afterEach } from "vitest";
import { nextTick } from "vue"; // Import nextTick from Vue

import { createI18n } from "vue-i18n";
import ClustersList from "./ClustersList.vue";

// Mock the Clusters Store
vi.mock("@/stores/clusters", () => ({
  useClustersStore: vi.fn(() => ({
    getClustersList: [
      { id: 1, name: "Cluster 1" },
      { id: 2, name: "Cluster 2" },
    ],
    fill: vi.fn(async () => [
      { id: 1, name: "Cluster 1" },
      { id: 2, name: "Cluster 2" },
    ]),
  })),
}));

// Mock vue-router
vi.mock("vue-router", () => ({
  useRouter: () => ({
    push: vi.fn(),
  }),
  useLocaleRoute: () => (route) => ({
    fullPath: `/mocked-path/${route}`,
  }),
}));

// Create i18n instance for testing
const i18n = createI18n({
  legacy: false,
  locale: "en",
  messages: {
    en: {
      ADD_CLUSTER: "Add Cluster",
    },
  },
});

describe("ClustersList.vue", () => {
  let wrapper;

  beforeEach(async () => {
    wrapper = mount(ClustersList, {
      global: {
        plugins: [i18n],
        stubs: {
          UButton: true,
          UModal: true,
          ClusterListSkeleton: true,
          AddOrEditCluster: true,
        },
        // Disable auto-stubbing of nuxt-link
        renderStubDefaultSlot: true,
      },
    });

    // Simulate the mounted lifecycle by calling getList
    await wrapper.vm.getList();
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the list of clusters", async () => {
    // Ensure the clusters list is available
    const clusters = wrapper.vm.lists;
    expect(clusters.length).toBe(2); // Should be 2 because the mock store returns 2 items

    // Ensure Vue reactivity has updated the DOM
    await wrapper.vm.$nextTick();

    // Find all the rendered list items
    const listItems = wrapper.findAll("li");

    // Check that the number of rendered items matches the number of clusters
    expect(listItems.length).toBe(2);

    // Verify that the first and second list items are correctly rendered
    expect(listItems.at(0).text()).toContain("Cluster 1");
    expect(listItems.at(1).text()).toContain("Cluster 2");
  });

  it("shows ClusterListSkeleton when loading", async () => {
    // Set isLoading to true
    wrapper.vm.isLoading = true;

    // Ensure Vue reactivity has updated the DOM
    await nextTick();

    // Check that the ClusterListSkeleton is displayed when isLoading is true
    expect(
      wrapper.findComponent({ name: "ClusterListSkeleton" }).exists()
    ).toBe(true);

    // Ensure the cluster list is not rendered
    expect(wrapper.find("ul").exists()).toBe(false);
  });

  it("renders cluster names correctly in the list", async () => {
    // Ensure Vue reactivity has updated the DOM
    await nextTick();

    // Find all list items
    const listItems = wrapper.findAll("li");

    // Check that the first cluster's name is correct
    expect(listItems.at(0).text()).toContain("Cluster 1");

    // Check that the second cluster's name is correct
    expect(listItems.at(1).text()).toContain("Cluster 2");
  });

  it("renders correct paths for cluster links", async () => {
    // Ensure Vue reactivity has updated the DOM
    await nextTick();

    // Check the links' paths
    const nuxtLinks = wrapper.findAllComponents({ name: "nuxt-link" });

    // Ensure the first cluster's path is correct
    expect(nuxtLinks.at(0).attributes("to")).toBe("/clusters/1");

    // Ensure the second cluster's path is correct
    expect(nuxtLinks.at(1).attributes("to")).toBe("/clusters/2");
  });
});
