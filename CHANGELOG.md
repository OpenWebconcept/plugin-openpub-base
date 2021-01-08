# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
