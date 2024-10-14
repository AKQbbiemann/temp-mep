import { mount } from "@vue/test-utils";
import ClusterListSkeleton from "./ClusterListSkeleton.vue"; // Adjust the path as necessary

const USkeleton = {
  template: "<div class='skeleton'></div>", // Simple mock template
};

describe("ClusterListSkeleton.vue", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(ClusterListSkeleton, {
      global: {
        components: {
          USkeleton, // Mocking USkeleton component
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the correct number of USkeleton components", () => {
    const skeletons = wrapper.findAllComponents(USkeleton);

    // Expect 8 skeleton elements based on your template
    expect(skeletons.length).toBe(8);
  });

  it("renders the first two USkeletons in the flex justify-between section", () => {
    const firstSectionSkeletons = wrapper
      .findAll(".flex.justify-between")
      .at(0)
      .findAllComponents(USkeleton);
    expect(firstSectionSkeletons.length).toBe(2);

    // Check the dimensions of the first skeleton
    expect(firstSectionSkeletons.at(0).attributes("class")).toContain(
      "h-10 w-40"
    );

    // Check the dimensions of the second skeleton
    expect(firstSectionSkeletons.at(1).attributes("class")).toContain(
      "h-10 w-10"
    );
  });

  it("renders the USkeleton components in the second section with the correct sizes", () => {
    const mb10Sections = wrapper.findAll(".mb-10");

    // Ensure we're targeting the second mb-10 section
    const secondSectionSkeletons = mb10Sections
      .at(1)
      .findAllComponents(USkeleton);
    expect(secondSectionSkeletons.length).toBe(2);

    // Check the first skeleton's size
    expect(secondSectionSkeletons.at(0).attributes("class")).toContain(
      "h-6 w-[250px] mb-2"
    );

    // Check the second skeleton's size
    expect(secondSectionSkeletons.at(1).attributes("class")).toContain(
      "h-6 w-[200px]"
    );
  });

  it("renders the full width skeleton bar in the middle section", () => {
    const fullWidthSkeleton = wrapper.find(".h-2.w-full.mb-10");
    expect(fullWidthSkeleton.exists()).toBe(true);
  });

  it("renders the last two USkeletons in the second flex justify-between section", () => {
    const lastSectionSkeletons = wrapper
      .findAll(".flex.justify-between")
      .at(1)
      .findAllComponents(USkeleton);
    expect(lastSectionSkeletons.length).toBe(2);

    // Check the dimensions of the first skeleton in the last section
    expect(lastSectionSkeletons.at(0).attributes("class")).toContain(
      "h-10 w-40"
    );

    // Check the dimensions of the second skeleton in the last section
    expect(lastSectionSkeletons.at(1).attributes("class")).toContain(
      "h-10 w-40"
    );
  });

  it("renders the large full width skeleton at the bottom", () => {
    const largeSkeleton = wrapper
      .findAllComponents(USkeleton)
      .find((skeleton) => {
        return skeleton.classes("h-[400px]") && skeleton.classes("w-full");
      });
    expect(largeSkeleton).toBeTruthy(); // or expect(largeSkeleton.exists()).toBe(true);
  });
});
