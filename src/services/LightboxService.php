<?php
/**
 * Lightbox plugin for Craft CMS 3.x
 *
 * Render a Lightbox from a given image.
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox\services;

use dodecastudio\lightbox\Lightbox;

use Craft;
use Craft\helpers\StringHelper;
use craft\base\Component;
use craft\elements\Asset;

class LightboxService extends Component
{
    private array $uniqueIds = [];

    /**
     * getLinkAttributesObject: Return the attributes required for generating a lightbox link
     *
     * @param asset Asset
     * @param ref String
     *
     * @return string
     */
    public function getLinkAttributes(Asset $asset = null, String $galleryTitle = null, String $galleryRef = null): Array
    {
        // Get settings
        $settings = Lightbox::getInstance()->getSettings();
        
        if (is_null($galleryRef)) {
            $galleryRef = Lightbox::getInstance()->lightboxServices->getGalleryRef($galleryTitle);
        }
        $gallery = $settings['identifier'] . '-gallery-' . $galleryRef;
        
        // Get title
        $alt = $asset->isFieldEmpty('alt') ? '' : $asset['alt'];
        $title = $settings['titleAsCaption'] ? $asset['title'] : $alt;

        // Transforms
        $srcset = null;
        $srcsetWebp = null;
        if ($settings['responsiveTransforms']) {
            // Create transforms
            $transformSm = ['mode' => 'fit', 'width' => $settings['transformSizeSm'], 'height' => $settings['transformSizeSm']];
            $transformMd = ['mode' => 'fit', 'width' => $settings['transformSizeMd'], 'height' => $settings['transformSizeMd']];
            $transformLg = ['mode' => 'fit', 'width' => $settings['transformSizeLg'], 'height' => $settings['transformSizeLg']];
            $transformXl = ['mode' => 'fit', 'width' => $settings['transformSizeXl'], 'height' => $settings['transformSizeXl']];
            // Render srcset
            $srcset = $asset->getUrl($transformSm, true) . ' ' . $transformSm['width'] .'w,' . $asset->getUrl($transformMd, true) . ' ' . $transformMd['width'] .'w,' . $asset->getUrl($transformLg, true) . ' ' . $transformLg['width'] .'w,' . $asset->getUrl($transformXl, true) . ' ' . $transformXl['width'] .'w';
            // Create WebP transforms
            if ($settings['responsiveTransformsWebp'] && Craft::$app->images->getSupportsWebP()) {
                $transformSmWebp = ['mode' => 'fit', 'width' => $settings['transformSizeSm'], 'height' => $settings['transformSizeSm'], 'format' => 'webp'];
                $transformMdWebp = ['mode' => 'fit', 'width' => $settings['transformSizeMd'], 'height' => $settings['transformSizeMd'], 'format' => 'webp'];
                $transformLgWebp = ['mode' => 'fit', 'width' => $settings['transformSizeLg'], 'height' => $settings['transformSizeLg'], 'format' => 'webp'];
                $transformXlWebp = ['mode' => 'fit', 'width' => $settings['transformSizeXl'], 'height' => $settings['transformSizeXl'], 'format' => 'webp'];
                // Render srcset
                $srcsetWebp = $asset->getUrl($transformSmWebp, true) . ' ' . $transformSmWebp['width'] .'w,' . $asset->getUrl($transformMdWebp, true) . ' ' . $transformMdWebp['width'] .'w,' . $asset->getUrl($transformLgWebp, true) . ' ' . $transformLgWebp['width'] .'w,' . $asset->getUrl($transformXlWebp, true) . ' ' . $transformXlWebp['width'] .'w';
            }
        }
        
        // Attributes object
        $attributesObject = [
            'aria' => [
                'controls' => $settings['identifier'] . '-modal',
                'label' => Craft::t('lightbox', 'IMAGE_CONTROL_LABEL', ['title' => $title]),
            ],
            'class' => [$settings['cssClassGalleryLink'], $settings['cssClassesGalleryLink'], $settings['launchLightboxCssClass']],
            'data' => [
                'averagecolor' => $settings['useAverageColorThemeing'] && Craft::$app->plugins->getPlugin('blur-hash') ? \dodecastudio\blurhash\BlurHash::getInstance()->blurHashServices->averageColor($asset)->getHsl() : null,
                'gallery' => $gallery,
                'mimetype' => $asset['mimeType'],
                'orientation' => $asset['width'] == $asset['height'] ? "square" : ($asset['width'] > $asset['height'] ? "landscape" : "portrait"),
                'srcset' => $srcset,
                'srcsetwebp' => $srcsetWebp,
                'title' => $title,
                'url' => $asset['url'],
            ],
        ];

        return $attributesObject;
    }

    /**
     * getGalleryAttributes: Return the attributes required for generating a gallery
     *
     * @param ref String
     * @param title String
     *
     * @return string
     */
    public function getGalleryAttributes(String $galleryTitle = '', String $galleryRef = null): Array
    {
        // Get settings
        $settings = Lightbox::getInstance()->getSettings();
        if (is_null($galleryRef)) {
            $galleryRef = Lightbox::getInstance()->lightboxServices->getGalleryRef($galleryTitle);
        }
        
        // Attributes object
        $attributesObject = [
            'id' => $settings['identifier'] . '-gallery-' . $galleryRef,
            'class' => [$settings['cssClassGallery'], $settings['cssClassesGallery']],
            'data' => [
              'ref' => $galleryRef,
              'title' => $galleryTitle,
            ]
        ];

        return $attributesObject;
    }

    /**
     * getGalleryRef: Returns a gallery reference from a gallery title
     *
     * @param title String
     *
     * @return string
     */
    public function getGalleryRef(String $galleryTitle = null): String
    {   
        if (empty($galleryTitle) or is_null($galleryTitle)) {
            return Lightbox::getInstance()->lightboxServices->generateUniqueID();
        }

        // Convert to a reference
        $ref1 = StringHelper::toKebabCase($galleryTitle);
        $ref2 = str_ireplace(array('a','e','i','o','u'), '', $ref1);
        $ref3 = $ref2.substr(0, 12);

        return $ref3;
    }

    /**
     * getGalleryImageSrcset: Returns a srcset for images displayed in the gallery grid
     *
     * @param ref String
     * @param title String
     *
     * @return string
     */
    public function getGalleryImageSrcset(Asset $asset = null): Array
    {
        // Get settings
        $settings = Lightbox::getInstance()->getSettings();

        // Transforms
        $srcset = [];
        $srcsetWebp = [];
        if ($settings['responsiveTransforms'] && in_array($asset->mimeType, ['image/jpeg', 'image/png', 'image/tiff', 'image/webp'])) {
            // Create transforms
            $transformXs = ['mode' => 'fit', 'width' => $settings['transformSizeXs'], 'height' => $settings['transformSizeXs']];
            $transformSm = ['mode' => 'fit', 'width' => $settings['transformSizeSm'], 'height' => $settings['transformSizeSm']];
            $transformMd = ['mode' => 'fit', 'width' => $settings['transformSizeMd'], 'height' => $settings['transformSizeMd']];
            // Render srcset
            $srcset = array(
                [ 'url' => $asset->getUrl($transformXs, true), 'width' => $transformXs['width'] ],
                [ 'url' => $asset->getUrl($transformSm, true), 'width' => $transformSm['width'] ],
                [ 'url' => $asset->getUrl($transformMd, true), 'width' => $transformMd['width'] ]
            );
            // Create WebP transforms
            if ($settings['responsiveTransformsWebp'] && Craft::$app->images->getSupportsWebP()) {
                $transformXsWebp = ['mode' => 'fit', 'width' => $settings['transformSizeXs'], 'height' => $settings['transformSizeXs'], 'format' => 'webp'];
                $transformSmWebp = ['mode' => 'fit', 'width' => $settings['transformSizeSm'], 'height' => $settings['transformSizeSm'], 'format' => 'webp'];
                $transformMdWebp = ['mode' => 'fit', 'width' => $settings['transformSizeMd'], 'height' => $settings['transformSizeMd'], 'format' => 'webp'];
                // Render srcset
                $srcsetWebp = array(
                    [ 'url' => $asset->getUrl($transformXsWebp, true), 'width' => $transformXsWebp['width'] ],
                    [ 'url' => $asset->getUrl($transformSmWebp, true), 'width' => $transformSmWebp['width'] ],
                    [ 'url' => $asset->getUrl($transformMdWebp, true), 'width' => $transformMdWebp['width'] ]
                );
            }
        }
        
        // Attributes object
        $srcsets = [
            'srcset' => $srcset,
            'srcsetwebp' => $srcsetWebp,
        ];

        return $srcsets;
    }


    /**
     * generateUniqueID: Returns a unique ID for a gallery ref
     *  
     * Thanks to Robin Schambach for the pointer:
     * https://craftcms.stackexchange.com/questions/29628/give-included-twig-component-unique-id
     *
     * @return string
     */
    public function generateUniqueId(): string 
    {
        $id = StringHelper::randomString(8);

        while (\in_array($id, $this->uniqueIds, true)){
            $id = StringHelper::randomString(8);
        }
        
        $this->uniqueIds[] = $id;

        return $id;
    }
    
}
