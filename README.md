# Plugin OpenPub Base

This README documents whatever steps are necessary to get this plugin up and running.

## Getting started

1. Unzip and/or move all files to the /wp-content/plugins/openpub-base directory
2. Log into the WordPress admin and activate the ‘OpenPub Base’ plugin through the ‘Plugins’ menu
3. Go to the 'OpenPub instellingen' pagina in the left-hand menu to enter some of the required settings

## Hooks

See [Hooks](/docs/hooks.md)

## REST API

See [REST API](/docs/restapi.md)

## Running tests

To run the Unit tests go to a command-line.

```sh
# 1. go into the plugin directory
cd <path>

# 2. install the composer deps
composer install

# 3. run the tests
phpunit
```

You can run code coverage reports with the command below:

```sh
phpunit --coverage-html ./tests/coverage
```

## Shared environments

If the OpenPub environment is shared by multiple websites and it is required to configure the websites where an openpub-item should be displayed on, follow the steps below:

### Step 1

Go to the 'OpenPub instellingen pagina' in the left-hand menu and look for the setting 'Show on'. Check the checkbox. When this setting is enabled this plugin creates a taxonomy 'Show on'.

### Step 2

Add terms to the taxonomy so the terms can be used in the editor of an openpub-item.

### Step 3

This plugin adds a select element to the editor of an openpub-item. Look for the select beneath the heading 'External' and select the websites you want this item to be displayed on.

The selected websites are now able to make requests to the items endpoint and only retrieve openpub-items which are intended for the selected website. Example url: https://url/wp-json/owc/openpub/v1/items?source={blog_slug}

## Translations

All of the descriptions, labels and names inside this plugin can be translated since they are controlled by gettext methods. You can find the .pot file at in the languages directory.

> Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

Use your own preferred way of translating .pot files, however if this is your first time doing translations within WordPress, we recommend you use the [Loco Translate plugin](https://wordpress.org/plugins/loco-translate/) which is a great tool for translating WordPress plugins.

## Contributing

You're welcome to contribute or suggest improvements to this plugin.
When you submit a pull request, please make sure all the tests pass.

Want to contribute but have no idea what to begin with? Take a look at the coverage reports to see where more coverage can be obtained.

## Questions

You can ask technical questions at the [GitHub issues](https://github.com/OpenWebconcept/plugin-openpub-base/issues) page. For general questions about the Open Webconcept we ask you get in touch with us via the [Open Webconcept website](https://openwebconcept.nl/contact/).
