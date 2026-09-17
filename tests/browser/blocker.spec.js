const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

for (const framework of ['bootstrap5', 'tailwind']) {
    test(`${framework}: CRUD, recovery, search, themes, and mobile layout`, async ({ page, context }) => {
        const rejected = await context.request.post('/blocker', { form: { typeId: '3', value: 'missing-csrf.invalid' } });
        expect(rejected.status()).toBe(419);
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await context.addCookies([{ name: 'blocker_framework', value: framework, url: 'http://127.0.0.1:19746' }]);
        await page.goto('/blocker');
        await page.getByLabel('Appearance').selectOption('dark');
        await expect(page.locator('[data-blocker-root]')).toHaveAttribute('data-theme', 'dark');
        await page.reload();
        await expect(page.getByLabel('Appearance')).toHaveValue('dark');
        await page.getByRole('link', { name: 'Add blocked item' }).click();
        await page.getByLabel('Type', { exact: true }).selectOption({ label: 'Domain Name' });
        const value = `browser-${framework}-${Date.now()}.invalid`;
        await page.getByLabel('Value', { exact: true }).fill(value);
        await page.getByLabel('Note', { exact: true }).fill('<script>window.injected = true</script>');
        await page.getByRole('button', { name: 'Save item' }).click();
        await expect(page.getByRole('link', { name: value, exact: true })).toBeVisible();
        expect(await page.evaluate(() => window.injected)).toBeUndefined();
        await page.getByLabel('Search', { exact: true }).fill(value);
        await page.getByRole('button', { name: 'Search', exact: true }).click();
        await expect(page.locator('tbody tr')).toHaveCount(1);
        await page.getByRole('link', { name: 'Edit', exact: true }).click();
        await page.getByLabel('Note', { exact: true }).fill('Updated in browser');
        await page.getByRole('button', { name: 'Save item' }).click();
        await expect(page.getByLabel('Note', { exact: true })).toHaveValue('Updated in browser');
        await page.getByRole('link', { name: 'Blocked items', exact: true }).click();
        const row = page.locator('tr', { has: page.getByRole('link', { name: value, exact: true }) });
        page.once('dialog', dialog => dialog.dismiss());
        await row.getByRole('button', { name: 'Delete', exact: true }).click();
        await expect(row).toBeVisible();
        page.once('dialog', dialog => dialog.accept());
        await row.getByRole('button', { name: 'Delete', exact: true }).click();
        await page.getByRole('link', { name: 'Deleted items', exact: true }).click();
        page.once('dialog', dialog => dialog.accept());
        await page.locator('tr', { hasText: value }).getByRole('button', { name: 'Restore', exact: true }).click();
        await expect(page.getByRole('link', { name: value, exact: true })).toBeVisible();
        for (const theme of ['light', 'dark', 'system']) {
            await page.getByLabel('Appearance').selectOption(theme);
            const accessibility = await new AxeBuilder({ page }).include('[data-blocker-root]').withTags(['wcag2a', 'wcag2aa', 'wcag21aa']).analyze();
            expect(accessibility.violations).toEqual([]);
        }
        await page.setViewportSize({ width: 390, height: 844 });
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth)).toBeTruthy();
        await page.screenshot({ path: `test-results/${framework}-mobile.png`, fullPage: true });
        expect(errors).toEqual([]);
    });
}

for (const framework of ['bootstrap3', 'bootstrap4']) {
    test(`${framework}: legacy views and search remain usable`, async ({ page, context }) => {
        await context.addCookies([{ name: 'blocker_framework', value: framework, url: 'http://127.0.0.1:19746' }]);
        await page.goto('/blocker');
        await expect(page.locator(framework === 'bootstrap3' ? '.panel' : '.card').first()).toBeVisible();
        await page.locator('#blocked_search_box').fill('test.com');
        await page.getByRole('button', { name: 'Submit Blocked Search' }).click();
        await expect(page.locator('#search_results')).toContainText('test.com');
        await page.screenshot({ path: `test-results/${framework}.png`, fullPage: true });
    });
}
