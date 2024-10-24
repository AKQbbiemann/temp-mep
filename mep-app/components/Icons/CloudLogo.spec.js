import { mount } from "@vue/test-utils";
import CloudLogo from "./CloudLogo.vue";
import { describe, it, expect } from "vitest";

describe("CloudLogo.vue", () => {
  it("renders the cloud SVG correctly", () => {
    const wrapper = mount(CloudLogo);

    // Check if the root div with the class 'cloud-container' exists
    const cloudContainer = wrapper.find(".cloud-container");
    expect(cloudContainer.exists()).toBe(true);

    // Check if the SVG element is rendered with the correct width and height
    const svg = wrapper.find("svg");
    expect(svg.exists()).toBe(true);
    expect(svg.attributes("width")).toBe("151");
    expect(svg.attributes("height")).toBe("98");

    // Ensure the paths inside the SVG are present
    const paths = wrapper.findAll("path");
    expect(paths.length).toBeGreaterThan(0); // Ensure there are some paths

    // Optionally, check for one specific path
    const specificPath = wrapper.find("#path2844");
    expect(specificPath.exists()).toBe(true);
    expect(specificPath.attributes("style")).toContain("fill: #05806d");
  });
});
