import { shallowMount } from "@vue/test-utils";
import AppHeader from "./AppHeader.vue";

// Mock the ProfileMenu and UIcon components
const ProfileMenu = {
  template: '<div class="profile-menu-mock"></div>',
};

const UIcon = {
  template: '<div class="icon-mock" @click="$emit(\'click\')"></div>',
  props: ["name"],
};

describe("AppHeader.vue", () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(AppHeader, {
      global: {
        components: {
          ProfileMenu,
          UIcon,
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the ProfileMenu component", () => {
    const profileMenu = wrapper.findComponent(ProfileMenu);
    expect(profileMenu.exists()).toBe(true);
  });

  it("emits 'open' event when UIcon is clicked", async () => {
    const icon = wrapper.findComponent(UIcon);

    // Simulate the UIcon click
    await icon.trigger("click");

    // Check if the 'open' event was emitted
    expect(wrapper.emitted().open).toBeTruthy();
    expect(wrapper.emitted().open[0]).toEqual([true]);
  });
});
