import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: '.',
    testMatch: 'ResponsiveVisibility.spec.mjs',
    outputDir: './artifacts/responsive-security',
    workers: 1,
    reporter: 'list',
    use: {
        channel: 'chrome',
        headless: true,
        screenshot: 'only-on-failure',
    },
});
