const { defineConfig } = require('cypress')

module.exports = defineConfig({
  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },projectId: "mpg52y",
  },chromeWebSecurity: false,
  reporter: "mochawesome",
  reporterOptions: {
    reportDir: "cypress/reports/mochawesome-report",
    overwrite: false,
    html: false,
    json: true,
    timestamp: "mmddyyyy_HHMMss"
  }
})