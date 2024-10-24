import { mount } from "@vue/test-utils";
import Loader from "./Loader.vue";
import { describe, it, expect } from "vitest";

describe("Loader.vue", () => {
  it("renders the Loader correctly", () => {
    const wrapper = mount(Loader);

    // Check if the main wrapper is rendered
    const wrapperDiv = wrapper.find(".spinner-wrapper");
    expect(wrapperDiv.exists()).toBe(true);

    // Check if the spinner container is rendered
    const spinnerDiv = wrapper.find(".spinner");
    expect(spinnerDiv.exists()).toBe(true);

    // Check if all the spinner rectangles are rendered with the correct class names
    const rects = [".rect1", ".rect2", ".rect3", ".rect4", ".rect5"];
    rects.forEach((rectClass) => {
      const rect = wrapper.find(rectClass);
      expect(rect.exists()).toBe(true);
    });
  });
});
