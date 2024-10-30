=== Linear ===
Contributors: linearoy
Tags: retail agency, real estate, apartments, gutenberg, blocks
Requires at least: 6.2
Tested up to: 6.6
Stable tag: 2.7.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Linear WP plugin allows you to integrate your website into Linear real estate system, easily displaying data of the listings you are selling.

== Description ==
This plugin requires the use of 3rd party service i.e., Linear.fi services in order to work. Plugin acts as an interface for linear.fi real estate CRM and it provides functionality to display company listings on your own WordPress site.

With this plugin you can add archive of your listings on any WordPress page without manually duplicating the content from your real estate management system on your WordPress page. Just configure the plugin in the settings page and everything will be done for you.
Archive page template is made to be compatible with every WordPress theme with some customizable styling features. Selected page will display all listings that are visible under selected company's dixu.fi profile.
This will enable your visitors to filter, search for and read about apartments on sale on your website.

The plugin may display some links that can take visitors to the Dixu.fi listing site where potential home buyers can interact with the seller/realtor, apply for a mortgage, download apartment documents and make a digital offer for the apartment.
The User is responsible for installing the plugin and is liable for any direct or indirect expenses to themself caused by using the plugin.
Linear is not responsible if any problems occur and is not responsible to fix or develop the plugin. Users may use the plugin at their own discretion.

== Installation ==
To install plugin:
1. Upload plugin folder to the `/wp-content/plugins/` directory or install it through WordPress dashboard.
2. Activate the plugin through the 'Plugins' menu in WordPress. On activation plugin will create 'Apartments' page for listings.
3. Go to 'Linear' settings page in admin dashboard and fill 'Company ID', 'API url' and 'API key' data and submit the form. The Company ID, API URL and API KEY should be requested from Linear customer support.
4. If you added correct data to settings page, listings for your company from Linear real estate system will be listed automatically.
5. If your site supports Gutenberg blocks, you can use the 'Linear listing'-block wherever you want

== Frequently Asked Questions ==

= I don't see my listings on 'Apartments' page, what to to? =

Check that you set correct 'Company ID', 'API url' and 'API key' in 'Linear' settings page, if not an error message will be displayed on 'Apartments' page.

= 'Apartments' has listings from another user, how to make it display my listings? =

Go to 'Linear' settings page and check if you set correct 'Company ID' If not, get it from Linear support.

= How can I display 'Buy commissions' as they don't appear automatically? =

'Buy commissions' is a part of the opt-in features of our plugin. Firstly you need to create a empty page (preferably with the page template "linear-plain") and then set that page in 'Linear' settings page as the page for 'Buy commissions'.

= I changed the language in WP-Admin and lost 'Linear' settings page settings, why does this happen? =

If your site uses Polylang or WPML, our plugin saves some values separately depending on language. Values like API-key are the same despite the language but e.g. 'Listings' page needs to be set separately for each language.

= The 'Listing' page seems too thin/wide, how can I change this? =

Our plugin tries to stay avay from deciding how your pages should look like and tried to inherit styles from the used WordPress theme. We have a page-template that you can set your page to use for a plain experience with enough width, it's called 'Linear plain'. You can also use any other page-template with out 'Listing' pages that try to render the WordPress page native content (the_content()).

== Changelog ==

= 2.7.11 =
*Release date 2nd October 2024*

* Tweak - Added captions to carousel images
* Fix - Updated Mapbox API key

= 2.7.10 =
*Release date 17th April 2024*

* Bug - Fixed listings not ordered newest first when listing multiple companies on one page

= 2.7.9 =
*Release date 12th February 2024*

* Tweak - Added a new filter hook "edit_linear_listing"

= 2.7.8 =
*Release date 19th January 2024*

* Tweak - Improved compability with Divi theme
* Bug - Hidden zeroed values

= 2.7.6 =
*Release date 5th January 2024*

* Tweak - Adjusted single listing fields to map closer to what's shown on Oikotie

= 2.7.5 =
*Release date 5th January 2024*

* Bug - Fixed issue when trying to load a Elementor editor with shortcode

= 2.7.4 =
*Release date 13th December 2023*

* Bug - Fixed price- , rent-  and areasliders to have min and maxvalues not filtering results

= 2.7.3 =
*Release date 12th December 2023*

* Tweak - Adjusted area display for business premises

= 2.7.2 =
*Release date 20th November 2023*

* Bug - Fixing broken videoembeds on single listing pages

= 2.7.1 =
*Release date 15th November 2023*

* New - Shortcode filter support for realtor filtering
* Tweak - Removed unused settings
* Tweak - Updated translations

