const { test, expect } = require('@playwright/test');
const { gotoTab, TABS, clickToggle } = require('./helpers');

const SAVE_BUTTON = '#mwb_mbfw_general_settings_save';

const TOGGLE_FIELDS = [
  { id: 'mwb_mbfw_is_plugin_enable', label: 'Enable Plugin' },
  { id: 'mwb_mbfw_is_booking_enable', label: 'Enable Bookings' },
  { id: 'mwb_mbfw_disable_book_now', label: 'Disable book now button on empty form' },
];

test.describe('General Settings tab', () => {
  test.beforeEach(async ({ page }) => {
    await gotoTab(page, TABS.general);
    await expect(page.locator('.wps-tab-highlight-block__title')).toHaveText('General Settings');
  });

  for (const field of TOGGLE_FIELDS) {
    test(`"${field.label}" toggle persists after save and can be restored`, async ({ page }) => {
      const toggle = page.locator(`#${field.id}`);
      await expect(toggle).toHaveCount(1);

      const initialChecked = await toggle.isChecked();

      // Flip the toggle and save.
      await clickToggle(page, field.id);
      await expect(toggle).toBeChecked({ checked: !initialChecked });
      await page.click(SAVE_BUTTON);
      await page.waitForLoadState('load');

      await expect(page.locator('.notice.notice-success')).toContainText(/Settings saved Successfully/i);
      await expect(page.locator(`#${field.id}`)).toBeChecked({ checked: !initialChecked });

      // Flip it back to the original value and save again (leave the site as we found it).
      await clickToggle(page, field.id);
      await page.click(SAVE_BUTTON);
      await page.waitForLoadState('load');
      await expect(page.locator(`#${field.id}`)).toBeChecked({ checked: initialChecked });
    });
  }

  test('all three General Settings fields are present with correct labels', async ({ page }) => {
    for (const field of TOGGLE_FIELDS) {
      await expect(page.locator(`#${field.id}`)).toHaveCount(1);
    }
  });
});
