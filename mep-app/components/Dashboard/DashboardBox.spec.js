import { mount } from "@vue/test-utils";
import { describe, it, expect, test } from "vitest";
import DashboardBox from "./DashboardBox.vue";

// Mocking USkeleton and UIcon components
const USkeleton = {
  template: '<div class="skeleton-mock"></div>',
};

const UIcon = {
  template: '<div class="dashboard-box-icon"></div>',
};

describe("DashboardBox", () => {
  it("is component rendered correctly", () => {
    expect(DashboardBox).toBeTruthy();
  });

  it("renders the content correctly when not loading", () => {
    const wrapper = mount(DashboardBox, {
      props: {
        loading: false,
        text: "Sample Text",
        count: "123",
        icon: "sample-icon",
        lastbox: false,
      },
      global: {
        components: {
          USkeleton,
          UIcon,
        },
      },
    });
    // Assert that the loading skeleton is not rendered
    expect(wrapper.find(".skeleton-mock").exists()).toBe(false);
    // Assert that the main content is rendered
    expect(wrapper.find(".dashboard-box").exists()).toBe(true);
    // Assert that the text is rendered correctly
    expect(wrapper.find("h2").text()).toBe("Sample Text");
    // Assert that the count is rendered correctly
    expect(wrapper.find("p").text()).toBe("123");
    // Assert that the icon is rendered correctly
    expect(wrapper.find(".dashboard-box-icon").exists()).toBe(true);
    // Assert that the lastbox class is working correctly
    expect(wrapper.find(".border-r-2").exists()).toBe(true);
  });

  it("renders loading state correctly", () => {
    const wrapper = mount(DashboardBox, {
      props: {
        loading: true,
      },
      global: {
        components: { USkeleton },
      },
    });

    // Assert that the loading skeleton is rendered
    expect(wrapper.find(".skeleton-mock").exists()).toBe(true);
    // Assert that the main content is not rendered
    expect(wrapper.find(".dashboard-box").exists()).toBe(false);
  });
});
