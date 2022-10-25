<?php
/**
 * Lightbox plugin for Craft CMS 4.x
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

/**
 * Lightbox plugin config.php
 *
 * This file is a template for the Lightbox settings.
 * 
 * To use it, copy it to 'craft/config/' and rename to 'lightbox.php'. Make any changes you wish to there.
 * 
 * For more information, see the Craft Docs here:
 * https://craftcms.com/docs/4.x/extend/plugin-settings.html#overriding-setting-values
 * 
 */

return [
  
  // An identifier to prepend to the `ID` attributes of HTML elements in the Lightbox.
  'identifier' => 'clb',
    
  // The CSS Class that identifies links to launch the lightbox.
  'launchLightboxCssClass' => 'clb-launcher',
  
  // Whether or not to include the minimum CSS required to make the lightbox functional (recommended).
  'includeEssentialCss' => true,
  
  // Whether or not to include CSS required to make the lightbox look nice (see additional settings below for details on how to set your own styles).
  'includeDecorativeCss' => true,
  
  // Whether or not to display the image counter in the Lightbox.
  'showImageCounter' => true,
  
  // Whether or not to display image captions in the Lightbox.
  'showImageCaptions' => true,
  
  // Use the title of an Asset as the default caption, if an `alt` field does not exist on the Asset resource.
  'titleAsCaption' => true,
  
  // Whether or not to apply transforms to images in the Lightbox in order to use Responsive Image markup.
  'responsiveTransforms' => true,
  
  // Whether or not to apply render additional transforms to images in the Lightbox in WebP format.
  'responsiveTransformsWebp' => false,

  // The maximum width or height for the extra small image transform, applied to an image in a Gallery.
  'transformSizeXs' => 160,
  
  // The maximum width or height for the smallest image transform, applied to an image in the Lightbox.
  'transformSizeSm' => 320,
  
  // The maximum width or height for the medium image transform, applied to an image in the Lightbox.
  'transformSizeMd' => 640,
  
  // The maximum width or height for the large image transform, applied to an image in the Lightbox.
  'transformSizeLg' => 1200,
  
  // The maximum width or height for the extra large image transform, applied to an image in the Lightbox.
  'transformSizeXl' => 2400,
  
  // If Craft Blurhash (https://plugins.craftcms.com/blur-hash) is installed, enabling this will use an image's average color as the background-color of the Lightbox when that image is being displayed.
  'useAverageColorThemeing' => true,
  
  // CSS Classes used in markup:
    // Note: Those prefixed cssClass* denote a single and primary class for the element
    // Those prefixed cssClasses* allow for additional classes to be applied. Useful for applying atomic classes and utilties such as Unocss.
  
  // CSS Classes for the lightbox modal
  'cssClassLightboxModal' => 'clb-modal',
  'cssClassesLightboxModal' => '',
  
  // CSS Classes for the lightbox modal's first child, the container
  'cssClassLightboxContainer' => 'clb-container',
  'cssClassesLightboxContainer' => '',
  
  // CSS Classes for the close control within the lightbox
  'cssClassLightboxControlClose' => 'clb-control-close',
  'cssClassesLightboxControlClose' => '',
  
  // CSS Classes for the SVG icon in the close control within the lightbox
  'cssClassLightboxControlCloseSvg' => 'clb-control-close-svg',
  'cssClassesLightboxControlCloseSvg' => '',
  
  // CSS Classes for the "previous image" control within the lightbox
  'cssClassLightboxControlPrevious' => 'clb-control-previous',
  'cssClassesLightboxControlPrevious' => '',
  
  // CSS Classes for the SVG icon in the "previous image" control within the lightbox
  'cssClassLightboxControlPreviousSvg' => 'clb-control-previous-svg',
  'cssClassesLightboxControlPreviousSvg' => '',
  
  // CSS Classes for the "next image" control within the lightbox
  'cssClassLightboxControlNext' => 'clb-control-next',
  'cssClassesLightboxControlNext' => '',
  
  // CSS Classes for the SVG icon in the "next image" control within the lightbox
  'cssClassLightboxControlNextSvg' => 'clb-control-next-svg',
  'cssClassesLightboxControlNextSvg' => '',
  
  // CSS Classes for the content wrapper, which contains the frame and info elements
  'cssClassLightboxContent' => 'clb-content',
  'cssClassesLightboxContent' => '',
  
  // CSS Classes for the frame, which contains the image
  'cssClassLightboxFrame' => 'clb-frame',
  'cssClassesLightboxFrame' => '',
  
  // CSS Classes for the img tag for the picture element for the lightbox image being displayed
  'cssClassLightboxPicture' => 'clb-picture',
  'cssClassesLightboxPicture' => '',
  
  // CSS Classes for the img tag for the lightbox image being displayed
  'cssClassLightboxImage' => 'clb-image',
  'cssClassesLightboxImage' => '',
  
  // CSS Class applied to the lightbox image whilst it is loading
  'cssClassLightboxImageLoading' => 'clb-image--loading',
  
  // CSS Classes for the image counter, which displays the current image number and the total number of images in the lightbox
  'cssClassLightboxInfoTotal' => 'clb-total',
  'cssClassesLightboxInfoTotal' => '',
  
  // CSS Classes for the image caption
  'cssClassLightboxInfoCaption' => 'clb-caption',
  'cssClassesLightboxInfoCaption' => '',
  
  // CSS Classes for the gallery container
  'cssClassGallery' => 'clb-gallery-wrapper',
  'cssClassesGallery' => '',
  
  // CSS Classes for the links that wrap gallery images
  'cssClassGalleryLink' => 'clb-gallery-link',
  'cssClassesGalleryLink' => '',
  
  // CSS Classes for the images in a gallery
  'cssClassGalleryImage' => 'clb-gallery-image',
  'cssClassesGalleryImage' => '',
  
  // CSS Classes for the images in a gallery
  'cssClassScreenReaderOnly' => 'clb-sr-only',
  'cssClassesScreenReaderOnly' => '',
  
  // CSS Class for disabling document scroll when lightbox is active
  'cssClassDisableScroll' => 'clb-disablescroll',
  'cssClassesDisableScroll' => '',

  // CSS Classes for lightbox iframe content
  'cssClassVideo' => 'clb-video',
  'cssClassesVideo' => '',

  // CSS Classes for lightbox video content wrapper
  'cssClassVideoContainer' => 'clb-videowrapper',
  'cssClassesVideoContainer' => '',

  // CSS Classes for lightbox iframe content
  'cssClassIframe' => 'clb-iframe',
  'cssClassesIframe' => '',
];