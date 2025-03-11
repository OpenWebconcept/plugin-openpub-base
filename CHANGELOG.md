# Changelog

## v3.5.3

- Fix: translations just in time error

## v3.5.2

- Feat: add settings API endpoint. @richardkorthuis (#31)

## v3.5.1

- Refactor: Remove optional params from fillPostName called by 'wp_insert_post_data' hook

## v3.5.0

- Feat: add filter for altering the post types config

## v3.4.8

- Fix: Fix composer.lock on PHP 7.4 rather than PHP 8.1

## v3.4.7

- Refactor: openpub-show-on taxonomy capabilities to 'manage_options'

## v3.4.6

- Refactor: enable openpub-show-on taxonomy in REST

## v3.4.5

- Chore: Updated elasticpress to v5

## v3.4.4

- Refactor: CMB2 'show on' taxonomy field type from 'select_advanced' to 'taxonomy_multicheck'

## v3.4.3

- Chore: Updated CMB2 to version 2.11.0

## v3.4.2

- Fix: Ensure that only authenticated users can access draft preview items in the API response

## v3.4.1

- Feat: Enable filtering on taxonomy 'openpub-audience'
- Feat: Add 'date_modified_gmt' to the API response
- Feat: Add translation for 'Author'

## v3.4.0

- Feat: Support for multiple type params in tax query used in API responses

## v3.3.3

- Feat: DateTime modifier could also be a negative integer inside Item repository class.

## v3.3.2

- Fix: Expiration Parameters because the CMB2 field type 'text_datetime_timestamp' is not working correctly.

## v3.3.1

- Fix: Comparing expiration date with 'now' is done wrongly by comparing different timezones.

## v3.3.0

- Feat: Add 'date_modified' to theme endpoints.
- Feat: Add 'yoast' to theme endpoints.

## v3.2.0

- Feat: Add endpoint to search for themes by their slug.

## v3.1.3

- Fix: Duplicated post_names when multiple items had the same post_title.

## v3.1.2

- Fix: Restore some of the documented actions and filters.

## v3.1.1

- Feat: Always fill the post_name when an openpub-item is saved, is required for previewing openpub-items.

## v3.1.0

- Feat: Fill the post_name when an openpub-item is not published, is required for previewing openpub-items.
- Feat: Upgrade admin notices.

## v3.0.2

- Feat: Always return date_modified in API.

## v3.0.1

- Feat: Add future post status when retrieving item for draft preview.

## v3.0.0

- Feat: Implement CMB2 metabox plugin.
- Chore: Clean-up/refactoring.

## v2.3.3

- Feat: Related items type taxonomy args.

## v2.3.2

- Feat: Added the 'johnbillion/extended-cpts' package as dependency to composer.json.
- Chore: Update dependencies.
- Chore: Directory name of plugin in README.md.

## v2.3.1

- Fix: checkForUpdate() inside Plugin class should not run when class is being extended.

## v2.3.0

- Feat: Updates can now be provided through the Admin interface.

## v2.2.2

- Chore: Change the settings page identifier to prevent collision with OpenPDC.

## v2.2.1

- Feat: Filter items, in API, on type taxonomy slug when param is set.

## v2.2.0

- Feat: Support PHP 8
- Chore: Update dependencies.

## v2.1.1

- Feat: Remove Elasticpress settings when plugin yard-elasticsearch is active

## v2.1.0

- Feat: Include SEO meta fields, provided by multiple plugins, in API.

## v2.0.20

- Chore: Taxonomy openpub-show-on change capabilities from manage_options to manage_categories.

## v2.0.19

- Chore: Update dependencies.

## v2.0.18

- Feat: Generating portal URL.

### Fix

- Fix: Return value in filter 'post_type_link' registered in '\OWC\OpenPub\Base\Admin\AdminServiceProvider::class.

## v2.0.17

- Feat: Set argument public to false for the CPT's 'openpub-theme', 'openpub-subtheme' and 'openpub-location' so ElasticPress does not include them in the sync.

## v2.0.16

- Feat: Add featured image field to related items

## v2.0.15

- Feat: Change 'preview' parameter into 'draft-preview'

## v2.0.14

- Fix: Append id for pdc draft previews

## v2.0.13

- Fix: Elasticpress indexables posttypes and statuses hooks was not correctly returned.

## v2.0.12

- Feat: Existing OpenPub items without an expiration date will be assigned a value of the published date plus the value given in days.
- Feat: New OpenPub items will have a value of the current date plus the value given in days.

## v2.0.11

- Fix: Meta-query with multiple arguments not working correctly.

## v2.0.10

- Feat: Add preview parameter for retrieving drafts
- Feat: Purge Varnish on save_post
- Fix: Add addHighlightedParameters to active items

## v2.0.9

- Feat: Add author column for openpub items

## v2.0.8

- Feat: Add explanation to 'show on' taxonomy form.
- Feat: Only use 'show on' filtering in endpoints when setting is active.
- Feat: Filter related items on source slug when param is set.

## v2.0.7

- Feat: Add filtering on 'show on' to active items endpoint.
- Feat: 'show on' taxonomy is only allowed to be managed by administrators.

## v2.0.6

- Refactor: Filtering on 'show on' in items endpoint from string to numeric value.

## v2.0.5

- Feat: Add 'show on' setting to openpub-settings.
- Feat: Add 'show on' setting in editor of openpub-item.
- Feat: Add filtering on 'show on' to items endpoint.

## v2.0.4

- Feat: Add escape element setting to openpub-settings.
- Feat: Add escape element setting in editor of openpub-item.

## v2.0.3

- Fix: Filter inactive items

## v2.0.2

- Feat: Add slug to related item in API.
- Feat: Add multiple unit tests.

## v2.0.1

- Feat: Add full image size to attachment meta
- Feat: Add expiration parameters to related items

## v2.0.0

- Chore: Clean-up for version 1.0.
- Feat: Add settings on settings page.
- Feat: Add SettingsPageOptions model.
- Feat: Add portal_url to API output on conditional.
- Feat: Add date_modified to API output on conditional.
- Fix: Base settings on settings page.

## v1.2.0

- Feat: Comments to items.

## v1.1.9

- Feat: Highlighted parameter on rest endpoint.

## v1.1.8

- Feat: Add correct mappings for ElasticSearch.

## v1.1.7

- Feat: Add openpub rest route route on slug.

## v1.1.6

- Feat: Add add thumnbail url to related posts.

## v1.1.5

- Fix: Standardize expired date for better compatibility.

## v1.1.4

- Feat: Add make posttypes and taxonomies available in REST API.

## v1.1.3

- Feat: Add make taxonomy "openpub-type" available in REST API.

## v1.1.2

- Feat: Add endpoint for active items only: `wp-json/owc/openpub/v1/items/active`.

## v1.1.1

- Fix: Remove unwanted redirect.

## v1.1.0

- Fix: Remove unwanted exit.
- Fix: Related items of theme REST API.

## v1.0.9

- Chore: Remove Hooks class.

## v1.0.8

- Feat: Add query args for REST API.
- Update: PHPunit 8.

## v1.0.7

- Fix: Check if required file for `is_plugin_active` is already loaded, otherwise load it. _Props @Jasper Heidebrink_

## v1.0.6

- Feat: Add openpub endpoint description for documentation.

## v1.0.5

- Feat: Add default order of published date.

## v1.0.4

- Feat: Add filter options to REST API.

## v1.0.3

- Update: Languages
- Feat: Add highlighted item to API.

## v1.0.2

- Update: Languages
- Change: Format of REST API to follow WP_Post for elasticsearch
- Chore: Remove unused setting tab

## v1.0.1

- Feat: Add docs.
- Feat: Add PHP style linter.
- Change: Add REST API output to follow WP_Post.

## v1.0.0

- Initial release.

 The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
