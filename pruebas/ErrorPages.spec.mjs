import { test, expect } from '@playwright/test';
import { readFileSync } from 'node:fs';

for (const authenticated of [false, true]) {
for (const status of [401, 402, 403, 404, 419, 429, 500, 503]) {
    test(`Error ${status}, sesión ${authenticated}: responsive, animación y navegación`, async ({ page }) => {
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await page.route('**/*', route => route.abort());
        const html = readFileSync(new URL(`./artifacts/error-pages/${status}${authenticated ? '-authenticated' : ''}.html`, import.meta.url), 'utf8');
        await page.setContent(html);
        await expect(page.getByRole('link', { name: 'Volver al inicio' })).toHaveAttribute('href', authenticated ? 'http://localhost/dashboard' : 'http://localhost');
        for (const width of [320, 360, 768, 1279, 1280, 1440]) {
            await page.setViewportSize({ width, height: 700 });
            await expect(page.getByRole('heading', { name: `Error ${status}`, exact: true })).toBeVisible();
            await expect(page.getByRole('link', { name: 'Volver al inicio' })).toBeVisible();
            expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
            if (status === 404 && [360, 1440].includes(width)) {
                await page.screenshot({ path: `pruebas/artifacts/error-pages/404-${width}.png`, fullPage: true });
            }
        }
        expect(await page.locator('#canvas').evaluate(canvas => canvas.getContext('2d').getImageData(0, 0, 1, 1).data[3])).toBe(255);
        await page.emulateMedia({ reducedMotion: 'reduce' });
        await expect(page.locator('.frame div').first()).toHaveCSS('animation-name', 'none');
        await page.getByRole('heading', { level: 2 }).evaluate(element => element.textContent = 'Descripción extensa '.repeat(100));
        await page.setViewportSize({ width: 320, height: 400 });
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        await page.getByRole('link').scrollIntoViewIfNeeded();
        await expect(page.getByRole('link')).toBeInViewport();
        expect(errors).toEqual([]);
        if (status === 404) await page.screenshot({ path: 'pruebas/artifacts/error-pages/404-mobile.png', fullPage: true });
    });
}
}
