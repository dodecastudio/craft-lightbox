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
    private array $supportedTypes = ["image", "video", "youtube", "vimeo", "query"];
    private array $supportedVideoFiles = ["3gp", "3gp2", "avi", "m3u8", "mkv", "mov", "mp4", "mpeg", "ogg", "webm", "wmv"];
    private array $supportedResponsiveImageMimeTypes = ["image/jpeg", "image/png", "image/tiff", "image/webp"];

    /**
     * getLinkAttributesObject: Return the attributes required for generating a lightbox link
     *
     * @param asset Asset
     * @param ref String
     *
     * @return string
     */
    public function getLinkAttributes($source = null, String $galleryTitle = null, String $galleryRef = null): Array
    {
        // Get settings
        $settings = Lightbox::getInstance()->getSettings();
        
        // Check gallery reference
        if (is_null($galleryRef)) {
            $galleryRef = Lightbox::getInstance()->lightboxServices->getGalleryRef($galleryTitle);
        }
        $gallery = $settings['identifier'] . '-gallery-' . $galleryRef;
        
        // Get type
        $type = Lightbox::getInstance()->lightboxServices->getType($source);

        // Set defaults
        $attributesObject = [
            'aria' => [
                'controls' => $settings['identifier'] . '-modal',
            ],
            'class' => [$settings['cssClassGalleryLink'], $settings['cssClassesGalleryLink'], $settings['launchLightboxCssClass']],
            'data' => [
                'gallery' => $gallery,
                'type' => $type,
            ],
        ];

        // Image
        if ($type == "image") {
            // Get title
            $asset = $source;
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
            $attributesObject['aria']['label'] = Craft::t('lightbox', 'IMAGE_CONTROL_LABEL', ['title' => $title]);
            $attributesObject['data']['averagecolor'] = $settings['useAverageColorThemeing'] && Craft::$app->plugins->getPlugin('blur-hash') ? \dodecastudio\blurhash\BlurHash::getInstance()->blurHashServices->averageColor($asset)->getHsl() : null;
            $attributesObject['data']['mimetype'] = $asset['mimeType'];
            $attributesObject['data']['orientation'] = $asset['width'] == $asset['height'] ? "square" : ($asset['width'] > $asset['height'] ? "landscape" : "portrait");
            $attributesObject['data']['srcset'] = $srcset;
            $attributesObject['data']['srcsetwebp'] = $srcsetWebp;
            $attributesObject['data']['title'] = $title;
            $attributesObject['data']['url'] = $asset['url'];
        }

        // Video
        if ($type == "video") {
            $type = "video";
            if ($source instanceof Asset) {
                // Attributes object
                $asset = $source;
                $alt = $asset->isFieldEmpty('alt') ? '' : $asset['alt'];
                $title = $settings['titleAsCaption'] ? $asset['title'] : $alt;
                $attributesObject['aria']['label'] = Craft::t('lightbox', 'VIDEOASSET_CONTROL_LABEL', ['title' => $title]);
                $attributesObject['data']['mimetype'] = $asset['mimeType'];
                $attributesObject['data']['title'] = $title;
                $attributesObject['data']['url'] = $asset['url'];
            } else {
                // Attributes object
                $attributesObject['aria']['label'] = Craft::t('lightbox', 'VIDEO_CONTROL_LABEL');
                $attributesObject['data']['orientation'] = "landscape";
                $attributesObject['data']['url'] = $source;
            }
        }

        // YouTube
        if ($type == "youtube") {
            // Attributes object
            $attributesObject['aria']['label'] = Craft::t('lightbox', 'YOUTUBE_CONTROL_LABEL');
            $attributesObject['data']['orientation'] = "landscape";
            $attributesObject['data']['url'] = $source;
        }

        // Vimeo
        if ($type == "vimeo") {
            // Attributes object
            $attributesObject['aria']['label'] = Craft::t('lightbox', 'VIMEO_CONTROL_LABEL');
            $attributesObject['data']['orientation'] = "landscape";
            $attributesObject['data']['url'] = $source;
        }

        // Query
        if ($type == "query") {
            // Attributes object
            $attributesObject['aria']['label'] = Craft::t('lightbox', 'QUERY_CONTROL_LABEL');
            $attributesObject['data']['target'] = $source;
        }

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
        if ($settings['responsiveTransforms'] && in_array($asset->mimeType,$this->supportedResponsiveImageMimeTypes)) {
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


    /**
     * getType: Returns the type of gallery
     *
     * @param source String
     *
     * @return string
     */
    public function getType($source): string 
    {
        $type = false;

        if ($source instanceof Asset && $source->kind == "image") {
            $type = "image";
        }

        if ($source instanceof Asset && $source->kind == "video") {
            $type = "video";
        }
        
        if (is_string($source)) {
            $extension = pathinfo($source, PATHINFO_EXTENSION);
            if (stripos($source, "youtube.com") > 0) {
                $type = "youtube";
            } elseif (stripos($source, "vimeo.com") > 0) {
                $type = "vimeo";
            } elseif (in_array($extension, $this->supportedVideoFiles)) {
                $type = "video";
            } else {
                $type = "query";
            }
        }

        return $type;
    }
    
}
