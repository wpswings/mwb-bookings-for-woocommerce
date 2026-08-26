const { test, expect } = require('@playwright/test');
const { gotoBookingFormSettings, clickToggle } = require('./helpers');

const SAVE_BUTTON = '#mwb_mbfw_booking_form_settings_save';

const TOGGLE_FIELDS = [
  { id: 'mwb_mbfw_is_show_included_service', label: 'Display Included Services' },
  { id: 'mwb_mbfw_is_show_totals', label: 'Display Totals' },
];

test.describe('Configuration > Booking Form Settings sub-tab', () => {
  test.beforeEach(async ({ page }) => {
    await gotoBookingFormSettings(page);
    await expect(page.locator('h3.nav-tab-wrapper .nav-tab-active')).toHaveText('Booking Form Settings');
  });

  for (const field of TOGGLE_FIELDS) {
    test(`"${field.label}" toggle persists after save and can be restored`, async ({ page }) => {
      const toggle = page.locator(`#${field.id}`);
      await expect(toggle).toHaveCount(1);
      const initialChecked = await toggle.isChecked();

      await clickToggle(page, field.id);
      await page.click(SAVE_BUTTON);
      await page.waitForLoadState('load');
      await gotoBookingFormSettings(page);
      await expect(page.locator(`#${field.id}`)).toBeChecked({ checked: !initialChecked });

      await clickToggle(page, field.id);
      await page.click(SAVE_BUTTON);
      await page.waitForLoadState('load');
      await gotoBookingFormSettings(page);
      await expect(page.locator(`#${field.id}`)).toBeChecked({ checked: initialChecked });
    });
  }

  test('"Please Select the language for calendar" select persists after save and can be restored', async ({ page }) => {
    const select = page.locator('#mwb_mbfw_select_language_for_calendar');
    await expect(select).toHaveCount(1);
    const initialValue = await select.inputValue();
    const newValue = initialValue === 'fr' ? 'de' : 'fr';

    await select.selectOption(newValue);
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await gotoBookingFormSettings(page);
    await expect(page.locator('#mwb_mbfw_select_language_for_calendar')).toHaveValue(newValue);

    await page.locator('#mwb_mbfw_select_language_for_calendar').selectOption(initialValue);
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await gotoBookingFormSettings(page);
    await expect(page.locator('#mwb_mbfw_select_language_for_calendar')).toHaveValue(initialValue);
  });

  test('"Please Select start day of the week" select persists after save and can be restored', async ({ page }) => {
    const select = page.locator('#mwb_mbfw_select_first_day_of_week');
    await expect(select).toHaveCount(1);
    const initialValue = await select.inputValue();
    const newValue = initialValue === '3' ? '1' : '3';

    await select.selectOption(newValue);
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await gotoBookingFormSettings(page);
    await expect(page.locator('#mwb_mbfw_select_first_day_of_week')).toHaveValue(newValue);

    await page.locator('#mwb_mbfw_select_first_day_of_week').selectOption(initialValue);
    await page.click(SAVE_BUTTON);
    await page.waitForLoadState('load');
    await gotoBookingFormSettings(page);
    await expect(page.locator('#mwb_mbfw_select_first_day_of_week')).toHaveValue(initialValue);
  });

  test('all Booking Form Settings fields are present', async ({ page }) => {
    for (const field of TOGGLE_FIELDS) {
      await expect(page.locator(`#${field.id}`)).toHaveCount(1);
    }
    await expect(page.locator('#mwb_mbfw_select_language_for_calendar')).toHaveCount(1);
    await expect(page.locator('#mwb_mbfw_select_first_day_of_week')).toHaveCount(1);
  });
});
