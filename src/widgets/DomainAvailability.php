<?php
/**
 * Selling site for HiPanel
 *
 * @link      http://hipanel.com/
 * @package   hipanel-site
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\site\widgets;

use Yii;
use yii\base\Widget;

class DomainAvailability extends Widget
{
    public $backgroundImageEn = 'https://ahnames.com/www/flat.skin/images/banner/afilias/en.jpg';

    public $backgroundImageRu = 'https://ahnames.com/www/flat.skin/images/banner/afilias/ru.jpg';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = Yii::$app->view;
        $internationalizationImage = 'backgroundImage' . ucfirst(substr(Yii::$app->language, 0, 2));
        $view->registerCss(sprintf('.domainavailability { background-image: url(%s); }', $this->$internationalizationImage));

        return $this->render((new\ReflectionClass($this))->getShortName(), []);
    }
}
