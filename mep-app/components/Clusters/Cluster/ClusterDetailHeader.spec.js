import { mount } from "@vue/test-utils";
import { describe, it, expect, vi, beforeEach } from "vitest";
import ClusterDetailHeader from "./ClusterDetailHeader.vue";
import { createI18n } from "vue-i18n"; // For i18n support

// Mocking external components
const UIcon = { template: "<div><slot /></div>" };
const UModal = { template: "<div><slot /></div>", props: ["modelValue"] };
const AddOrEditCluster = { template: "<div></div>" };
const DeleteClusterModal = { template: "<div></div>" };

// i18n configuration for tests
const i18n = createI18n({
  legacy: false,
  locale: "en",
  messages: {
    en: {
      EDIT_CLUSTER: "Edit Cluster",
    },
  },
});

describe("ClusterDetailHeader.vue", () => {
  let wrapper;

  const mockProps = {
    isDetailsCluster: true,
    cluster: {
      name: "Test Cluster",
    },
    id: { id: "test-id" },
  };

  beforeEach(() => {
    wrapper = mount(ClusterDetailHeader, {
      props: mockProps,
      global: {
        plugins: [i18n], // Include the i18n plugin
        stubs: {
          UIcon, // Stub the UIcon component
          UModal, // Stub the UModal component
          AddOrEditCluster, // Stub the AddOrEditCluster component
          DeleteClusterModal, // Stub the DeleteClusterModal component
        },
        mocks: {
          $t: (msg) => msg, // Mock $t function for i18n
        },
      },
    });
  });

  it("renders the cluster name when isDetailsCluster is true", () => {
    const header = wrapper.find("h1");
    expect(header.text()).toBe("Test Cluster");
  });

  it("shows AddOrEditCluster modal when isOpenEditCluster is true", async () => {
    // Simulate clicking the edit icon, which should trigger the modal
    await wrapper.findComponent(UIcon).trigger("click");

    // Wait for the reactivity to update the state and render the modal
    await wrapper.vm.$nextTick();

    // Now the AddOrEditCluster modal should be displayed
    expect(wrapper.findComponent(AddOrEditCluster).exists()).toBe(true);
  });

  it("renders the 'Edit Cluster' title when isDetailsCluster is false", async () => {
    // Update the prop to switch from details view to edit view
    await wrapper.setProps({ isDetailsCluster: false });

    const header = wrapper.find("h1");
    expect(header.text()).toBe("EDIT_CLUSTER");
  });
});
