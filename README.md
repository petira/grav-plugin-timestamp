# Timestamp Plugin

The **Timestamp** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav). The Timestamp Plugin overrides the values of all listed `date` type variables from a recognizable `string` format to a value in `timestamp` format. Valid values are any string date values that `strtotime()` supports. Optionally, if a variable is not specified on a page, it can be created and the selected value returned to it.

The dependency on Grav v1.6.0 is now set, but it is being developed on Grav v1.7 and Admin Panel v1.10.

You can always find the latest version of this [documentation](https://github.com/petira/grav-plugin-timestamp/blob/develop/README.md) on the project [homepage](https://github.com/petira/grav-plugin-timestamp).

If you find a problem or have a suggestion for improvement, please send me an [issue](https://github.com/petira/grav-plugin-timestamp/issues).

If you translate the Timestamp Plugin into another language, please send me the [strings](https://github.com/petira/grav-plugin-timestamp/blob/develop/languages.yaml) via [pull request](https://github.com/petira/grav-plugin-timestamp/pulls) or [issue](https://github.com/petira/grav-plugin-timestamp/issues).

The [demo](https://www.grav.cz/demo/timestamp) is available on the [Grav.cz](https://www.grav.cz) website.

## Important

### Known issue

When using the Timestamp Plugin on a multi-language website, the system crashes because the individual language versions of the pages are not treated. The issue will be fixed as soon as possible in some future version.

If you still want to use the Timestamp Plugin on a multi-language website, set the Content Language Fallback to each other for all languages in the `/user/config/system.yaml` file, for example like this:

```
languages:
  supported:
    - en
    - cs
    - fr
  default_lang: en
  content_fallback:
    en: 'en,cs,fr'
    cs: 'cs,en,fr'
    fr: 'fr,cs,en'
```

## Installation

Installing the Timestamp plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](https://learn.getgrav.org/cli-console/grav-cli-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav installation, and enter:

    bin/gpm install timestamp

This will install the Timestamp plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/timestamp`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `timestamp`. You can find these files on [GitHub](https://github.com/petira/grav-plugin-timestamp) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/timestamp

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml file on GitHub](https://github.com/petira/grav-plugin-timestamp/blob/develop/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins` menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `/user/plugins/timestamp/timestamp.yaml` to `/user/config/plugins/timestamp.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named `timestamp.yaml` will be saved in the `/user/config/plugins` folder once the configuration is saved in the Admin.

## Usage

### Basic information

The Timestamp Plugin does not overwrite or save anything, everything happens dynamically within the session.

The overrides do not affect the `date` and `modified` variables, which are directly processed by Grav as timestamps, returned via `page.date` and `page.modified`. However, through the Timestamp Plugin it is possible to create clones of them in the `page.header.date` and `page.header.modified` variables if needed.

> NOTE: All variables are user-defined (custom), which means they are always accessed via the `header`, including handling in collections, such as when ordering.

The Timestamp Plugin can now process values from page headers that are stored in first, second and third level variables, specifically:

```
date: '17:08:22 29-11-2020'
```

```
event:
    start: '08:00:00 22-10-2022'
    end: '15:00:00 22-10-2022'
```

```
stamp:
    created:
        on: '17:08:22 29-11-2020'
        by: Joe Doe
    modified:
        on: '13:31:17 18-01-2023'
        by: Joe Doe
```

### Settings

#### Overriding date values

> NOTE: The Date and Time values in the examples below are converted to a Timestamp in UTC. So it may not match your time zone.

In the **List of variables**, enter the name of the original variable and the name of the new variable to store the timestamp.

If the new variable has the same name as the original variable, the original variable will be overriden within the session:

    date_modified = '14:36:27 18-01-2023'

| Original variable | New variable |
| - | - |
| date_modified | date_modified |
| 14:36:27 18-01-2023 | 1674052587 |

    date_modified = 1674052587

If the new variable is empty, the original variable will also be overriden within the session:

    date_modified = '14:36:27 18-01-2023'

| Original variable | New variable |
| - | - |
| date_modified | |
| 14:36:27 18-01-2023 | 1674052587 |

    date_modified = 1674052587

If the new variable has a different name than the original variable, the original variable will be preserved within the session:

    date_modified = '14:36:27 18-01-2023'
    date_modified_new = null

| Original variable | New variable |
| - | - |
| date_modified | date_modified_new |
| 14:36:27 18-01-2023 | 1674052587 |

    date_modified = '14:36:27 18-01-2023'
    date_modified_new = 1674052587

Of course, it is also possible to override the value of any original or new variable:

    date = '12:34:56 01-01-2023'
    date_modified = '14:36:27 18-01-2023'
    date_modified_new = null

| Original variable | New variable |
| - | - |
| date_modified | date_modified_new |
| 14:36:27 18-01-2023 | 1674052587 |
| date | date_modified |
| 12:34:56 01-01-2023 | 1672576496 |

    date = '12:34:56 01-01-2023'
    date_modified = 1672576496
    date_modified_new = 1674052587

Second and third level variables are separated by dots, but the same rules apply to them as for first level variables:

| Original variable | New variable |
| - | - |
| event.start | event.start |
| 22-10-2022 08:00:00 | 1666425600 |
| event.end | event.end |
| 22-10-2022 15:00:00 | 1666450800 |

Of course, even in this case, if the new variable has a different name than the original variable, the original variable will be preserved within the session:

| Original variable | New variable |
| - | - |
| stamp.created.on | pc |
| 2020-11-29 17:08:22 | 1606669702 |
| stamp.modified.on | pm |
| 2023-01-18 14:36:27 | 1674052587 |

#### Creating date values

If a variable is not specified on a page, it can be created and the selected value returned to it.

In the **Default date value** drop-down list, it is possible to select a default value that applies to all variables:

| Name | Command | Return |
| - | - | - |
| Empty | empty | null |
| Date | date | the page `date` value, if not specified, then the page `modified` value |
| Modified | modified | the page `modified` value |
| Past | past | 0000000000 (00:00:00 UTC on 1st January 1970) |
| Present | present | now (current date and time) |
| Future | future | 2147483647 (03:14:07 UTC on 19th January 2038) |

It is advisable to use this option in such a way that it affects as many variables as possible.

Of course, it is possible to override the default value with values for individual variables in the **Custom date values** list:

| Original variable | Command |
| - | - |
| date_modified | modified |
| event.start | past |
| event.end | future |

It is possible to use the same commands as for the default value.

## Credits

[Grav.cz](https://www.grav.cz) - Czech portal about [Grav CMS](https://github.com/getgrav/grav) containing lots of instructions and tips

[Stamp Plugin](https://github.com/petira/grav-plugin-stamp) - plugin for [Grav CMS](https://github.com/getgrav/grav) written by the same [author](https://github.com/petira)

## To Do

- [ ] Direct support for multi-language website
- [ ] Improve checking via REGEX
- [ ] Solve the Year 2038 problem
- [ ] Specific examples in the [README.md](https://github.com/petira/grav-plugin-timestamp/blob/develop/README.md) file and on the [Demo](https://www.grav.cz/demo/timestamp) page
