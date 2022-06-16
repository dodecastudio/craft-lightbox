# Craft Lightbox plugin for Craft CMS

<img src="src/icon.svg" width="128" height="128" />

Craft Lightbox renders a simple and lightweight image lightbox that is both responsive and accessible. It's suitable for anyone seeking a quick plug-and-play lightbox solution without much setup required.

## Requirements

- Craft CMS 3.X or 4.x
- PHP 7.4+

## Installation

Install the plugin as follows:

1.  Open your terminal and go to your Craft project:

        cd /path/to/project

2.  Then tell Composer to load the plugin:

        composer require dodecastudio/craft-lightbox

3.  In the Control Panel, go to Settings → Plugins and click the “Install” button for Lightbox.

## Getting started

The fastest way to take a look at the lightbox in action, is to copy the [demo.twig](resources/demo.twig) file in to your project and view it in a browser. So long as you have some assets in your project, you should see some content on that page.

Otherwise, create an `AssetQuery` and give it to lightbox's `gallery` method. Then include lightbox's `render` method like so:

```twig
{# Fetch some images #}
{% set myAssetQuery = craft.assets().limit(20) %}

{# Display the images in a gallery #}
{{ craft.lightbox.gallery(myAssetQuery) }}

{# Embed the Lightbox on the page #}
{{ craft.lightbox.render() }}
```

Please see the [Craft Lightbox Docs](https://github.com/dodecastudio/craft-lightbox/wiki) for a complete guide, including more [templating](https://github.com/dodecastudio/craft-lightbox/wiki/Templating) examples and full details on [configuration](https://github.com/dodecastudio/craft-lightbox/wiki/Configuration).
