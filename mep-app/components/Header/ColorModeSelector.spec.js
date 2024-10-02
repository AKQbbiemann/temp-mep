import { shallowMount } from "@vue/test-utils";
import { createI18n } from "vue-i18n";
import { useColorMode } from "@vueuse/core"; // Import the composable
import { reactive } from "vue"; // Use reactive for the whole object
import { vi } from "vitest";
import ColorModeSelector from "./ColorModeSelector.vue";

// Mock the useColorMode composable
vi.mock("@vueuse/core", () => ({
  useColorMode: vi.fn(),
}));

describe("ColorModeSelector.vue", () => {
  let wrapper;
  let colorModeMock;

  beforeEach(() => {
    // Use reactive to make the entire colorModeMock reactive
    colorModeMock = reactive({
      preference: "light", // Initial mode is 'light'
    });

    // Make sure the mock returns the `colorModeMock`
    useColorMode.mockReturnValue(colorModeMock);

    const i18n = createI18n({
      // vue-i18n options here ...
    });

    wrapper = shallowMount(ColorModeSelector, {
      global: {
        plugins: [i18n],
        provide: {
          useColorMode: colorModeMock, // Mocking color mode composable
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the correct initial mode and icon", () => {
    // Since the mocked preference is 'light', the next mode should be 'dark'
    expect(wrapper.text()).toContain("🌑"); // dark mode icon
    expect(wrapper.vm.nextMode).toBe("dark");
  });

  it("toggles the mode when button is clicked", async () => {
    // Initially, the colorModeMock.preference is "light"
    expect(colorModeMock.preference).toBe("light");

    // Find the button and simulate a click
    const button = wrapper.find("button");
    await button.trigger("click");

    // After the click, the mode should switch to "dark"
    expect(colorModeMock.preference).toBe("dark");

    // Now that the mode is "dark", the next mode should be "system"
    expect(wrapper.vm.nextMode).toBe("system");

    // Also, check that the system mode icon is rendered
    expect(wrapper.text()).toContain("🌓"); // system mode icon
  });

  it("cycles through modes correctly", async () => {
    const button = wrapper.find("button");

    // Light -> Dark
    await button.trigger("click");
    expect(colorModeMock.preference).toBe("dark");
    expect(wrapper.vm.nextMode).toBe("system");

    // Dark -> System
    await button.trigger("click");
    expect(colorModeMock.preference).toBe("system");
    expect(wrapper.vm.nextMode).toBe("light");

    // System -> Light
    await button.trigger("click");
    expect(colorModeMock.preference).toBe("light");
    expect(wrapper.vm.nextMode).toBe("dark");
  });
});
