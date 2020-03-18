// tailwind.config.js
const defaultTheme = require("tailwindcss/defaultTheme")

module.exports = {
  theme: {
    extend: {
      fontFamily: {
        sans: Object.assign(["Inter var"], defaultTheme.fontFamily.sans)
      }
    }
  },
  variants: {},
  plugins: [require("@tailwindcss/ui")]
};
