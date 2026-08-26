const PLUGIN_PAGE = 'wp-admin/admin.php?page=mwb_bookings_for_woocommerce_menu';

const TABS = {
  overview: 'mwb-bookings-for-woocommerce-overview',
  general: 'mwb-bookings-for-woocommerce-general',
  configuration: 'mwb-bookings-for-woocommerce-configuration',
  calendar: 'mwb-bookings-for-woocommerce-booking-calendar-listing',
  availability: 'mwb-bookings-for-woocommerce-booking-availability-settings',
};

async function gotoTab(page, tabKey, extraQuery = '') {
  await page.goto(`${PLUGIN_PAGE}&mbfw_tab=${tabKey}${extraQuery}`);
}

async function gotoBookingFormSettings(page) {
  await gotoTab(
    page,
    TABS.configuration,
    '&bfw_sub_nav=mwb-bookings-for-woocommerce-booking-form-settings'
  );
}

const TAXONOMIES = {
  cost: 'mwb_booking_cost',
  service: 'mwb_booking_service',
};

async function gotoTaxonomyTab(page, taxonomySlug) {
  await gotoTab(page, TABS.configuration, `&bfw_sub_nav=${taxonomySlug}`);
}

async function gotoHomePage(page) {
  await page.goto('wp-admin/admin.php?page=home');
}

/** Reads a WordPress option value directly via the options.php export trick is overkill;
 * instead we assert on rendered form state after a fresh page load, which reflects get_option(). */
async function reload(page, tabKey, extraQuery = '') {
  await gotoTab(page, tabKey, extraQuery);
}

/**
 * The plugin's "radio-switch" fields render the real <input type=checkbox> with
 * width/height 0 and opacity 0 (a custom-styled toggle) so it's not directly clickable.
 * Click the visible parent <label class="wps-toggle"> instead, which natively toggles
 * the checkbox it wraps.
 */
async function clickToggle(page, id) {
  await page.locator(`label.wps-toggle:has(#${id})`).click();
}

module.exports = {
  PLUGIN_PAGE,
  TABS,
  TAXONOMIES,
  gotoTab,
  gotoBookingFormSettings,
  gotoTaxonomyTab,
  gotoHomePage,
  reload,
  clickToggle,
};
