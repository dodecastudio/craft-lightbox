<?php
/**
 * Lightbox plugin for Craft CMS 3.x
 *
 * Render a Lightbox from a given image.
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox\twigextensions;

use dodecastudio\lightbox\Lightbox;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LightboxTwigExtension extends AbstractExtension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Craft Lightbox';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something'|someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            // new TwigFilter('lightbox', [$this, 'lightbox']),
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            // new TwigFunction('lightbox', [$this, 'lightbox']),
        ];
    }


    /**
     * lightbox: Take an array of image assets and render a lightbox
     *
     * @param assets Array
     *
     * @return string
     */
    public function lightbox($assets)
    {
        // return Lightbox::getInstance()->lightboxServices->lightbox($assets);
    }

}