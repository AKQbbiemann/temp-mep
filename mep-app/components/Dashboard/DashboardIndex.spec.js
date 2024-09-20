import { shallowMount } from "@vue/test-utils";
import DashboardIndex from "./DashboardIndex.vue";
import DashboardBox from "./DashboardBox.vue";

describe("DashboardIndex.vue", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(DashboardIndex, {
      global: {
        components: {
          DashboardBox,
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the correct number of DashboardBox components", () => {
    const dashboardBoxes = wrapper.findAllComponents(DashboardBox);
    expect(dashboardBoxes.length).toBe(4);
  });

  it("passes the correct props to each DashboardBox", () => {
    const dashboardBoxes = wrapper.findAllComponents(DashboardBox);

    expect(dashboardBoxes.at(0).props()).toMatchObject({
      count: "10",
      text: "Cluster",
      icon: "i-heroicons-user-group-solid",
    });

    expect(dashboardBoxes.at(1).props()).toMatchObject({
      count: "11",
      text: "Kompetenzen",
      icon: "i-mingcute-user-setting-line",
    });

    expect(dashboardBoxes.at(2).props()).toMatchObject({
      count: "10",
      text: "Anforderungen",
      icon: "i-mdi-file-question",
    });

    expect(dashboardBoxes.at(3).props()).toMatchObject({
      count: "10",
      text: "Warten auf Freigabe",
      icon: "i-clarity-clock-line",
      lastbox: true,
    });
  });

  it("renders the layout with proper CSS classes", () => {
    expect(wrapper.classes()).toContain("flex");
    expect(wrapper.classes()).toContain("flex-col");
    expect(wrapper.classes()).toContain("m-4");
    expect(wrapper.classes()).toContain("h-screen");
  });

  it("renders sub-containers correctly", () => {
    const subContainers = wrapper.findAll(".sub-container");
    expect(subContainers.length).toBe(2);
  });
});
