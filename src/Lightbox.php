<?php
/**
 * Lightbox plugin for Craft CMS 4.x
 *
 * @link      https://dodeca.studio
 * @copyright Copyright (c) 2022 Dodeca Studio
 */

namespace dodecastudio\lightbox;

use dodecastudio\lightbox\models\Settings;
use dodecastudio\lightbox\services\LightboxService;
use dodecastudio\lightbox\twigextensions\LightboxTwigExtension;
use dodecastudio\lightbox\variables\LightboxVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;


/**
 * 
 * @author    Dodeca Studio
 * @package   Lightbox
 * @since     1.0.0
 *
 */
class Lightbox extends Plugin
{

    // Static Properties

    /**
     * @var Lightbox
     */
    public static ?Lightbox $plugin = null;

    // Public Properties

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSettings = false;

    /**
     * @var bool
     */
    public bool $hasCpSection = false;

    // Public Methods

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        $config['components'] = [
            'lightbox' => LightboxService::class,
        ];

        parent::__construct($id, $parent, $config);
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // $this->setComponents([
        //     'lightboxServices' => LightboxService::class,
        // ]);
        
        // Add in our Twig extensions
        Craft::$app->view->registerTwigExtension(new LightboxTwigExtension());

        // Add in Variables
        Event::on(
            CraftVariable::class, 
            CraftVariable::EVENT_INIT, 
            static function(Event $event):void {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('lightbox', LightboxVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'lightbox',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

    }

    // Settings

    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

}

