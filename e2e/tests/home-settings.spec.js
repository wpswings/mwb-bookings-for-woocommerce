const { test, expect } = require('@playwright/test');
const { gotoHomePage } = require('./helpers');

// This form's Save button (id=mbfw_button_demo) is only processed by the server when
// the current screen id is "wp-swings_page_home", so it must be exercised on the Home
// page itself, not the plugin's own settings tabs.
test.describe('WP Swings > Home page settings', () => {
  test.beforeEach(async ({ page }) => {
    await gotoHomePage(page);
    await expect(page.locator('.wps-header-title')).toHaveText('WP Swings');
  });

  test('"Enable Tracking" toggle persists after save and can be restored', async ({ page }) => {
    const toggle = page.locator('#mbfw_enable_tracking');
    await expect(toggle).toHaveCount(1);
    const initialChecked = await toggle.isChecked();

    await page.locator('label.wps-toggle:has(#mbfw_enable_tracking)').click();
    await page.click('#mbfw_button_demo');
    await page.waitForLoadState('load');
    await expect(page.locator('#mbfw_enable_tracking')).toBeChecked({ checked: !initialChecked });

    await page.locator('label.wps-toggle:has(#mbfw_enable_tracking)').click();
    await page.click('#mbfw_button_demo');
    await page.waitForLoadState('load');
    await expect(page.locator('#mbfw_enable_tracking')).toBeChecked({ checked: initialChecked });
  });
});
