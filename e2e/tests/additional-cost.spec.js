const { test, expect } = require('@playwright/test');
const { gotoTaxonomyTab, TAXONOMIES } = require('./helpers');

test.describe('Configuration > Additional Cost (mwb_booking_cost) taxonomy', () => {
  test.beforeEach(async ({ page }) => {
    await gotoTaxonomyTab(page, TAXONOMIES.cost);
    await expect(page.locator('.mwb-inline-taxonomy__form-title')).toHaveText('Add New Booking Cost');
  });

  test('all Additional Cost fields are present on the Add New form', async ({ page }) => {
    await expect(page.locator('#mwb-tax-name')).toHaveCount(1);
    await expect(page.locator('#mwb-tax-slug')).toHaveCount(1);
    await expect(page.locator('#mwb-tax-desc')).toHaveCount(1);
    await expect(page.locator('#mwb_mbfw_booking_cost')).toHaveCount(1);
    await expect(page.locator('#mwb_mbfw_is_booking_cost_multiply_people')).toHaveCount(1);
    await expect(page.locator('#mwb_mbfw_is_booking_cost_multiply_duration')).toHaveCount(1);
  });

  test('creates, edits, and deletes an Additional Cost term with all fields', async ({ page }) => {
    const termName = `E2E Cost ${Date.now()}`;

    // --- Create ---
    await page.fill('#mwb-tax-name', termName);
    await page.fill('#mwb_mbfw_booking_cost', '25.5');
    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_booking_cost_multiply_people)').click();
    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_booking_cost_multiply_duration)').click();
    await page.click('.mwb-tax-field--submit button');
    await page.waitForLoadState('load');

    await expect(page.locator('.mwb-inline-tax-notice')).toContainText(/New item added successfully/i);

    const row = page.locator('.mwb-inline-taxonomy__row', { hasText: termName });
    await expect(row).toHaveCount(1);
    // <td> order within the row: [0] name, [1] cost, [2] multiply-by-people icon, [3] multiply-by-duration icon.
    const cells = row.locator('td');
    await expect(cells.nth(1)).toContainText('25.5');
    await expect(cells.nth(2).locator('.mwb-tax-icon--yes')).toHaveCount(1);
    await expect(cells.nth(3).locator('.mwb-tax-icon--yes')).toHaveCount(1);

    // --- Edit --- (row-actions links only become visible on hover, like core WP list tables)
    await row.hover();
    await row.locator('a', { hasText: 'Edit' }).click();
    await expect(page.locator('.mwb-inline-taxonomy__edit-title')).toContainText('Edit');
    await expect(page.locator('#mwb-edit-name')).toHaveValue(termName);
    await expect(page.locator('#mwb-edit-cost')).toHaveValue('25.5');
    await expect(page.locator('#mwb-edit-people')).toBeChecked();
    await expect(page.locator('#mwb-edit-duration')).toBeChecked();

    await page.fill('#mwb-edit-cost', '40');
    await page.locator('label.mwb-tax-switch:has(#mwb-edit-duration)').click();
    await page.click('.mwb-edit-actions button[type="submit"]');
    await page.waitForLoadState('load');

    await expect(page.locator('.mwb-inline-tax-notice')).toContainText(/Item updated successfully/i);
    const updatedRow = page.locator('.mwb-inline-taxonomy__row', { hasText: termName });
    const updatedCells = updatedRow.locator('td');
    await expect(updatedCells.nth(1)).toContainText('40');
    await expect(updatedCells.nth(2).locator('.mwb-tax-icon--yes')).toHaveCount(1);
    await expect(updatedCells.nth(3).locator('.mwb-tax-icon--no')).toHaveCount(1);

    // --- Delete (cleanup) ---
    await updatedRow.hover();
    page.once('dialog', (dialog) => dialog.accept());
    await updatedRow.locator('a.mwb-tax-delete-link').click();
    await page.waitForLoadState('load');
    await expect(page.locator('.mwb-inline-tax-notice')).toContainText(/Item deleted successfully/i);
    await expect(page.locator('.mwb-inline-taxonomy__row', { hasText: termName })).toHaveCount(0);
  });
});
