import { mount } from "@vue/test-utils";
import { describe, it, expect } from "vitest";
import ClusterDetails from "./ClusterDetails.vue";

describe("ClusterDetails.vue", () => {
  it("renders header and content slots correctly", () => {
    // Define header and content to be passed into the slots
    const headerContent = "Cluster Header";
    const contentContent = "Cluster details and information here.";

    // Mount the component with slot content
    const wrapper = mount(ClusterDetails, {
      slots: {
        header: headerContent,
        content: contentContent,
      },
    });

    // Check if the header slot content is rendered
    const header = wrapper.find("div > div:first-child");
    expect(header.text()).toContain(headerContent);

    // Check if the content slot content is rendered
    const content = wrapper.find("div > div:last-child");
    expect(content.text()).toContain(contentContent);
  });
});