= 2.7.0 =
*Release date 9th November 2023*

* New - Support for external embedable form for single listings, primarly supporting Linear Script solutions
* Bug - Adjust filters in mobile
* Tweak - Adjusted Realtor-block logic
* Tweak - Tested up to WordPress 6.4
* Tweak - Updated translations

= 2.6.13 =
*Release date 26th October 2023*

* Bug - Adjusted asset loading to be more precise

= 2.6.12 =
*Release date 26th October 2023*

* Tweak - Updated translations

= 2.6.11 =
*Release date 26th October 2023*

* Tweak - Adjusted map to only load on user request to avoid unnecessary mapbox polling

= 2.6.10 =
*Release date 17th October 2023*

* Tweak - Adjusted how matterport-views are shown in the image slider

= 2.6.9 =
*Release date 4th July 2023*

* Tweak - Adjusted listings filters logic to be slightly more flexible for various shortcode configurations

= 2.6.8 =
*Release date 22nd June 2023*

* Bug - Fixed potential language errors with WPML

= 2.6.7 =
*Release date 16th June 2023*

* Bug - Fixed typos
* Bug - Adjusted language-logic to not fail hard

= 2.6.6 =
*Release date 14th June 2023*

* Bug - Fixed issue with bidding listings
* Bug - Improved documentation for shortcode usage
* Tweak - Updated translations

= 2.6.5 =
*Release date 7th June 2023*

* Bug - Fixed default listing-amount for various column-amounts
* Bug - Fixed un-even columna mount default listings-per-page to show always full rows of listings
* Tweak - Fixed readme

= 2.6.4 =
*Release date 2nd June 2023*

* Tweak - Adjusted plugin loading order
* Tweak - Adjusted plugin compability
* Tweak - Added potential plugin extension support
* Bug - Fixed PHP Notice with contact method
* Tweak - Potential fix for text overlaying floorplan

= 2.6.3 =
*Release date 25th May 2023*

* Bug - Fixed issue content breaking upon plugin activation
* Tweak - Adjusted translations

= 2.6.2 =
*Release date 18th May 2023*

* Bug - Improved concurrent request for buy-commissions
* Bug - Fixed some PHP Notices in buy commissions

= 2.6.1 =
*Release date 18th May 2023*

* Bug - Fixed potential risk for concurrent API requests

= 2.6.0 =
*Release date 16th May 2023*

* New - Option to activate a contact-form in single listings, requires adjusting plugin settings (disabled until further notice)
* New - Linear plugin cache remove tool, found under the plugin "debug" page
* Tweak - Adjusting logic of some data-fields of single listings
* Tweak - Various small tweaks
* Tweak - Updating translations
* Tweak - Added some CSS-classes for easier content targetting

= 2.5.28 =
*Release date 2nd May 2023*

* Bug - Fixed some PHP notices
* Tweak - Improved flexibility of API-key format
* Tweak - Tweaked PHP support for older versions

= 2.5.27 =
*Release date 2nd May 2023*

* Bug - Fixed occasionally occuring JS-issue with listings and buy-commission blocks

= 2.5.26 =
*Release date 24th April 2023*

* Bug - Potentially fixing a bug where cache delivers weirdly bundled JS

= 2.5.25 =
*Release date 21st April 2023*

* Bug - Fixing issue where square price was calculated wrong

= 2.5.24 =
*Release date 30th March 2023*

* Bug - Removing some unecessary fields from Detached houses info

= 2.5.23 =
*Release date 22nd March 2023*

* Bug - Fixing elements not shown on single buy-commissions
* Tweak - Filtered buy-commissions to only show ones with basic information set
* Tweak - Adjusted single listing mandatory charges specification in introduction

= 2.5.22 =
*Release date 15th March 2023*

* Bug - Potentially fixing single listing breaking bug

= 2.5.21 =
*Release date 15th March 2023*

* Bug - Modified previously inserted unique classNames to fix false classNames

= 2.5.20 =
*Release date 13th March 2023*

* Tweak - Added individual CSS-classes to accordion elements for easier targetting or hiding
* Tweak - Added more filters for business premises
* Tweak - Hidden additional fees on single listing if they were free of charge

= 2.5.19 =
*Release date 8th March 2023*

* Tweak - Bumped version number to fix update bug

= 2.5.18 =
*Release date 7th March 2023*

* New - Updated Linear logos
* Tweak - Adjusted realtor-block visual logic

= 2.5.17 =
*Release date 13th February 2023*

* Tweak - Adjusted realtor-block "seller" text
* Tweak - Translations

= 2.5.16 =
*Release date 10th February 2023*

