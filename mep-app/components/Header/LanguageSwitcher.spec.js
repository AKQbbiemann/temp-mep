import { createI18n } from "vue-i18n";
import { shallowMount } from "@vue/test-utils";
import { nextTick } from "vue";
import LanguageSwitcher from "./LanguageSwitcher.vue";

// Mock the USelect component to simplify the test
const USelect = {
  template: '<div class="u-select-mock"></div>',
  props: ["modelValue"],
  methods: {
    $emit(event, value) {
      if (event === "update:modelValue") {
        this.modelValue = value;
      }
    },
  },
};

describe("LanguageSwitcher.vue", () => {
  let wrapper;
  let i18n;

  beforeEach(() => {
    // Create a new i18n instance with `en` and `de` locales
    i18n = createI18n({
      legacy: false, // Ensure Composition API mode is enabled
      locale: "en", // Initial locale is 'en'
      globalInjection: true, // Allow use of `$t`
      messages: {
        en: { message: "Hello" },
        de: { message: "Hallo" },
      },
    });

    wrapper = shallowMount(LanguageSwitcher, {
      global: {
        plugins: [i18n], // Provide i18n instance
        components: {
          USelect, // Use the mock USelect component
        },
      },
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the USelect component with correct initial language", () => {
    const select = wrapper.findComponent(USelect);

    // Verify the USelect component is rendered
    expect(select.exists()).toBe(true);
    // Check that the initial value for language is 'en'
    expect(wrapper.vm.lang).toBe("en");
    // Ensure the i18n locale is also 'en'
    expect(i18n.global.locale.value).toBe("en");
  });

  it("updates the locale when language is changed", async () => {
    const select = wrapper.findComponent(USelect);

    // Simulate changing the language to 'de'
    select.vm.$emit("update:modelValue", "de");
    await nextTick(); // Wait for reactivity to update

    // Check if the component's `lang` ref has been updated
    expect(wrapper.vm.lang).toBe("de");

    // Ensure the i18n locale is updated to 'de'
    expect(i18n.global.locale.value).toBe("de");
  });
});
