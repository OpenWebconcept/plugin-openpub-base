# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Version [2.2.2]

### Chore

-   Change the settings page identifier to prevent collision with OpenPDC

## Version [2.2.1]

### Feat

-   Filter items, in api, on type taxonomy slug when param is set.

## Version [2.2.0]

### Feat

-   Support php8 + update deps

## Version [2.1.1]

### Feat

-   Remove Elasticpress settings when plugin yard-elasticsearch is active

## Version [2.1.0]

### Feat

-   Include SEO meta fields, provided by multiple plugins, in API.

## Version [2.0.20]

### Chore

-   Taxonomy openpub-show-on change capabilities from manage_options to manage_categories.

## Version [2.0.19]

### Chore

-   Update dependencies.

## Version [2.0.18]

### Refactor

-   Generating portal url.

### Fix

-   Return value in filter 'post_type_link' registered in '\OWC\OpenPub\Base\Admin\AdminServiceProvider::class.

## Version [2.0.17]

### Feat

-   Set argument public to false for the CPT's 'openpub-theme', 'openpub-subtheme' and 'openpub-location' so ElasticPress does not include them in the sync.

## Version [2.0.16]

### Feat

-   Add featured image field to related items

## Version [2.0.15]

### Feat

-   Change 'preview' parameter into 'draft-preview'

## Version [2.0.14]

### Fix

-   Append id for pdc draft previews

## Version [2.0.13]

### Fix

-   Elasticpress indexables posttypes and statuses hooks was not correctly returned.

## Version [2.0.12]

### Feat

-   Existing OpenPub items without an expiration date will be assigned a value of the published date plus the value given in days.
-   New OpenPub items will have a value of the current date plus the value given in days.

## Version [2.0.11]

### Fix

-   Meta-query with multiple arguments not working correctly.

## Version [2.0.10]

### Feat

-   Add preview parameter for retrieving drafts
-   Purge Varnish on save_post

### Fix

-   Add addHighlightedParameters to active items

## Version [2.0.9]

### Feat

-   Add author column for openpub items

## Version [2.0.8]

### Feat

-   Add explanation to 'show on' taxonomy form.
-   Only use 'show on' filtering in endpoints when setting is active.
-   Filter related items on source slug when param is set.

## Version [2.0.7]

### Refactor

-   Add filtering on 'show on' to active items endpoint.
-   'show on' taxonomy is only allowed to be managed by administrators.

## Version [2.0.6]

### Refactor

-   Filtering on 'show on' in items endpoint from string to numeric value.

## Version [2.0.5]

### Feat

-   Add 'show on' setting to openpub-settings.
-   Add 'show on' setting in editor of openpub-item.
-   Add filtering on 'show on' to items endpoint.

## Version [2.0.4]

### Feat

-   Add escape element setting to openpub-settings.
-   Add escape element setting in editor of openpub-item.

## Version [2.0.3]

### Fix:

-   Filter inactive items

## Version [2.0.2]

### Features:

-   Add slug to related item in API.
-   Add multiple unit tests.

## Version [2.0.1]

### Features:

-   Add full image size to attachment meta
-   Add expiration parameters to related items

## Version [2.0.0]

### Features:

-   Refactor: clean-up for version 1.0.
-   Add settings on settings page
-   Add SettingsPageOptions model
-   Add portal_url to api output on conditional
-   Add date_modified to api output on conditional

### Fix

-   Base settings on settings page

## [1.2.0] - 2020-10-10

### Added

-   Comments to items

## [1.1.9]

### Features

-   highlighted parameter on rest endpoint

## [1.1.8]

### Fix

-   Add: correct mappings for Elasticsearch

## [1.1.7]

### Features

-   Add: openpub rest route route on slug

## [1.1.6]

### Features

-   Add: add thumnbail url to related posts

## [1.1.5]

### Features

-   Fix: Standardize expired date for better compatibility.

## [1.1.4]

### Features

-   Add: make posttypes and taxonomies available in rest api.

## [1.1.3]

### Features

-   Add: make taxonomy "openpub-type" available in rest api.

## [1.1.2]

### Features

-   Chore: add endpoint for active items only: `wp-json/owc/openpub/v1/items/active`.

## [1.1.1]

### Features

-   Fix: remove unwanted redirect.

## [1.1.0]

### Features

-   Fix: remove unwanted exit.
-   Fix: related items of theme rest api.

## [1.0.9]

### Features

-   Chore: remove Hooks class.

## [1.0.8]

### Features

-   Add: Query args for rest api.
-   Update: PHPunit 8.

## [1.0.7]

### Fix

-   (fix): check if required file for `is_plugin_active` is already loaded, otherwise load it. Props @Jasper Heidebrink

## [1.0.6]

### Features

-   Add: openpub endpoint description for documentation.

## [1.0.5]

### Features

-   Add: default order of published date.

## [1.0.4]

### Features

-   Add: filter options to rest api.

## [1.0.3]

### Features

-   Update: languages
-   Add: add highlighted item to api.

## [1.0.2]

### Features

-   Update: languages
-   Change: format of rest api to follow WP_Post for elasticsearch
-   Remove: unused setting tab

## [1.0.1]

### Features

-   Add: docs.
-   Add: php style linter
-   Change: add rest api output to follow WP_Post

## [1.0.0]

### Features

-   Initial release
