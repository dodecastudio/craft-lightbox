<?php
/**
 * Lightbox plugin for Craft CMS 4.x
 *
 * Render a Lightbox from a given image.
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox\models;

use craft\base\Model;

class Settings extends Model
{

    // An identifier to prepend to the `ID` attributes of HTML elements in the Lightbox.
    public string $identifier = 'clb';
    
    // The CSS Class that identifies links to launch the lightbox.
    public string $launchLightboxCssClass = 'clb-launcher';

    // Whether or not to include the minimum CSS required to make the lightbox functional (recommended).
    public bool $includeEssentialCss = true;

    // Whether or not to include CSS required to make the lightbox look nice (see additional settings below for details on how to set your own styles).
    public bool $includeDecorativeCss = true;

    // Whether or not to display the image counter in the Lightbox.
    public bool $showImageCounter = true;

    // Whether or not to display image captions in the Lightbox.
    public bool $showImageCaptions = true;

    // Use the title of an Asset as the default caption, if an `alt` field does not exist on the Asset resource.
    public bool $titleAsCaption = true;

    // Whether or not to apply transforms to images in the Lightbox in order to use Responsive Image markup.
    public bool $responsiveTransforms = true;

    // Whether or not to apply render additional transforms to images in the Lightbox in WebP format.
    public bool $responsiveTransformsWebp = false;

    // The maximum width or height for the smallest image transform, applied to an image in the Lightbox.
    public int $transformSizeSm = 320;

    // The maximum width or height for the medium image transform, applied to an image in the Lightbox.
    public int $transformSizeMd = 640;

    // The maximum width or height for the large image transform, applied to an image in the Lightbox.
    public int $transformSizeLg = 1200;

    // The maximum width or height for the extra large image transform, applied to an image in the Lightbox.
    public int $transformSizeXl = 2400;

    // If Craft Blurhash (https://plugins.craftcms.com/blur-hash) is installed, enabling this will use an image's average color as the background-color of the Lightbox when that image is being displayed.
    public bool $useAverageColorThemeing = true;

    // CSS Classes used in markup:
    // Note: Those prefixed $cssClass* denote a single and primary class for the element
    // Those prefixed $cssClasses* allow for additional classes to be applied. Useful for applying atomic classes and utilties such as Unocss.

    // CSS Classes for the lightbox modal
    public string $cssClassLightboxModal = 'clb-modal';
    public string $cssClassesLightboxModal = '';

    // CSS Classes for the lightbox modal's first child, the container
    public string $cssClassLightboxContainer = 'clb-container';
    public string $cssClassesLightboxContainer = '';

    // CSS Classes for the close control within the lightbox
    public string $cssClassLightboxControlClose = 'clb-control-close';
    public string $cssClassesLightboxControlClose = '';

    // CSS Classes for the SVG icon in the close control within the lightbox
    public string $cssClassLightboxControlCloseSvg = 'clb-control-close-svg';
    public string $cssClassesLightboxControlCloseSvg = '';

    // CSS Classes for the "previous image" control within the lightbox
    public string $cssClassLightboxControlPrevious = 'clb-control-previous';
    public string $cssClassesLightboxControlPrevious = '';

    // CSS Classes for the SVG icon in the "previous image" control within the lightbox
    public string $cssClassLightboxControlPreviousSvg = 'clb-control-previous-svg';
    public string $cssClassesLightboxControlPreviousSvg = '';

    // CSS Classes for the "next image" control within the lightbox
    public string $cssClassLightboxControlNext = 'clb-control-next';
    public string $cssClassesLightboxControlNext = '';

    // CSS Classes for the SVG icon in the "next image" control within the lightbox
    public string $cssClassLightboxControlNextSvg = 'clb-control-next-svg';
    public string $cssClassesLightboxControlNextSvg = '';

    // CSS Classes for the content wrapper, which contains the frame and info elements
    public string $cssClassLightboxContent = 'clb-content';
    public string $cssClassesLightboxContent = '';

    // CSS Classes for the frame, which contains the image
    public string $cssClassLightboxFrame = 'clb-frame';
    public string $cssClassesLightboxFrame = '';

    // CSS Classes for the img tag for the picture element for the lightbox image being displayed
    public string $cssClassLightboxPicture = 'clb-picture';
    public string $cssClassesLightboxPicture = '';

    // CSS Classes for the img tag for the lightbox image being displayed
    public string $cssClassLightboxImage = 'clb-image';
    public string $cssClassesLightboxImage = '';

    // CSS Class applied to the lightbox image whilst it is loading
    public string $cssClassLightboxImageLoading = 'clb-image--loading';

    // CSS Classes for the image counter, which displays the current image number and the total number of images in the lightbox
    public string $cssClassLightboxInfoTotal = 'clb-total';
    public string $cssClassesLightboxInfoTotal = '';

    // CSS Classes for the image caption
    public string $cssClassLightboxInfoCaption = 'clb-caption';
    public string $cssClassesLightboxInfoCaption = '';

    // CSS Classes for the gallery container
    public string $cssClassGallery = 'clb-gallery-wrapper';
    public string $cssClassesGallery = '';

    // CSS Classes for the links that wrap gallery images
    public string $cssClassGalleryLink = 'clb-gallery-link';
    public string $cssClassesGalleryLink = '';

    // CSS Classes for the images in a gallery
    public string $cssClassGalleryImage = 'clb-gallery-image';
    public string $cssClassesGalleryImage = '';

    // CSS Classes for the images in a gallery
    public string $cssClassScreenReaderOnly = 'clb-sr-only';
    public string $cssClassesScreenReaderOnly = '';

    // CSS Class for disabling document scroll when lightbox is active
    public string $cssClassDisableScroll = 'clb-disablescroll';
    public string $cssClassesDisableScroll = '';

    public function rules() : array
    {
        return [
            ['identifier', 'required'],
            ['launchLightboxCssClass', 'required'],
            ['includeEssentialCss', 'required'],
            ['includeDecorativeCss', 'required'],
            ['showImageCounter', 'required'],
            ['showImageCaptions', 'required'],
            ['responsiveTransforms', 'required'],
            ['responsiveTransformsWebp', 'required'],
            ['transformSizeSm', 'required'],
            ['transformSizeMd', 'required'],
            ['transformSizeLg', 'required'],
            ['transformSizeXl', 'required'],
            ['useAverageColorThemeing', 'required'],
            ['cssClassLightboxModal', 'required'],
            ['cssClassesLightboxModal', 'required'],
            ['cssClassLightboxContainer', 'required'],
            ['cssClassesLightboxContainer', 'required'],
            ['cssClassLightboxControlClose', 'required'],
            ['cssClassesLightboxControlClose', 'required'],
            ['cssClassLightboxControlCloseSvg', 'required'],
            ['cssClassesLightboxControlCloseSvg', 'required'],
            ['cssClassLightboxControlPrevious', 'required'],
            ['cssClassesLightboxControlPrevious', 'required'],
            ['cssClassLightboxControlPreviousSvg', 'required'],
            ['cssClassesLightboxControlPreviousSvg', 'required'],
            ['cssClassLightboxControlNext', 'required'],
            ['cssClassesLightboxControlNext', 'required'],
            ['cssClassLightboxControlNextSvg', 'required'],
            ['cssClassesLightboxControlNextSvg', 'required'],
            ['cssClassLightboxContent', 'required'],    
            ['cssClassesLightboxContent', 'required'],    
            ['cssClassLightboxFrame', 'required'],
            ['cssClassesLightboxFrame', 'required'],
            ['cssClassLightboxImage', 'required'],
            ['cssClassesLightboxImage', 'required'],
            ['cssClassLightboxImageLoading', 'required'],
            ['cssClassLightboxPicture', 'required'],
            ['cssClassesLightboxPicture', 'required'],
            ['cssClassLightboxInfoTotal', 'required'],
            ['cssClassesLightboxInfoTotal', 'required'],
            ['cssClassLightboxInfoCaption', 'required'],       
            ['cssClassesLightboxInfoCaption', 'required'],       
            ['cssClassGallery', 'required'],
            ['cssClassesGallery', 'required'],
            ['cssClassGalleryLink', 'required'],
            ['cssClassesGalleryLink', 'required'],
            ['cssClassGalleryImage', 'required'],
            ['cssClassesGalleryImage', 'required'],
            ['cssClassScreenReaderOnly', 'required'],
            ['cssClassesScreenReaderOnly', 'required'],
            ['cssClassDisableScroll', 'required'],
            ['cssClassesDisableScroll', 'required'],
        ];
    }
}
