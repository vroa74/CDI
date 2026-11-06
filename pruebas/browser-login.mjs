import { chromium } from '@playwright/test';
import { fileURLToPath } from 'node:url';

const context = await chromium.launchPersistentContext(fileURLToPath(new URL('.browser-profile', import.meta.url)), {
    channel: 'chrome',
    headless: false,
    viewport: { width: 1440, height: 900 },
});
const page = context.pages()[0] ?? await context.newPage();
page.on('framenavigated', async (frame) => {
    if (frame !== page.mainFrame()) {
        return;
    }
    const url = new URL(frame.url());
    if (url.hostname === 'ccditmp.ask.me' && /^\/(dashboard|servicios|usuarios|inventario|reportes-red|edificios|lineas-internet)(\/|$)/.test(url.pathname)) {
        await context.storageState({ path: fileURLToPath(new URL('auth.json', import.meta.url)) });
        console.log('Sesión de pruebas preparada.');
    }
});
await page.goto('http://ccditmp.ask.me/servicios');
console.log('Ventana de pruebas abierta. Inicia sesión en esta ventana si se solicita.');
