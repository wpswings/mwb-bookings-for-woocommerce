const path = require('path');
const { execFileSync } = require('child_process');

function togglePlugin(action) {
  const slug = process.env.PRO_PLUGIN_SLUG;
  const wpLoadPath = process.env.WP_LOAD_PATH;
  const phpBin = process.env.PHP_BIN;

  if (!slug || !wpLoadPath || !phpBin) {
    console.log('[pro-plugin-control] PRO_PLUGIN_SLUG/WP_LOAD_PATH/PHP_BIN not fully set, skipping.');
    return;
  }

  const scriptPath = path.join(__dirname, 'toggle-pro-plugin.php');
  try {
    const output = execFileSync(phpBin, [
      ...(process.env.PHP_INI ? ['-c', process.env.PHP_INI] : []),
      scriptPath,
      action,
      wpLoadPath,
      slug,
    ], {
      env: { ...process.env, LD_LIBRARY_PATH: process.env.PHP_LIB_PATH || '' },
      encoding: 'utf-8',
    });
    console.log(`[pro-plugin-control] ${action}: ${output.trim()}`);
  } catch (err) {
    console.warn(`[pro-plugin-control] Failed to ${action} ${slug}: ${err.message}`);
  }
}

module.exports = { togglePlugin };
