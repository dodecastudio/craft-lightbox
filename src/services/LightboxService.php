<?php
/**
 * Lightbox plugin for Craft CMS 4.x
 *
 * Render a Lightbox from a given image.
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox\services;

use dodecastudio\lightbox\Lightbox;

use Craft;
use craft\base\Component;
use craft\elements\Asset;
use craft\fields\data\ColorData;
use craft\validators\ColorValidator;

class LightboxService extends Component
{

    // private $DEFAULT_INFO_TYPE = 'required';

    /**
     * lightbox: Take an array of image assets and render a lightbox.
     *
     * @param assets Array
     *
     * @return string
     */
    public function lightbox($assets = [])
    {   
        
        // Place param in array if its not an array itself
        if (!is_array($assets)) {
            $assets = array($assets);
        }

        // Check each item in the array is a valid asset
        foreach ($assets as &$asset) {
            if (!$asset instanceof Asset) {
                return false;
            }
        }

        return 'gallery';
    }
    
}
