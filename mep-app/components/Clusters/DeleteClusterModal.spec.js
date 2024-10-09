import { mount } from "@vue/test-utils";
import DeleteClusterModal from "./DeleteClusterModal.vue";
import { createI18n } from "vue-i18n";
import { vi } from "vitest";

// Mock the useClustersStore composable
const deleteClusterMock = vi.fn();
vi.mock("@/stores/clusters", () => ({
  useClustersStore: () => ({
    deleteCluster: deleteClusterMock,
  }),
}));

// Define mock translations
const messages = {
  en: {
    DELETE_CLUSTER: "Delete Cluster",
    DELETE_CLUSTER_MSG: "Are you sure you want to delete this cluster?",
    CANCEL: "Cancel",
    DELETE: "Delete",
  },
};

// Create an i18n instance with the mock messages
const i18n = createI18n({
  locale: "en",
  messages,
  legacy: false, // Ensure legacy mode is turned off
});

const UButton = {
  template: "<button @click=\"$emit('click')\">{{ label }}</button>",
  props: ["label"],
};

const UCard = {
  template:
    '<div><slot name="header"></slot><slot></slot><slot name="footer"></slot></div>', // Simulate the slots
};

describe("DeleteClusterModal.vue", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(DeleteClusterModal, {
      props: {
        clusterId: 1,
      },
      global: {
        plugins: [i18n], // Use the i18n plugin in the test environment
        components: {
          UButton,
          UCard, // Mock UCard to expose slots
        },
      },
    });
  });

  afterEach(() => {
    vi.clearAllMocks();
    wrapper.unmount();
  });

  it("renders the correct content", () => {
    const header = wrapper.find("h3");
    expect(header.exists()).toBeTruthy();
    expect(header.text()).toContain("Delete Cluster");

    const paragraph = wrapper.find("p");
    expect(paragraph.text()).toContain(
      "Are you sure you want to delete this cluster?"
    );
  });

  it("emits 'isOpen' with false when the cancel button is clicked", async () => {
    const cancelButton = wrapper.findAllComponents(UButton).at(0);

    // Ensure the button exists before triggering
    expect(cancelButton.exists()).toBe(true);

    // Trigger the click event
    await cancelButton.trigger("click");

    expect(wrapper.emitted("isOpen")).toBeTruthy();
    expect(wrapper.emitted("isOpen")[0]).toEqual([false]);
  });

  it("calls OnSubmit and emits 'isOpen' with false when the delete button is clicked", async () => {
    const deleteButton = wrapper.findAllComponents(UButton).at(2);

    // Ensure the button exists before triggering
    expect(deleteButton.exists()).toBe(true);

    // Trigger the click event
    await deleteButton.trigger("click");

    // Log mock calls
    console.log("Mock calls:", deleteClusterMock.mock.calls);

    // Check if the deleteClusterMock was called with the correct argument (1)
    expect(deleteClusterMock).toHaveBeenCalledWith(1);

    // Check if the 'isOpen' event was emitted with false
    expect(wrapper.emitted("isOpen")).toBeTruthy();
    expect(wrapper.emitted("isOpen")[0]).toEqual([false]);
  });

  it("handles errors in OnSubmit", async () => {
    deleteClusterMock.mockRejectedValueOnce(new Error("Deletion failed"));

    const deleteButton = wrapper.findAllComponents(UButton).at(2);

    // Ensure the button exists before triggering
    expect(deleteButton.exists()).toBe(true);

    await deleteButton.trigger("click");

    expect(deleteClusterMock).toHaveBeenCalledWith(1);
    expect(wrapper.emitted("isOpen")).toBeTruthy();
    expect(wrapper.emitted("isOpen")[0]).toEqual([false]);
  });
});
