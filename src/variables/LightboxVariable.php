<?php
/**
 * Lightbox plugin for Craft CMS 3.x
 *
 * Render a Lightbox from a given image.
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox\variables;

use dodecastudio\lightbox\Lightbox;

use Craft;
use craft\helpers\Template;
use craft\elements\Asset;
use craft\elements\db\AssetQuery;
use craft\web\View;

use Twig\Markup;

class LightboxVariable
{
  public function gallery(AssetQuery $assetsQuery = null, String $title = '', String $galleryRef = null) : Markup
  {
      $assets = $assetsQuery->kind('image')->all();

      $template = Craft::$app->getView()->renderTemplate('lightbox/_frontend/gallery.twig', [
        'assets' => $assets,
        'title' => $title,
        'galleryRef' => $galleryRef,
        'settings' => Lightbox::getInstance()->getSettings(),
      ], View::TEMPLATE_MODE_CP);

      return Template::raw($template);
  }

  public function render(String $closeButton = null, String $previousButton = null, String $nextButton = null) : Markup
  {
      $template = Craft::$app->getView()->renderTemplate('lightbox/_frontend/lightbox.twig', [
        'settings' => Lightbox::getInstance()->getSettings(),
        'closeButton' => $closeButton,
        'previousButton' => $previousButton,
        'nextButton' => $nextButton,
      ], View::TEMPLATE_MODE_CP);

      return Template::raw($template);
  }

  public function linkAttrs(Asset $asset = null, String $galleryRef = null) : Markup
  {

      $template = Craft::$app->getView()->renderTemplate('lightbox/_frontend/linkAttrs.twig', [
        'asset' => $asset,
        'galleryRef' => $galleryRef,
        'settings' => Lightbox::getInstance()->getSettings(),
      ], View::TEMPLATE_MODE_CP);

      return Template::raw($template);
  }

}