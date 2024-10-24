import { mount } from "@vue/test-utils";
import IconUser from "./IconUser.vue";
import { describe, it, expect } from "vitest";

describe("IconUser.vue", () => {
  it("renders the user icon SVG correctly", () => {
    const wrapper = mount(IconUser);

    // Check if the SVG element is rendered
    const svg = wrapper.find("svg");
    expect(svg.exists()).toBe(true);

    // Check if the SVG has the correct attributes for width, height, viewBox
    expect(svg.attributes("width")).toBe("800px");
    expect(svg.attributes("height")).toBe("800px");
    expect(svg.attributes("viewBox")).toBe("796 796 200 200");

    // Check if the path inside the SVG is present
    const path = wrapper.find("path");
    expect(path.exists()).toBe(true);

    // Ensure the 'd' attribute of the path is correctly set
    expect(path.attributes("d")).toContain(
      "M896,796c-55.14,0-99.999,44.86-99.999,100"
    );

    // Check if the fill color of the SVG is correctly set
    expect(svg.attributes("fill")).toBe("#000000");
  });
});
