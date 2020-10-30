# README #

This README documents whatever steps are necessary to get this plugin up and running.

## How do I get set up ##

* Unzip and/or move all files to the /wp-content/plugins/openpub-base directory
* Log into WordPress admin and activate the ‘OpenPub Base’ plugin through the ‘Plugins’ menu
* Go to the 'OpenPub instellingen pagina' in the left-hand menu to enter some of the required settings

## Hooks ##

See [Hooks](/docs/hooks.md)

## Rest api ##

See [Rest Api](/docs/restapi.md)

## Translations ##

If you want to use your own set of labels/names/descriptions and so on you can do so. 
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/openpub-base/languages/openpub-base.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the [Loco Translate plugin](https://wordpress.org/plugins/loco-translate/)

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advice you to read the Beginners's guide page on their website: https://localise.biz/wordpress/plugin/beginners
or start at the homepage: https://localise.biz/wordpress/plugin

## Running tests ##

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

## Contribution guidelines ##

### Writing tests ###

Have a look at the code coverage reports to see where more coverage can be obtained. 
Write tests
Create a Pull request to the OWC repository

### Who do I talk to ###

IF you have questions about or suggestions for this plugin, please contact
[Holgers Peters](mailto:hpeters@Buren.nl) from Gemeente Buren.
