import { mount } from "@vue/test-utils";
import SkeletonDashboardBox from "./SkeletonDashboardBox.vue";
import { describe, it, expect } from "vitest";

const USkeleton = {
  props: ["ui"],
  template: '<div class="skeleton" :class="ui"></div>',
};

describe("SkeletonDashboardBox.vue", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(SkeletonDashboardBox, {
      global: {
        components: {
          USkeleton, // Mock `USkeleton` component
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the container with the correct classes", () => {
    const container = wrapper.find(
      "div.flex.items-center.space-x-4.bg-gray-800.rounded-lg.shadow"
    );
    expect(container.exists()).toBe(true);
  });

  it("renders the circular `USkeleton` component for the avatar", () => {
    const avatarSkeleton = wrapper.findComponent(USkeleton);
    expect(avatarSkeleton.exists()).toBe(true);
    expect(avatarSkeleton.classes()).toContain("h-12");
    expect(avatarSkeleton.classes()).toContain("w-12");
    expect(avatarSkeleton.props("ui")).toEqual({ rounded: "rounded-full" });
  });

  it("renders two `USkeleton` components for text lines with correct dimensions", () => {
    const textSkeletons = wrapper.findAllComponents(USkeleton);
    expect(textSkeletons).toHaveLength(3); // 1 for avatar, 2 for text

    // Check the first text skeleton's dimensions
    expect(textSkeletons[1].classes()).toContain("h-4");
    expect(textSkeletons[1].classes()).toContain("w-[250px]");

    // Check the second text skeleton's dimensions
    expect(textSkeletons[2].classes()).toContain("h-4");
    expect(textSkeletons[2].classes()).toContain("w-[200px]");
  });
});
