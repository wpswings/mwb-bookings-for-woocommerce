require('dotenv').config();
const { chromium, expect } = require('@playwright/test');
const { togglePlugin } = require('./scripts/pro-plugin-control');

module.exports = async () => {
  const baseURL = process.env.BASE_URL || 'http://localhost:10028';
  const username = process.env.WP_ADMIN_USER;
  const password = process.env.WP_ADMIN_PASSWORD;

  if (!username || !password) {
    throw new Error('WP_ADMIN_USER and WP_ADMIN_PASSWORD must be set in e2e/.env');
  }

  // A Pro add-on, if active, overrides this plugin's own Availability Settings fields
  // via a shared filter hook. Deactivate it for the run so this plugin's real fields
  // render; global-teardown.js reactivates it afterwards.
  togglePlugin('deactivate');

  const browser = await chromium.launch();
  const page = await browser.newPage({ baseURL });

  await page.goto(`${baseURL}/wp-login.php`);
  await page.fill('#user_login', username);
  await page.fill('#user_pass', password);
  await page.click('#wp-submit');

  await expect(page.locator('#wpadminbar')).toBeVisible({ timeout: 15000 });

  // Dismiss the "Welcome to WP Swings" onboarding modal (shown on the plugin's settings
  // page) via its own "Skip For Now" AJAX handler, which suppresses it for 2 days server
  // side. Otherwise its overlay intercepts every click on the settings forms below it.
  await page.goto(`${baseURL}/wp-admin/admin.php?page=mwb_bookings_for_woocommerce_menu`);
  const skipLink = page.locator('.mwb-mbfw-on-boarding-no_thanks');
  if (await skipLink.count()) {
    await skipLink.click();
    await expect(page.locator('.mdc-dialog--open')).toHaveCount(0, { timeout: 10000 });
  }

  await page.context().storageState({ path: './.auth/admin.json' });
  await browser.close();
};