* Bug - Fixed missing translations
* New - Support for upcoming data-field "Other buildings"
* Tweak - Minor style adjustments

= 2.5.15 =
*Release date 9th February 2023*

* Bug - Fixed issue with missing showing date
* Bug - Fixed some linea breaking issues
* Bug - Fixed issues where business listings and/or rentals were shown incorrect data

= 2.5.14 =
*Release date 7th February 2023*

* Bug - Fixed issue with some fields missing from business listings

= 2.5.13 =
*Release date 3rd February 2023*

* Bug - Fixed issue with area-range filter

= 2.5.12 =
*Release date 3rd February 2023*

* New - Added listing count to listings
* Tweak - Updated buy-commissions documentation

= 2.5.11 =
*Release date 30th January 2023*

* Tweak - Fixed issue with some email-encoding plugins

= 2.5.10 =
*Release date 27th January 2023*

* Bug - Fixed missing translations

= 2.5.9 =
*Release date 27th January 2023*

* New - Added single listing content hooks, more about these in "advanced usage"
* Tweak - Added realtor contact information to realtor-block in single listings
* Tweak - Minor styles adjustments
* Tweak - Added more populated URL-parameters for "contact" buttons
* Tweak - Clarified search-field placeholder
* Tweak - Added placeholders for plugin credentials
* Tweak - Improved business listings logic
* Tweak - Added "Ask price" for priceless listings
* Bug - Fixed sliders input field weird behaviour
* Bug - Fixed missing translations

= 2.5.8 =
*Release date 24th January 2023*

* Tweak - Updated listings gutenberg block to be up to date with shortcode options
* Tweak - Updated shortcode documentation

= 2.5.7 =
*Release date 20th January 2023*

* New - Added option to modify the rendering-method between the_content and shortcode
* New - Additional pre-set filter values for shortcodes
* Tweak - Updated shortcodes documentation
* Tweak - Added missing translations
* Tweak - Added custom CSS short instructions
* Tweak - Added identifier as CSS-class to every rendered listing
* Bug - Fixed field key wrapping bug

= 2.5.6 =
*Release date 17th January 2023*

* Bug - Fixed commissions type not populating to URL params

= 2.5.5 =
*Release date 17th January 2023*

* Tweak - Updated presentations logic
* Bug - Fix minor bug with commission type filter
* Tweak - Updated shortcodes documentation

= 2.5.4 =
*Release date 16th January 2023*

* Tweak - Improved support for Divi-based themes
* Bug - Fixed typos

= 2.5.3 =
*Release date 13th January 2023*

* Bug - Fixed base filtering logic, some listings were not shown

= 2.5.2 =
*Release date 13th January 2023*

* Tweak - Updated shortcode guide (partially only English)
* Bug - Fixed issue with certain filters bugging in the listings

= 2.5.1 =
*Release date 12th January 2023*

* New - Options for managing listings column count
* Tweak - Translations

= 2.5.0 =
*Release date 12th January 2023*

* New - Reworked filters logic, now they should work more reliable and there are more arguments to use
* New - Area/Size slider implemented, need to atm be implemented via shortcode with param 'area_range="true"'
* New - You can now set the slider-filters min/max values in Linear plugin settings
* Bug - Fix conflict with Elementor themes menus
* Bug - Fix fullscreen image gallery mobile scaling to some extent

Note: The documentation will be improved

= 2.4.2 =
*Release date 3rd January 2023*

* Tweak - Better support for various Gutenberg and Elementor themes

= 2.4.1 =
*Release date 22nd December 2022*

* New - Plugin supports hiding Dixu-links. Please apply the settings according to your liking in listings in Linear, and hide "load request" button in Linear plugin options

= 2.4.0 =
*Release date 20th December 2022*

* New - Restructured Linear plugin to appear via the_content hook rather than via template
* New - Supports Gutenberg FSE sites
* Tweak - Adjusting fields visibility depending on e.g. rental or detached house
* Tweak - Adjusting translations
* Tweak - Workarounds for wpautop
* Tweak - Changed templates to be opt-in instead of opt-out
* Tweak - Various tweaks in logic

= 2.3.5 =
*Release date 14th December 2022*

* Tweak - Fixed rental text and added translation

= 2.3.4 =
*Release date 7th December 2022*

* Bug - Advanced usage had some faulty instructions, those are now fixed

= 2.3.3 =
*Release date 7th December 2022*

* New - Additional options available for listings shortcodes
* Tweak - Improve filters logic, sometimes caused an warning
* Tweak - Update advanced usage guide

= 2.3.2 =
*Release date 2nd December 2022*

