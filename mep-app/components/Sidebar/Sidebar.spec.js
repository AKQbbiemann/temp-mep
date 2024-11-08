import { mount } from "@vue/test-utils";
import Sidebar from "./Sidebar.vue";
import { describe, it, expect, vi, beforeEach } from "vitest";

// Mock UVerticalNavigation component
const UVerticalNavigation = {
  props: ["links"],
  template: `<div class="vertical-navigation">
               <a v-for="link in links" :key="link.to" :href="link.to">{{ link.to }}</a>
             </div>`,
};

// Mock vue-router
vi.mock("vue-router", async (importOriginal) => {
  const actual = await importOriginal();
  return {
    ...actual,
    useRoute: () => ({
      params: { id: "test-id" },
    }),
    useRouter: () => ({
      push: vi.fn(),
    }),
    useLocaleRoute: () =>
      vi.fn((route) => ({ fullPath: `/mocked-path/${route.name}` })),
  };
});

// Mock getSidebarMenu function
const mockGetSidebarMenu = vi.fn();

vi.mock("../../composables/getSidebarMenu.js", () => ({
  getSidebarMenu: mockGetSidebarMenu,
}));

describe("Sidebar.vue", () => {
  let wrapper;

  beforeEach(() => {
    vi.clearAllMocks();

    // Set up `mockGetSidebarMenu` to return the correct structure
    mockGetSidebarMenu.mockReturnValue([
      { to: "/", icon: "i-heroicons-home" },
      { to: "/clusters", icon: "i-heroicons-square-3-stack-3d" },
      { to: "/loadprofiles", icon: "i-heroicons-squares-2x2-20-solid" },
      { to: "/requirements", icon: "i-heroicons-check-badge" },
    ]);

    wrapper = mount(Sidebar, {
      global: {
        provide: {
          localePath: (path) => path,
        },
        components: {
          UVerticalNavigation,
        },
        stubs: {
          NuxtLink: true,
          AkqLogo: true,
        },
      },
    });

    console.log("Rendered HTML:", wrapper.html());
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it("renders the logo", () => {
    const logo = wrapper.find('img[alt="Akquinet Logo"]');
    expect(logo.exists()).toBe(true);
    expect(logo.attributes("src")).toContain("logo.png");
  });

  it("renders the sidebar navigation", () => {
    const navigation = wrapper.find(".vertical-navigation");
    expect(navigation.exists()).toBe(true);
  });

  it("renders the sidebar navigation links", () => {
    const links = wrapper.findAll(".vertical-navigation a");
    expect(links).toHaveLength(4); // Adjusted to expect 4 links

    // Check each link text or icon if visible in your mock structure
    expect(links[0].attributes("href")).toBe("/"); // First link's href
    expect(links[1].attributes("href")).toBe("/clusters"); // Second link's href
    expect(links[2].attributes("href")).toBe("/loadprofiles"); // Third link's href
    expect(links[3].attributes("href")).toBe("/requirements"); // Fourth link's href
  });
});
