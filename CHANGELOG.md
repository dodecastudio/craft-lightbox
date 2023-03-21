# Lightbox Changelog

## v1.0.12 - 2023-03-21

- Fixed: When loading embedded video content, such as that in `iframe` or `video` tags, tabbed focus of the content was not possible, preventing keyboard users from being able to control the media. Fixing this ensure compliance with [WCAG 2.4.3: Focus Order](https://www.w3.org/WAI/WCAG21/Understanding/focus-order.html).

## v1.0.11 - 2023-03-16

- Fixed: The Next and Previous controls were not correctly disabled when the modal was launched, causing a bug that allowed focus to move out of the modal dialog without closing the modal. This meant that for single-image modals, the modal would not be compliant with [WCAG 2.4.3: Focus Order](https://www.w3.org/WAI/WCAG21/Understanding/focus-order.html).

## v1.0.10 - 2023-01-26

- Fixed: Images displayed in the lightbox were missing their alt.

## v1.0.9 - 2023-01-20

- Added: `captioncontent` data attribute allows for HTML captions in the lightbox.
- Updated: Demo template with example of `captioncontent` data attribute.
- Fixed: Removed duplicate markup for when a webp Asset is loaded in to the lightbox.

## v1.0.8 - 2022-11-08

- Fixed: Removed `Mixed` type declarations to fix PHP 7.4 compatibility.

## v1.0.7 - 2022-10-25

- Updated: Changed it so that the counter no longer displays when there is a single image in the lightbox.

## v1.0.6 - 2022-10-25

- Fixed: Typo in templated CSS Class names.

## v1.0.5 - 2022-10-25

- Added: Support for different types of lightbox content, including YouTube and Vimeo embeds as well as video assets and HTML content.
- Added: New settings and translation strings, for use with different lightbox content.
- Updated: Changed the default value for the `IMAGE_CONTROL_LABEL` translation string.
- Updated: The demo template with new examples.

## v1.0.4 - 2022-07-10

- Fixed: Removing use of `endBody` due to templating issues across different scenarios.

## v1.0.3 - 2022-07-06

- Fixed: Issue whereby assetbundle would not render in some situations.

## v1.0.2 - 2022-06-16

- Updated: Changed version number and composer.json file to better support Craft 3 and 4 across a single version.

## v1.0.1 - 2022-06-15

- Fixed: CSS Classes settings for the lightbox's image and picture elements were missing.

## v1.0.0 - 2022-06-15

- Initial release.

## v1.0.0-beta2 - 2022-06-12

- Added: Responsive image support to gallery markup.
- Added: New transform size to config.
- Updated: Improved decorative CSS layout of SVGs used in lightbox controls.
- Fixed: JavaScript bug with srcset path for some images.

## v1.0.0-beta1 - 2022-06-11

- Initial beta release.