* New - Additional options for Gutenberg blocks and shortcodes, now you can pre-set the search field value
* Tweak - Additional translations
* Tweak - Updated advanced usage page

= 2.3.1 =
*Release date 28th November 2022*

* New - Full Translatepress support
* New - Additional filter for Gutenberg and shortcode listings when listing everything, filtering between sales and rental listings
* New - Added URL-parameters to custom contact link for easier integration in contact forms
* Bug - Minor bug fixes and style tweaks
* Bug - Fixed potential duplicate listings issue in sitemap.xml
* Tweak - Showing now the listing seller according to what is set in Linear, realtor or company
* Tweak - Minor style adjustements

= 2.3.0 =
*Release date 23rd November 2022*

* New - Removing support for old API calls, please make sure you have set the proper API-URL and API-Key.
* New - Now using shorter IDs for URLs, with fallback support for old IDs
* New - Added search-functionality to listings, is visible if no predefined values are set
* Bug - Fixed various fields that were wrong or should not be seen in rental listings
* Bug - Fixed missing realtor info on single listing
* Bug - Fixed missing rent price
* Bug - Fixed missing translation
* Tweak - Renames apartments to block of flats in filters
* Tweak - Added CSS-classes to filters, can now be easily targeted via CSS if you want to hide some of them

= 2.2.4 =
*Release date 10th November 2022*

* Tweak - Improved multilang-site plugin Installation logic
* Tweak - Page-template population upon Linear settings change
* Tweak - Improved guide page with troubleshooting section

= 2.2.3 =
*Release date 8th November 2022*

* Tweak - Full WPML support
* Bug - Fixed issue with buy-commissions
* Bug - Included Translatepress support, listings work on all languages but single listings only on one language
* Bug - Improved logic for translation management, performs better now

= 2.2.2 =
*Release date 2nd November 2022*

* Bug - Fixed broken API-links
* Bug - Improved some polylang single page performance

= 2.2.1 =
*Release date 28th October 2022*

* New - Added a instructions page to WordPress Admin
* Bug - Fixed bug with range-filters show/hide not working
* Bug - Fixed translation issue with HTML-entities
* Bug - Improved map-location accuracy
* Tweak - Tested for WordPress 6.1
* Tweak - Adjusted SeoPress meta plugin listing images
* Tweak - Supporting listings with ugly permalinks

= 2.2.0 =
*Release Date 26th October 2022*

* New - Supporting buy commissions (listing, gutenberg block, shortcode, single page, instructions, REST-API)
* New - Polylang support
* New - Partial WPML support
* New - Added support for sub-pages to be listing pages
* Tweak - Updated available filters to easier scale up if needed
* Tweak - Improved permalink-flushing rules
* Tweak - Updated WordPress plugin page instructions
* Tweak - Added additional filters for rentals
* Tweak - Various small tweaks and bugfixes
* Tweak - Updated WP Plugin repository FAQ
* Bug - Fixed rent-price filter max-price bug, now max-value doesn't filter max price

= 2.1.13 =
*Release Date 12th October 2022*

* Bug - Fixed issue with bidding-listings
* Tweak - Reordered accomodation specs
* Tweak - Improved social media sharing
* Tweak - Various minor style tweaks
* Tweak - Better inheritance for Elementor button styles
* Tweak - Support for listings pages as sub-pages

= 2.1.12 =
*Release Date 16th September 2022*

* Tweak - Fixed compability issue with Oxygen Builder sites (to some extent)
* Tweak - Cleaned codebase from legacy features
* Tweak - Adjusted listings template

= 2.1.11 =
*Release Date 1st September 2022*

* Bug - Potentially fixed balcony bug
* Bug - Handled some potential error situations
* Tweak - Adjusted permalinks to have the listing type properly included.
* Tweak - Added more detailed maintenance charges to the main specifications
* Tweak - Improved initial setup for Gutenberg sites, autofetch theme color

= 2.1.10 =
*Release Date 10th August 2022*

* Bug - Fixed biddings-tag color

= 2.1.9 =
*Release Date 10th August 2022*

* Bug - Fixed minor JS issue

= 2.1.8 =
*Release Date 8th August 2022*

* Tweak - Adjusted styles and improved listings empty results output
* Tweak - Cleaned codebase
* Tweak - Improved caching-logic, conflicted partially with settings changes
* Tweak - Fixed bidding pricing and added tags for clarity
* Tweak - Added floorplan to content
* Tweak - Various style improvements
* Tweak - Moved suggestions to its own sub-page in WP-Admin, feel free to leave feedback :)

= 2.1.7 =
*Release Date 2nd August 2022*

