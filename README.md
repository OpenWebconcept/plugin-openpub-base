# README #

This README documents whatever steps are necessary to get this plugin up and running.

### How do I get set up? ###
     
* Unzip and/or move all files to the /wp-content/plugins/openpub-base directory
* Log into WordPress admin and activate the ‘OpenPub Base’ plugin through the ‘Plugins’ menu
* Go to the 'OpenPub instellingen pagina' in the left-hand menu to enter some of the required settings

### Filters & Actions

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

##### Action for changing main Plugin object.
```php
'owc/openpub-base/plugin'
```

See OWC\OpenPub\Base\Config->set method for a way to change this plugins config.

Via the plugin object the following config settings can be adjusted
- metaboxes
- p2p_connections
- posttypes
- rest_api_fields
- settings
- settings_pages
- taxonomies

##### Filters the Posts to Posts connection defaults.
```php
owc/openpub-base/p2p-connection-defaults
```

##### Filters the per Posts to Posts connection, connection type args.
```php
owc/openpub-base/before-register-p2p-connection/{$posttypes_from}/{$posttypes_to]}
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-links
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-forms
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-downloads
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-title-alternative
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-appointment
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-featured_image
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-taxonomies
owc/openpub-base/core/posttype/posttypes/openpub_item/get-taxonomies/taxonomy-ids
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubitem/field/get-connections
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubsubcategory/field/has-report
```

##### Filters the data retrieved for this Rest API field.
```php
owc/openpub-base/rest-api/openpubsubcategory/field/has-appointment
```

##### Filters the metaboxes to be registered just before registration.
```php
owc/openpub-base/before-register-metaboxes
```

##### Filters the settings to be registered just before registration..
```php
owc/openpub-base/before-register-settings
```

### Translations ###

If you want to use your own set of labels/names/descriptions and so on you can do so. 
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/openpub-base/languages/openpub-base.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the 'Loco Translate' plugin. 
https://wordpress.org/plugins/loco-translate/

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advice you to read the Beginners's guide page on their website: https://localise.biz/wordpress/plugin/beginners
or start at the homepage: https://localise.biz/wordpress/plugin

### Running tests ###
To run the Unit tests go to a command-line.
```bash
cd /path/to/wordpress/htdocs/wp-content/plugins/openpub-base/
composer install
phpunit
```

For code coverage report, generate report with command line command and view results with browser.
```bash
phpunit --coverage-html ./tests/coverage
```

### Contribution guidelines ###

##### Writing tests
Have a look at the code coverage reports to see where more coverage can be obtained. 
Write tests
Create a Pull request to the OWC repository

### Who do I talk to? ###

IF you have questions about or suggestions for this plugin, please contact <a href="mailto:hpeters@Buren.nl">Holger Peters</a> from Gemeente Buren.
