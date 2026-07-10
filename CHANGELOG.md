# Changelog

## [v3.9.2] - 2026-07-10

- Perf: replace expiration meta_query with LEFT JOIN to avoid forced GROUP BY

## [v3.9.1] - 2026-07-07

- Chore: cache connected item fields in API routes

## [v3.9.0] - 2026-07-07

- Feat: add related items filtering on type tax when setting enabled
- Fix: tests

## [v3.8.7] - 2026-06-10

- Fix: add psr/container

## [v3.8.6] - 2026-06-10

- Chore: use 10up/elasticpress

## [v3.8.5] - 2026-06-10

- Fix: Restore default expiration dates feature

## [v3.8.4] - 2026-06-02

- Chore: update cmb2, move to wp-packages over wp-packagist

## [v3.8.3] - 2026-03-19

- Chore: Revert to wp-packagist version of 10up/elasticpress due to psr/container conflicts

## [v3.8.2] - 2026-03-19

- Fix: Add php version constraints

## [v3.8.1] - 2026-03-17

- Chore: Remove plugin repositories in favour of direct composer support

## [v3.8.0] - 2026-01-13

- Feat: add filter dropdown for easy filtering of target website @rmpel (#45)

## [v3.7.7] - 2025-08-15

- Feat: add query parameters for taxonomies added through apply_filter

## [v3.7.6] - 2025-07-17

- Feat: add taxonomies to API added through apply_filter

## [v3.7.5] - 2025-07-16

- Feat: add author id in API response of items endpoint

## [v3.7.4] - 2025-07-03

- Feat: apply filter before registering extended taxonomies

## [v3.7.3] - 2025-03-28

- Feat: getShowOnPortalURL(): add filter and sorting for portalUrls by numeric blogId with taxonomy 'openpub-show-on'

## [v3.7.2] - 2025-03-28

- Refactor: register rest routes in correct order

## [v3.7.1] - 2025-03-25

- Fix: autoloader

## [v3.7.0] - 2025-03-20

- Feat: update extended-cpts to v5

## [v3.6.1] - 2025-03-18

- Feat: use the correct portal URL if the Show On functionality is used. (Partly copied from PDC Base) @richardkorthuis (#37)

## [v3.6.0] - 2025-03-13

- Feat: added wp_reset_postdata() @eyal (#34)
- Feat: setup global postdata for OpenPub item @eyal (#34)
- Feat: update SettingsController.php @eyal (#35)

## [v3.5.3] - 2025-03-11

- Fix: translations just in time error

## [v3.5.2] - 2025-02-10

- Feat: add settings API endpoint. @richardkorthuis (#31)

## [v3.5.1] - 2024-10-30

- Refactor: Remove optional params from fillPostName called by 'wp_insert_post_data' hook

## [v3.5.0] - 2024-10-23

- Feat: add filter for altering the post types config

## [v3.4.8] - 2024-09-20

- Fix: Fix composer.lock on PHP 7.4 rather than PHP 8.1

## [v3.4.7] - 2024-06-26

- Refactor: openpub-show-on taxonomy capabilities to 'manage_options'

## [v3.4.6] - 2024-06-21

- Refactor: enable openpub-show-on taxonomy in REST

## [v3.4.5] - 2024-06-17

- Chore: Updated elasticpress to v5

## [v3.4.4] - 2024-04-26

- Refactor: CMB2 'show on' taxonomy field type from 'select_advanced' to 'taxonomy_multicheck'

## [v3.4.3] - 2024-04-05

- Chore: Updated CMB2 to version 2.11.0

## [v3.4.2] - 2024-01-02

- Fix: Ensure that only authenticated users can access draft preview items in the API response

## [v3.4.1] - 2023-11-07

- Feat: Enable filtering on taxonomy 'openpub-audience'
- Feat: Add 'date_modified_gmt' to the API response
- Feat: Add translation for 'Author'

## [v3.4.0] - 2023-09-27

- Feat: Support for multiple type params in tax query used in API responses

## [v3.3.3] - 2023-09-14

- Feat: DateTime modifier could also be a negative integer inside Item repository class.

## [v3.3.2] - 2023-09-13

- Fix: Expiration Parameters because the CMB2 field type 'text_datetime_timestamp' is not working correctly.

## [v3.3.1] - 2023-09-11

- Fix: Comparing expiration date with 'now' is done wrongly by comparing different timezones.

## [v3.3.0] - 2023-08-22

- Feat: Add 'date_modified' to theme endpoints.
- Feat: Add 'yoast' to theme endpoints.

## [v3.2.0] - 2023-08-01

- Feat: Add endpoint to search for themes by their slug.

## [v3.1.3] - 2023-07-19

- Fix: Duplicated post_names when multiple items had the same post_title.

## [v3.1.2] - 2023-07-17

- Fix: Restore some of the documented actions and filters.

## [v3.1.1] - 2023-07-06

- Feat: Always fill the post_name when an openpub-item is saved, is required for previewing openpub-items.

## [v3.1.0] - 2023-06-20

- Feat: Fill the post_name when an openpub-item is not published, is required for previewing openpub-items.
- Feat: Upgrade admin notices.

## [v3.0.2] - 2023-05-26

- Feat: Always return date_modified in API.

## [v3.0.1] - 2023-04-21

- Feat: Add future post status when retrieving item for draft preview.

## [v3.0.0] - 2023-03-31

- Feat: Implement CMB2 metabox plugin.
- Chore: Clean-up/refactoring.

## [v2.3.3] - 2023-03-28

- Feat: Related items type taxonomy args.

## [v2.3.2] - 2023-03-21

- Feat: Added the 'johnbillion/extended-cpts' package as dependency to composer.json.
- Chore: Update dependencies.
- Chore: Directory name of plugin in README.md.

## [v2.3.1] - 2023-03-21

- Fix: checkForUpdate() inside Plugin class should not run when class is being extended.

## [v2.3.0] - 2023-03-06

- Feat: Updates can now be provided through the Admin interface.

## [v2.2.2] - 2023-02-14

- Chore: Change the settings page identifier to prevent collision with OpenPDC.

## [v2.2.1] - 2023-02-09

- Feat: Filter items, in API, on type taxonomy slug when param is set.

## [v2.2.0] - 2022-12-19

- Feat: Support PHP 8
- Chore: Update dependencies.

## [v2.1.1] - 2022-11-01

- Feat: Remove Elasticpress settings when plugin yard-elasticsearch is active

## [v2.1.0] - 2022-11-30

- Feat: Include SEO meta fields, provided by multiple plugins, in API.

## [v2.0.20] - 2022-11-16

- Chore: Taxonomy openpub-show-on change capabilities from manage_options to manage_categories.

## [v2.0.19] - 2022-10-07

- Chore: Update dependencies.

## [v2.0.18] - 2022-02-18

- Feat: Generating portal URL.

### [Fix]

- Fix: Return value in filter 'post_type_link' registered in '\OWC\OpenPub\Base\Admin\AdminServiceProvider::class.

## [v2.0.17] - 2022-01-28

- Feat: Set argument public to false for the CPT's 'openpub-theme', 'openpub-subtheme' and 'openpub-location' so ElasticPress does not include them in the sync.

## [v2.0.16] - 2021-12-10

- Feat: Add featured image field to related items

## [v2.0.15] - 2021-11-12

- Feat: Change 'preview' parameter into 'draft-preview'

## [v2.0.14] - 2021-10-19

- Fix: Append id for pdc draft previews

## [v2.0.13] - 2021-10-22

- Fix: Elasticpress indexables posttypes and statuses hooks was not correctly returned.

## [v2.0.12] - 2021-10-08

- Feat: Existing OpenPub items without an expiration date will be assigned a value of the published date plus the value given in days.
- Feat: New OpenPub items will have a value of the current date plus the value given in days.

## [v2.0.11] - 2021-07-15

- Fix: Meta-query with multiple arguments not working correctly.

## [v2.0.10] - 2021-07-12

- Feat: Add preview parameter for retrieving drafts
- Feat: Purge Varnish on save_post
- Fix: Add addHighlightedParameters to active items

## [v2.0.9] - 2021-07-09

- Feat: Add author column for openpub items

## [v2.0.8] - 2021-06-02

- Feat: Add explanation to 'show on' taxonomy form.
- Feat: Only use 'show on' filtering in endpoints when setting is active.
- Feat: Filter related items on source slug when param is set.

## [v2.0.7] - 2021-05-27

- Feat: Add filtering on 'show on' to active items endpoint.
- Feat: 'show on' taxonomy is only allowed to be managed by administrators.

## [v2.0.6] - 2021-05-27

- Refactor: Filtering on 'show on' in items endpoint from string to numeric value.

## [v2.0.5] - 2021-05-10

- Feat: Add 'show on' setting to openpub-settings.
- Feat: Add 'show on' setting in editor of openpub-item.
- Feat: Add filtering on 'show on' to items endpoint.

## [v2.0.4] - 2021-04-06

- Feat: Add escape element setting to openpub-settings.
- Feat: Add escape element setting in editor of openpub-item.

## [v2.0.3] - 2021-03-12

- Fix: Filter inactive items

## [v2.0.2] - 2021-02-26

- Feat: Add slug to related item in API.
- Feat: Add multiple unit tests.

## [v2.0.1] - 2021-01-08

- Feat: Add full image size to attachment meta
- Feat: Add expiration parameters to related items

## [v2.0.0] - 2020-11-25

- Chore: Clean-up for version 1.0.
- Feat: Add settings on settings page.
- Feat: Add SettingsPageOptions model.
- Feat: Add portal_url to API output on conditional.
- Feat: Add date_modified to API output on conditional.
- Fix: Base settings on settings page.

## [v1.2.0] - 2020-11-10

- Feat: Comments to items.

## [v1.1.9] - 2020-10-30

- Feat: Highlighted parameter on rest endpoint.

## [v1.1.8] - 2020-07-29

- Feat: Add correct mappings for ElasticSearch.

## [v1.1.7] - 2020-07-21

- Feat: Add openpub rest route route on slug.

## [v1.1.6] - 2020-07-17

- Feat: Add add thumnbail url to related posts.

## [v1.1.5] - 2020-05-27

- Fix: Standardize expired date for better compatibility.

## [v1.1.4] - 2020-03-05

- Feat: Add make posttypes and taxonomies available in REST API.

## [v1.1.3] - 2020-01-28

- Feat: Add make taxonomy "openpub-type" available in REST API.

## [v1.1.2] - 2020-01-17

- Feat: Add endpoint for active items only: `wp-json/owc/openpub/v1/items/active`.

## [v1.1.1] - 2019-10-25

- Fix: Remove unwanted redirect.

## [v1.1.0] - 2019-09-07

- Fix: Remove unwanted exit.
- Fix: Related items of theme REST API.

## [v1.0.9] - 2019-09-03

- Chore: Remove Hooks class.

## [v1.0.8] - 2019-08-02

- Feat: Add query args for REST API.
- Update: PHPunit 8.

## [v1.0.7] - 2019-05-02

- Fix: Check if required file for `is_plugin_active` is already loaded, otherwise load it. _Props @Jasper Heidebrink_

## [v1.0.6] - 2019-01-31

- Feat: Add openpub endpoint description for documentation.

## [v1.0.5] - 2018-12-05

- Feat: Add default order of published date.

## [v1.0.4] - 2018-11-22

- Feat: Add filter options to REST API.

## [v1.0.3] - 2018-11-15

- Update: Languages
- Feat: Add highlighted item to API.

## [v1.0.2] - 2018-11-01

- Update: Languages
- Change: Format of REST API to follow WP_Post for elasticsearch
- Chore: Remove unused setting tab

## [v1.0.1] - 2018-10-30

- Feat: Add docs.
- Feat: Add PHP style linter.
- Change: Add REST API output to follow WP_Post.

## [v1.0.0] - 2018-06-28

- Initial release.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
