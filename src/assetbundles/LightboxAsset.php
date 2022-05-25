<?php
/**
 * Lightbox plugin for Craft CMS 3.x
 *
 * Render a Lightbox from a given image.
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox\assetbundles;

use Craft;
use craft\web\AssetBundle;

class LightboxAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@dodecastudio/lightbox/resources/dist';

        $this->js = [
            'js/lightbox-min.js',
        ];

        parent::init();
    }
}