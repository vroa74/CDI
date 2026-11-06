import { test, expect } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const projectRoot = fileURLToPath(new URL('../', import.meta.url));
const source = readFileSync(path.join(projectRoot, 'resources/views/livewire/admin/usuarios/index.blade.php'), 'utf8');
const mobileClasses = source.match(/<div class="([^"]*block xl:hidden[^"]*)"/)[1];
const desktopClasses = source.match(/<div class="([^"]*hidden xl:block[^"]*)"/)[1];
const manifest = JSON.parse(readFileSync(path.join(projectRoot, 'public/build/manifest.json'), 'utf8'));
const css = readFileSync(path.join(projectRoot, 'public/build', manifest['resources/css/app.css'].file), 'utf8');

for (const width of [360, 768, 1279, 1280, 1440]) {
    test(`Usuarios muestra una vista visible sin scroll lateral a ${width}px`, async ({ page }) => {
        await page.route('**/*', route => route.abort());
        await page.setViewportSize({ width, height: 900 });
        await page.setContent(`<!doctype html><html lang="es"><head><style>${css}</style></head><body>
            <main class="app-content min-w-0">
                <div data-testid="mobile" class="${mobileClasses}">Usuario de prueba</div>
                <div data-testid="desktop" class="${desktopClasses}"><table class="responsive-table w-full"><tbody><tr><td>Usuario de prueba</td></tr></tbody></table></div>
            </main></body></html>`);

        if (width < 1280) {
            await expect(page.getByTestId('mobile')).toBeVisible();
            await expect(page.getByTestId('desktop')).toBeHidden();
        } else {
            await expect(page.getByTestId('desktop')).toBeVisible();
            await expect(page.getByTestId('mobile')).toBeHidden();
        }

        expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth)).toBe(true);
    });
}
