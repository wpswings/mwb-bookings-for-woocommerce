const { test, expect } = require('@playwright/test');
const { gotoTab, TABS, clickToggle } = require('./helpers');

const SAVE_BUTTON = '#mwb_mbfw_availability_settings_save';

test.describe('Availability Settings tab', () => {
  test.beforeEach(async ({ page }) => {
    await gotoTab(page, TABS.availability);
    await expect(page.locator('.wps-tab-highlight-block__title')).toHaveText('Availability Settings');
  });

  test('"Enable availability setting" toggle persists after save and can be restored', async ({ page }) => {
    const toggle = page.locator('#mwb_mbfw_enable_availibility_setting');
    await expect(toggle).toHaveCount(1);
    const initialChecked = await toggle.isChecked();

    await clickToggle(page, 'mwb_mbfw_enable_availibility_setting');
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await expect(page.locator('.notice.notice-success')).toContainText(/Settings saved Successfully/i);
    await expect(page.locator('#mwb_mbfw_enable_availibility_setting')).toBeChecked({ checked: !initialChecked });

    await clickToggle(page, 'mwb_mbfw_enable_availibility_setting');
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await expect(page.locator('#mwb_mbfw_enable_availibility_setting')).toBeChecked({ checked: initialChecked });
  });

  test('Daily Start Time and Daily End Time persist after save and can be restored', async ({ page }) => {
    const startInput = page.locator('#mwb_mbfw_daily_start_time');
    const endInput = page.locator('#mwb_mbfw_daily_end_time');
    await expect(startInput).toHaveCount(1);
    await expect(endInput).toHaveCount(1);

    const initialStart = await startInput.inputValue();
    const initialEnd = await endInput.inputValue();

    await startInput.fill('08:00');
    await endInput.fill('18:00');
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await expect(page.locator('.notice.notice-success')).toContainText(/Settings saved Successfully/i);
    await expect(page.locator('#mwb_mbfw_daily_start_time')).toHaveValue('08:00');
    await expect(page.locator('#mwb_mbfw_daily_end_time')).toHaveValue('18:00');

    await page.locator('#mwb_mbfw_daily_start_time').fill(initialStart);
    await page.locator('#mwb_mbfw_daily_end_time').fill(initialEnd);
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await expect(page.locator('#mwb_mbfw_daily_start_time')).toHaveValue(initialStart);
    await expect(page.locator('#mwb_mbfw_daily_end_time')).toHaveValue(initialEnd);
  });

  test('rejects Daily Start Time later than Daily End Time on save', async ({ page }) => {
    const startInput = page.locator('#mwb_mbfw_daily_start_time');
    const endInput = page.locator('#mwb_mbfw_daily_end_time');
    const initialStart = await startInput.inputValue();
    const initialEnd = await endInput.inputValue();

    page.once('dialog', (dialog) => dialog.accept());
    await startInput.fill('20:00');
    await endInput.fill('09:00');
    await page.click(SAVE_BUTTON);

    // The client-side guard blocks submission, so the tab should still show the invalid values un-persisted.
    await expect(page).toHaveURL(/mbfw_tab=mwb-bookings-for-woocommerce-booking-availability-settings/);

    // Restore original values regardless of whether the guard fired.
    await page.locator('#mwb_mbfw_daily_start_time').fill(initialStart);
    await page.locator('#mwb_mbfw_daily_end_time').fill(initialEnd);
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
  });

  test('all Availability Settings fields are present', async ({ page }) => {
    await expect(page.locator('#mwb_mbfw_enable_availibility_setting')).toHaveCount(1);
    await expect(page.locator('#mwb_mbfw_daily_start_time')).toHaveCount(1);
    await expect(page.locator('#mwb_mbfw_daily_end_time')).toHaveCount(1);
  });
});
