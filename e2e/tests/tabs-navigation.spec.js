const { test, expect } = require('@playwright/test');
const { gotoTab, gotoBookingFormSettings, gotoTaxonomyTab, gotoHomePage, TABS, TAXONOMIES } = require('./helpers');

const PHP_ERROR_PATTERN = /(Fatal error|Parse error|Warning:|Notice:|Deprecated:)/i;

async function expectCleanLoad(page) {
  const bodyText = await page.locator('body').innerText();
  expect(bodyText).not.toMatch(PHP_ERROR_PATTERN);
}

test.describe('Plugin settings tab navigation', () => {
  test('Overview tab loads', async ({ page }) => {
    await gotoTab(page, TABS.overview);
    await expect(page.locator('.mwb-navbar__items a#mwb-bookings-for-woocommerce-overview')).toHaveClass(/active/);
    await expectCleanLoad(page);
  });

  test('General Settings tab loads', async ({ page }) => {
    await gotoTab(page, TABS.general);
    await expect(page.locator('.mwb-navbar__items a#mwb-bookings-for-woocommerce-general')).toHaveClass(/active/);
    await expect(page.locator('.wps-tab-highlight-block__title')).toHaveText('General Settings');
    await expectCleanLoad(page);
  });

  test('Configuration Settings tab loads with Booking Form Settings sub-tab', async ({ page }) => {
    await gotoBookingFormSettings(page);
    await expect(page.locator('.mwb-navbar__items a#mwb-bookings-for-woocommerce-configuration')).toHaveClass(/active/);
    await expect(page.locator('h3.nav-tab-wrapper .nav-tab-active')).toHaveText('Booking Form Settings');
    await expectCleanLoad(page);
  });

  test('Bookings Calendar tab loads', async ({ page }) => {
    await gotoTab(page, TABS.calendar);
    await expect(page.locator('.mwb-navbar__items a#mwb-bookings-for-woocommerce-booking-calendar-listing')).toHaveClass(/active/);
    await expectCleanLoad(page);
  });

  test('Availability Settings tab loads', async ({ page }) => {
    await gotoTab(page, TABS.availability);
    await expect(page.locator('.mwb-navbar__items a#mwb-bookings-for-woocommerce-booking-availability-settings')).toHaveClass(/active/);
    await expect(page.locator('.wps-tab-highlight-block__title')).toHaveText('Availability Settings');
    await expectCleanLoad(page);
  });

  test('Configuration Settings tab loads with Additional Cost sub-tab', async ({ page }) => {
    await gotoTaxonomyTab(page, TAXONOMIES.cost);
    await expect(page.locator('.mwb-inline-taxonomy__form-title')).toHaveText('Add New Booking Cost');
    await expectCleanLoad(page);
  });

  test('Configuration Settings tab loads with Booking Service sub-tab', async ({ page }) => {
    await gotoTaxonomyTab(page, TAXONOMIES.service);
    await expect(page.locator('.mwb-inline-taxonomy__form-title')).toHaveText('Add New Booking Service');
    await expectCleanLoad(page);
  });

  test('WP Swings > Home page loads', async ({ page }) => {
    await gotoHomePage(page);
    await expect(page.locator('.wps-header-title')).toHaveText('WP Swings');
    await expectCleanLoad(page);
  });
});
