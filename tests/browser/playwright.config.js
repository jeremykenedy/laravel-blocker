const { defineConfig } = require('@playwright/test');
module.exports = defineConfig({
    testDir: '.',
    testMatch: '*.spec.js',
    workers: 1,
    use: { baseURL: 'http://127.0.0.1:19746', trace: 'retain-on-failure' },
    webServer: {
        command: 'php -S 127.0.0.1:19746 server.php',
        url: 'http://127.0.0.1:19746/blocker',
        reuseExistingServer: !process.env.CI
    }
});
