import { defineConfig } from '@playwright/test';
import { existsSync } from 'node:fs';
import { fileURLToPath } from 'node:url';

const authPath = fileURLToPath(new URL('auth.json', import.meta.url));

export default defineConfig({
    testDir: '.',
    testMatch: '**/*.spec.mjs',
    outputDir: './artifacts',
    workers: 1,
    timeout: 60000,
    reporter: [['list']],
    use: {
        channel: 'chrome',
        baseURL: process.env.PLAYWRIGHT_BASE_URL ?? 'http://ccditmp.ask.me',
        storageState: existsSync(authPath) ? authPath : undefined,
        screenshot: 'only-on-failure',
        trace: 'retain-on-failure',
    },
});
