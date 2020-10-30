# Hooks

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

## Actions

### Action for changing main Plugin object

```php
'owc/openpub-base/plugin'
```

See OWC\OpenPub\Base\Config->set method for a way to change this plugins config.

Via the plugin object the following config settings can be adjusted

- metaboxes
- p2p_connections
- posttypes
- settings
- settings_pages
- taxonomies

## Filters

### Filters the Posts to Posts connection defaults

```php
owc/openpub-base/p2p-connection-defaults
```

### Filters the per Posts to Posts connection, connection type args

```php
owc/openpub-base/before-register-p2p-connection/{$posttypes_from}/{$posttypes_to]}
```

### Filters the data retrieved for the corresponding Rest API field

```php
owc/openpub-base/rest-api/openpubitem/field/get-links
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-forms
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-downloads
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-title-alternative
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-appointment
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-featured_image
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-taxonomies
owc/openpub-base/core/posttype/posttypes/openpub_item/get-taxonomies/taxonomy-ids
```

```php
owc/openpub-base/rest-api/openpubitem/field/get-connections
```

```php
owc/openpub-base/rest-api/openpubsubcategory/field/has-report
```

```php
owc/openpub-base/rest-api/openpubsubcategory/field/has-appointment
```

### Filters the metaboxes to be registered just before registration

```php
owc/openpub-base/before-register-metaboxes
```

### Filters the settings to be registered just before registration

```php
owc/openpub-base/before-register-settings
```
