<?php
/**
 * Lightbox plugin for Craft CMS 4.x
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

  public function gallery(AssetQuery $assetsQuery = null, String $galleryTitle = null) : Markup
  {
      $assets = $assetsQuery->kind('image')->all();

      $galleryRef = Lightbox::getInstance()->lightboxServices->getGalleryRef($galleryTitle);

      $template = Craft::$app->getView()->renderTemplate('lightbox/_frontend/gallery.twig', [
        'assets' => $assets,
        'galleryTitle' => $galleryTitle,
        'galleryRef' => $galleryRef,
        'settings' => Lightbox::getInstance()->getSettings(),
      ], View::TEMPLATE_MODE_CP);

      return Template::raw($template);
  }

  public function linkAttrs(Asset $asset = null, String $galleryTitle = null, String $galleryRef = null) : Array
  {
      return Lightbox::getInstance()->lightboxServices->getLinkAttributes($asset, $galleryTitle, $galleryRef);
  }

  public function galleryAttrs(String $galleryTitle = null, String $galleryRef = null) : Array
  {
      return Lightbox::getInstance()->lightboxServices->getGalleryAttributes($galleryTitle, $galleryRef);
  }

  public function getGalleryRef(String $galleryTitle = null) : String
  {
      return Lightbox::getInstance()->lightboxServices->getGalleryRef($galleryTitle);
  }

  public function getSettings() : Object
  {
      return Lightbox::getInstance()->getSettings();
  }

  public function getSettingValue(String $settingName = null) : String
  {
      if (isset($settingName) && isset(Lightbox::getInstance()->getSettings()[$settingName])) {
        return Lightbox::getInstance()->getSettings()[$settingName];
      }
      return false;
  }

}