# Locale Redirector plugin for Craft CMS

This plugin automatically redirects visitors to their preferred locale

## Overview

If you've ever developed a multilingual website, you've probably thought about automatically redirecting your visitors to their preferred locale. While it may have sounded simple at first, you obviously eventually realized it wasn't. Indeed, you have to take a few parameters into account:
- The locales installed in Craft
- The locale(s) accepted by your visitor's browser
- The language links
- Whether the page exists in the needed locale

Locale Redirector mixes all these parameters together and provides you with a plug-and-play solution.

## Installation

To install the Locale Redirector plugin, follow these steps:

1. Download the [latest release](https://github.com/pierrestoffe/craft-localeredirector/releases/latest) & unzip the file
2. Rename the directory into `localeredirector` if necessary
3. Copy the `localeredirector` directory into your `craft/plugins` directory
3. Install the plugin in the Craft Control Panel under Settings > Plugins

Component-based Templating works on Craft 2.4.x and Craft 2.5.x.

## Using this plugin

The only thing you have to modify is the language switcher section in your templates. The link's query string should include a `locale={{ locale }}` parameter, where `{{ locale }}` is the locale's id (`en_us`, `fr`, `de`,...). Doing so forces Locale Redirector to overwrite the locale cookie with the value of `locale`.

Here is an example:
```
{% for locale in craft.i18n.getSiteLocales() %}
    {% set localisedPage = null %}
    {% if entry is defined %}
       {% set localisedPage = craft.entries({
           id: entry.id,
           locale: locale.id
       })[0] ?? null %}
    {% elseif category is defined %}
       {% set localisedPage = craft.categories({
           id: category.id,
           locale: locale.id
       })[0] ?? null %}
    {% endif %}
    {% set linkUrl = localisedPage|length ? localisedPage.url : craft.config.siteUrl[locale.id] %}
    <li>
        <a href="{{ linkUrl }}?locale={{ locale }}" hreflang="{{ locale.id }}" lang="{{ locale.id }}">
            <abbr title="{{ locale.nativeName|capitalize }}">{{ locale.id|upper }}</abbr>
        </a>
    </li>
{% endfor %}
```

## Roadmap

Some things to do, and ideas for potential features:

* Include a language switcher
* Adapt for Craft 3

## Credits

Brought to you by [Pierre Stoffe](https://pierrestoffe.be)  
Kickstarted using [pluginfactory.io](https://pluginfactory.io)