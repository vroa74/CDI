import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: '.',
    testMatch: 'ErrorPages.spec.mjs',
    outputDir: './artifacts/error-browser',
    workers: 1,
    reporter: 'list',
    use: { channel: 'chrome', headless: true },
});
