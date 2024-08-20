import type { Config } from "tailwindcss";
import defaultTheme from "tailwindcss/defaultTheme";

export default <Partial<Config>>{
  mode: "jit",
  theme: {
    darkMode: "media",
    fontFamily: {
      heading: ["Poppins", ...defaultTheme.fontFamily.sans],
      lato: ["Lato", ...defaultTheme.fontFamily.sans],
    },
    extend: {
      colors: {
        "akq-green": {
          DEFAULT: "#05806d",
          50: "#E5EEEC",
          100: "#E6F2F0",
          200: "#C1DFDB",
          300: "#9BCCC5",
          400: "#50A699",
          500: "#05806D",
          600: "#057362",
          700: "#034D41",
          800: "#023A31",
          900: "#022621",
          950: "#00332e",
        },
        "akq-red": {
          DEFAULT: "#E7324C",
        },
        "akq-yellow": {
          DEFAULT: "#FFCC00",
        },
        "akq-green-ag": {
          DEFAULT: "#32A546",
        },
      },
    },
    borderRadius: {
      none: "0",
      sm: "4px",
      md: "8px",
      DEFAULT: "10px",
      lg: "15px",
      full: "9999px",
    },
    screens: {
      sm: "640px",
      md: "970px",
      lg: "1280px",
    },
  },
};
