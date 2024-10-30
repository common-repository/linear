=== Online Schedule ===
Contributors:      Oliver Granlund
Tags:              block
Tested up to:      5.9
Stable tag:        0.1.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

A block to show buy commissions. Works identically in WP-Admin and frontend. Includes settings for pre-set values.

== Features ==

- Fetches buy commissions
- Pre-settable values
- 2-layer React state to manage easier WP-Admin values and the React App itself
- Injects PHP/Global variables/data via Admin_Head and WP_Localize_script
- Contains its own React bundler

== Key files and folders ==

=== linear-commissions.php ===

The main file for the plugin. Sets and maps data, enqueues.

=== config.json ===

Skeleton for global values, could maybe be removed at some point.

=== src/edit.js ===

The main file for managing WP-Admin plugin state, Gutenberg settings and attribute-logic. Brings in <App /> from frontend.

=== src/save.js ===

Very minimal file that saves the set edit.js values to data-attributes.

=== src/block.json ===

The plugin data + default attributes.

=== src/frontend ===

The folder for the React App. index.js is only used for frontend element building. App.js contains the logic itself.