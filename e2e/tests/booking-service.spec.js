const { test, expect } = require('@playwright/test');
const { gotoTaxonomyTab, TAXONOMIES } = require('./helpers');

test.describe('Configuration > Booking Service (mwb_booking_service) taxonomy', () => {
  test.beforeEach(async ({ page }) => {
    await gotoTaxonomyTab(page, TAXONOMIES.service);
    await expect(page.locator('.mwb-inline-taxonomy__form-title')).toHaveText('Add New Booking Service');
  });

  test('all Booking Service fields are present on the Add New form', async ({ page }) => {
    for (const id of [
      'mwb-tax-name',
      'mwb-tax-slug',
      'mwb-tax-desc',
      'mwb_mbfw_service_cost',
      'mwb_mbfw_is_service_cost_multiply_people',
      'mwb_mbfw_is_service_cost_multiply_duration',
      'mwb_mbfw_is_service_optional',
      'mwb_mbfw_is_service_hidden',
      'mwb_mbfw_is_service_has_quantity',
      'mwb_mbfw_service_minimum_quantity',
      'mwb_mbfw_service_maximum_quantity',
    ]) {
      await expect(page.locator(`#${id}`)).toHaveCount(1);
    }
  });

  test('"If has Quantity" reveals and enables the Min/Max Quantity fields', async ({ page }) => {
    const minField = page.locator('#mwb_mbfw_service_minimum_quantity');
    const maxField = page.locator('#mwb_mbfw_service_maximum_quantity');

    await expect(minField).toBeDisabled();
    await expect(minField).not.toBeVisible();

    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_service_has_quantity)').click();

    await expect(minField).toBeEnabled();
    await expect(maxField).toBeEnabled();
    await expect(minField).toBeVisible();
    await expect(maxField).toBeVisible();
  });

  test('rejects Minimum Quantity greater than Maximum Quantity', async ({ page }) => {
    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_service_has_quantity)').click();

    page.once('dialog', (dialog) => dialog.accept());
    await page.fill('#mwb_mbfw_service_minimum_quantity', '10');
    await page.fill('#mwb_mbfw_service_maximum_quantity', '2');
    await page.locator('#mwb_mbfw_service_maximum_quantity').blur();

    // The client-side guard clears the field that was just edited (maximum).
    await expect(page.locator('#mwb_mbfw_service_maximum_quantity')).toHaveValue('');
  });

  test('creates, edits, and deletes a Booking Service term with all fields', async ({ page }) => {
    const termName = `E2E Service ${Date.now()}`;

    // --- Create ---
    await page.fill('#mwb-tax-name', termName);
    await page.fill('#mwb_mbfw_service_cost', '15');
    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_service_cost_multiply_people)').click();
    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_service_optional)').click();
    await page.locator('label.mwb-tax-switch:has(#mwb_mbfw_is_service_has_quantity)').click();
    await page.fill('#mwb_mbfw_service_minimum_quantity', '1');
    await page.fill('#mwb_mbfw_service_maximum_quantity', '5');
    await page.click('.mwb-tax-field--submit button');
    await page.waitForLoadState('load');

    await expect(page.locator('.mwb-inline-tax-notice')).toContainText(/New item added successfully/i);

    const row = page.locator('.mwb-inline-taxonomy__row', { hasText: termName });
    await expect(row).toHaveCount(1);
    // <td> order: [0] name, [1] cost, [2] people, [3] duration, [4] optional, [5] hidden, [6] quantity.
    const cells = row.locator('td');
    await expect(cells.nth(1)).toContainText('15');
    await expect(cells.nth(2).locator('.mwb-tax-icon--yes')).toHaveCount(1);
    await expect(cells.nth(3).locator('.mwb-tax-icon--no')).toHaveCount(1);
    await expect(cells.nth(4).locator('.mwb-tax-icon--yes')).toHaveCount(1);
    await expect(cells.nth(5).locator('.mwb-tax-icon--no')).toHaveCount(1);
    await expect(cells.nth(6).locator('.mwb-tax-icon--yes')).toHaveCount(1);

    // --- Edit --- (row-actions links only become visible on hover, like core WP list tables)
    await row.hover();
    await row.locator('a', { hasText: 'Edit' }).click();
    await expect(page.locator('#mwb-edit-name')).toHaveValue(termName);
    await expect(page.locator('#mwb-edit-cost')).toHaveValue('15');
    await expect(page.locator('#mwb-edit-people')).toBeChecked();
    await expect(page.locator('#mwb-edit-optional')).toBeChecked();
    await expect(page.locator('#mwb-edit-qty')).toBeChecked();
    await expect(page.locator('#mwb_mbfw_service_minimum_quantity')).toHaveValue('1');
    await expect(page.locator('#mwb_mbfw_service_maximum_quantity')).toHaveValue('5');

    await page.fill('#mwb-edit-cost', '30');
    await page.locator('label.mwb-tax-switch:has(#mwb-edit-hidden)').click();
    await page.click('.mwb-edit-actions button[type="submit"]');
    await page.waitForLoadState('load');

    await expect(page.locator('.mwb-inline-tax-notice')).toContainText(/Item updated successfully/i);
    const updatedRow = page.locator('.mwb-inline-taxonomy__row', { hasText: termName });
    const updatedCells = updatedRow.locator('td');
    await expect(updatedCells.nth(1)).toContainText('30');
    await expect(updatedCells.nth(5).locator('.mwb-tax-icon--yes')).toHaveCount(1);

    // --- Delete (cleanup) ---
    await updatedRow.hover();
    page.once('dialog', (dialog) => dialog.accept());
    await updatedRow.locator('a.mwb-tax-delete-link').click();
    await page.waitForLoadState('load');
    await expect(page.locator('.mwb-inline-tax-notice')).toContainText(/Item deleted successfully/i);
    await expect(page.locator('.mwb-inline-taxonomy__row', { hasText: termName })).toHaveCount(0);
  });
});
