import { mount } from "@vue/test-utils";
import ClusterBox from "./ClusterBox.vue";

describe("ClusterBox.vue", () => {
  let wrapper;

  // Test case for rendering the component with props
  it("renders the component with the correct data", () => {
    const mockData = {
      label: "Cluster Label",
      data: "Cluster Data",
    };

    // Mount the component with mock data as props
    wrapper = mount(ClusterBox, {
      props: {
        data: mockData,
      },
    });

    // Check if the label is rendered correctly
    expect(wrapper.find("span.font-medium").text()).toBe(mockData.label);

    // Check if the data is rendered correctly
    expect(wrapper.find("span.text-sm").text()).toBe(mockData.data);
  });
});
