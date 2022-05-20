# Lightbox fieldtype plugin for Craft CMS

<img src="src/icon.svg" width="128" height="128" />

Craft Lightbox renders a simple and lightweight image Lightbox that is both responsive and accessible. It may be suitable for anyone seeking a plug-and-play lightbox solution without much setup required.

## Requirements

- Craft CMS 4.x
- PHP 8.0.2+

## Installation

Install the plugin as follows:

1.  Open your terminal and go to your Craft project:

        cd /path/to/project

2.  Then tell Composer to load the plugin:

        composer require dodecastudio/craft-lightbox

3.  In the Control Panel, go to Settings → Plugins and click the “Install” button for Lightbox.

## Overview

## Using Lightbox

### Adding the lightbox your templates

Lightbox must be embedded in to your page templates. This can be done anywhere on a page you need it, like so:

```twig
{# Embed the Lightbox on the page #}
{{ craft.lightbox.lightbox() }}
```

### Launching the lightbox

To launch the lightbox you will need an `Asset` resource and the plugin's `linkAttrs` method e.g:

```twig
{# Display a link that launches the Lightbox #}
{% set myAsset = craft.assets().one() %}
<a {{ craft.lightbox.linkAttrs(myAsset) }}>Launch {{myAsset.filename}} in Lightbox</a>
```

### Embedding a gallery

The plugin can easily generate markup for an image gallery, simply by passing an `AssetQuery` to the gallery method:

```twig
{# Embed a gallery of images that will all launch the Lightbox #}
{% set myAssetQuery = craft.assets().limit(20) %}
{{ craft.lightbox.gallery(myAssetQuery) }}
```

To improve accessibility, give the gallery a title like so:

```twig
{{ craft.lightbox.gallery(myAssetQuery, "Craft Assets Gallery") }}`
```

The gallery title will be read out by assistive technologies.

#### Embedding multiple galleries

To keep multiple images on the same in seperate galleries, simply assign them different gallery names:

```twig
{# Launch a single image in a gallery #}
{% set myAsset = craft.assets().one() %}
<a {{ craft.lightbox.linkAttrs(myAsset, "Gallery one") }}>Launch {{myAsset.filename}} in Lightbox</a>
{# Embed a gallery that launches in a seperate Lightbox  #}
{% set myAssetQuery = craft.assets().limit(20) %}
{{ craft.lightbox.gallery(myAssetQuery, "Gallery two") }}
```

#### Creating your own gallery

To fully customise the markup of a gallery, it's straightforward enough to generate your own gallery markup in the way that you'd expect.

```twig
{# Embed a gallery of images #}
{% set myAssetQuery = craft.assets().limit(20) %}
{% for myAsset in myAssetQuery %}
	<button {{ craft.lightbox.linkAttrs(myAsset, "Craft Assets Gallery") }}><img src="{{myAsset.url}}" alt="{{myAsset.alt}}"/></button>
{% endfor %}
```

## Plugin Settings

### Core settings

| Setting                   | Type     | Default             | Description                                                                                                                                                                                                                                                                                                                     |
| ------------------------- | -------- | ------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `identifier`              | `string` | `lightbox`          | An identifier to prepend to the `ID` attributes of HTML elements in the Lightbox.                                                                                                                                                                                                                                               |
| `launchLightboxCssClass`  | `string` | `lightbox-launcher` | The CSS Class that identifies links to launch the lightbox.                                                                                                                                                                                                                                                                     |
| `includeEssentialCss`     | `bool`   | `true`              | Whether or not to include the minimum CSS required to make the lightbox functional (recommended).                                                                                                                                                                                                                               |
| `includeDecorativeCss`    | `bool`   | `true`              | Whether or not to include CSS required to make the lightbox look nice (see additional settings below for details on how to set your own styles).                                                                                                                                                                                |
| `showImageCounter`        | `bool`   | `true`              | Whether or not to display the image counter in the Lightbox.                                                                                                                                                                                                                                                                    |
| `showImageCaptions`       | `bool`   | `true`              | Whether or not to display image captions in the Lightbox.                                                                                                                                                                                                                                                                       |
| `titleAsCaption`          | `bool`   | `true`              | Use the title of an Asset as the default caption, if an `alt` field does not exist on the Asset resource.                                                                                                                                                                                                                       |
| `responsiveTransforms`    | `string` | `true`              | Whether or not to apply transforms to images in the Lightbox in order to use [Responsive Image](https://developer.mozilla.org/en-US/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images) markup.                                                                                                                         |
| `transformSizeSm`         | `int`    | 320                 | The maximum width or height for the smallest image transform, applied to an image in the Lightbox.                                                                                                                                                                                                                              |
| `transformSizeMd`         | `int`    | 640                 | The maximum width or height for the medium image transform, applied to an image in the Lightbox.                                                                                                                                                                                                                                |
| `transformSizeLg`         | `int`    | 1200                | The maximum width or height for the large image transform, applied to an image in the Lightbox.                                                                                                                                                                                                                                 |
| `transformSizeXl`         | `int`    | 2400                | The maximum width or height for the extra large image transform, applied to an image in the Lightbox.                                                                                                                                                                                                                           |
| `useAverageColorThemeing` | `bool`   | `true`              | If [Craft Blurhash](https://plugins.craftcms.com/blur-hash?cmsConstraint=%5E4.0) is installed, enabling this will use an image's [average color](https://github.com/dodecastudio/craft-blurhash/tree/craft-v4#returning-average-color-for-an-image) as the background-color of the Lightbox when that image is being displayed. |

For a complete list of all available settings, please see the [`config.php`](src/config.php) example.

## Customisation

Each element can have it's CSS Classes renamed and additional CSS Classes applied. Both the default functional and decorative styles can also be disabled entirely.

### Disabling decorative styles

The Lightbox comes with pre-existing styles which can be easily disabled in the config like so:

```php
return [
  'includeDecorativeCss' => false,
];
```

You can then create your own classes to style the Lightbox however you wish.

### Disabling essential styles

The essential styles are recommended as they provide the bare minimum for the Lightbox to function. However, if you want to start completely from scratch, you can disable them easily enough:

```php
return [
  'includeEssentialCss' => false,
];
```

## FAQ

- **Why use `a` tags for image links?**
  This is mostly personal preference, but the gallery uses `a` tags by default (as opposed to `button` elements to ensure that the user does not lose built-in browser functionality such as _Save link as..._).

## Roadmap

- Support for custom transforms / Imager X etc

## Licence

TBD
