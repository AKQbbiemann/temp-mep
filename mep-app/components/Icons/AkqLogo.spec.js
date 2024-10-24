import { mount } from "@vue/test-utils";
import { describe, it, expect } from "vitest";
import AkqLogo from "./AkqLogo.vue";

describe("AkqLogo.vue", () => {
  it("renders the SVG element with correct attributes", () => {
    // Mount the component
    const wrapper = mount(AkqLogo);

    // Find the SVG element
    const svg = wrapper.find("svg");

    // Check that the SVG element exists
    expect(svg.exists()).toBe(true);

    // Check that the SVG has the correct attributes
    expect(svg.attributes("xmlns")).toBe("http://www.w3.org/2000/svg");
    expect(svg.attributes("viewBox")).toBe("0 0 178 55");
    expect(svg.attributes("width")).toBe("90");
    expect(svg.attributes("height")).toBe("70");
  });

  it("contains the expected paths in the SVG", () => {
    // Mount the component
    const wrapper = mount(AkqLogo);

    // Find all path elements
    const paths = wrapper.findAll("path");

    // Check that the correct number of paths exist
    expect(paths.length).toBe(9); // Since there are 9 <path> elements in the component

    // Optionally, check the 'd' attribute of each path to ensure they are rendered correctly
    expect(paths[0].attributes("d")).toContain("M121.05661,12.97679");
    expect(paths[1].attributes("d")).toContain("M154.38357,20H177.8508");
    // Add more assertions for the remaining paths if necessary
  });
});
