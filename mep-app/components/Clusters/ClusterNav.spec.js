import { shallowMount } from "@vue/test-utils";
import { vi } from "vitest";
import { useI18n } from "vue-i18n";
import ClusterNav from "./ClusterNav.vue";
import { useRouter } from "vue-router";

// Mock useI18n
vi.mock("vue-i18n", () => ({
  useI18n: () => ({
    t: (key) => (key === "COMPETENCIES" ? "Competencies" : key),
  }),
}));

// Mock useRouter
const pushMock = vi.fn();
vi.mock("vue-router", () => ({
  useRouter: () => ({
    push: pushMock,
  }),
}));

// Mock child components
const UTabs = {
  template: "<div><slot :item=\"{ label: 'test' }\"></slot></div>",
  props: ["items"],
};

const ClustersList = { template: '<div class="clusters-list-mock"></div>' };
const SkillsList = { template: '<div class="skills-list-mock"></div>' };

describe("ClusterNav.vue", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(ClusterNav, {
      global: {
        components: {
          UTabs,
          ClustersList,
          SkillsList,
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders UTabs with correct items", () => {
    const tabs = wrapper.findComponent(UTabs);

    // Check if UTabs component exists
    expect(tabs.exists()).toBe(true);

    // Ensure UTabs has the correct items
    const items = wrapper.vm.items;
    expect(items.length).toBe(2);
    expect(items[0].label).toBe("Clusters");
    expect(items[1].label).toBe("Competencies");
  });

  it("calls onChange and navigates to the correct route when a tab is changed", async () => {
    const tabs = wrapper.findComponent(UTabs);

    // Simulate changing to the 'skills' tab (index 1)
    await tabs.vm.$emit("change", 1);
    expect(pushMock).toHaveBeenCalledWith("/clusters/skills");

    // Simulate changing to the 'clusters' tab (index 0)
    await tabs.vm.$emit("change", 0);
    expect(pushMock).toHaveBeenCalledWith("/clusters");
  });
});
