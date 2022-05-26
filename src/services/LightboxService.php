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
        $alt = isset($asset['alt']) || array_key_exists('alt', $asset) ? $asset['alt'] : '';
        $title = $settings['titleAsCaption'] ? $asset['title'] : $alt;

        // Transforms
        $transformSm = ['mode' => 'fit', 'width' => $settings['transformSizeSm'], 'height' => $settings['transformSizeSm']];
        $transformMd = ['mode' => 'fit', 'width' => $settings['transformSizeMd'], 'height' => $settings['transformSizeMd']];
        $transformLg = ['mode' => 'fit', 'width' => $settings['transformSizeLg'], 'height' => $settings['transformSizeLg']];
        $transformXl = ['mode' => 'fit', 'width' => $settings['transformSizeXl'], 'height' => $settings['transformSizeXl']];
        
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
                'orientation' => $asset['width'] == $asset['height'] ? "square" : $asset['width'] > $asset['height'] ? "landscape" : "portrait",
                'srcset' => $settings['responsiveTransforms'] ? $asset->getUrl($transformSm, true) . ',' . $asset->getUrl($transformMd, true) . ',' . $asset->getUrl($transformLg, true) . ',' .$asset->getUrl($transformLg, true) : null,
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