* Tweak - Adjusted lots of stylings and paddings
* Tweak - Improved Security deposit formatting
* Tweak - Added missing energy-info
* Bug - Fixed conflict with plugin email-encoder-bundle

= 2.1.6 =
*Release Date 29th July 2022*

* Bug - Fixed share URLs

= 2.1.5 =
*Release Date 28th July 2022*

* New - Social share buttons on single listings
* Bug - Fixed accordion buggy behaviour when element was abused
* Tweak - Added sitename to og:title
* Tweak - Minor styling and formatting fixes
* Bug - Fixed issues with certain listings filters

= 2.1.4 =
*Release Date 26th July 2022*

* Tweak - Style fixes
* Tweak - Clarifying admin settings and advanced usage guide
* Bug - Fixed broken locale translations
* Bug - Fixed page-scrolling issue upon selecting filters

= 2.1.3 =
*Release Date 22th July 2022*

* Tweak - Stable 2.1.X version

= 2.1.0 =
*Release Date 22th July 2022*

* Tweak - Refactored templates
* Tweak - Refactor accordion-features
* Bug - Fixed broken rent-price filter
* Bug - Replaced broken terms of use link
* Tweak - Adding listings to sitemap, exceptions for some SEO-plugins
* Tweak - Fixed translations
* Bug - Better error-handling for faulty API-keys
* Bug - Fixed formatting of phone numbers
* Bug - Fixed issue with shortcode parameters

= 2.0.16 =
*Release Date 30th Juny 2022*

* Bug - Fixed bug with roomCount calculation

= 2.0.15 =
*Release Date 28th Juny 2022*

* Bug - Adding missing labels and constants
* Tweak - Updated translations

= 2.0.14 =
*Release Date 27th Juny 2022*

* Bug - Adding missing labels and constants
* Tweak - Updated translations

= 2.0.13 =
*Release Date 27th Juny 2022*

* Bug - Adjusting conflicting CSS-styles
* Bug - Fixing missing newlines in longers texts

= 2.0.12 =
*Release Date 22nd Juny 2022*

* Bug - Fixed apartments listing missing results

= 2.0.11 =
*Release Date 22nd Juny 2022*

* Bug - Reseting URL parameters upon all filters reset

= 2.0.10 =
*Release Date 22nd Juny 2022*

* New - Used filters are now saved as URL-parameters, you can now share pages with certain filterings 
* Tweak - Bringing back the option to set search keywords, supports also addresses and districts
* Bug - Disabling locale-language language user for API v2, only using "fi" until API develops further
* Bug - Easing on type declarations for potentially fixing issue
* Bug - Orderby for listings missing in page-template use

= 2.0.9 =
*Release Date 21st Juny 2022*

* Bug - Hotfix, made middleware requirments easier for permalink building, disabling Gutenberg link disabler

= 2.0.8 =
*Release Date 20th Juny 2022*

* Tweak - Cleaning code
* Bug - Fixed potential issue with middleware permalink building

= 2.0.7 =
*Release Date 20th Juny 2022*

* Bug - Hotfix for other types of "apartments" eg. plots, farms, garages and vacation apartments

= 2.0.6 =
*Release Date 20th Juny 2022*

* Tweak - Changed how localized scripts are loaded due to certain caching/optimizing plugins breaking default behaviour

= 2.0.5 =
*Release Date 17th Juny 2022*

* Bug - Fixed faulty REST-API url in options page

= 2.0.4 =
*Release Date 17th Juny 2022*

* New - REST-endpoints and WordPress filters for getting listings or single listing data, instructions added to options page
* Bug - Hotfix for newly constructed apartments
* Bug - Minor frontend tweaks, price formatting

= 2.0.3 =
*Release Date 16th Juny 2022*

* Tweak - Range-slider filters didn't format the price/rent fields, added formatting of numbers
* Bug - Fix security deposit logic

= 2.0.2 =
*Release Date 16th Juny 2022*

* Bug - Mobile filters accordion button titles were wrong, adjusted titles
* Bug - Single listing didn't show next presentation date-time, fixed
* Dev - New page template for old listings to better work with themes that use narrow content
* Bug - Various bug fixes

= 2.0.1 =
*Release Date 15th Juny 2022*

* Bug - Fixed broken new API credentials request mailto-link prepopulated text + translations

= 2.0.0 =
*Release Date 15th Juny 2022*

* Dev - Added API-key field in options, required in future for the plugin to work
* Dev - Refactored listings, now more reliable and work faster when adjusting filters
* Dev - Refactored middleware, transients reset on options change and plugin update
* Dev - Support for assing listings via shortcodes or Gutenberg blocks
