<?php

/*
 * Selling site for HiPanel
 *
 * @link      http://hipanel.com/
 * @package   hipanel-site
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\site\menus;

use Yii;

class FooterMenu extends \hiqdev\menumanager\Menu
{
    protected $_addTo = 'footer';

    public function items()
    {
        return [
            ['label' => Yii::t('hisite', 'Terms of use'),   'url' => ['/pages/rules']],
            ['label' => Yii::t('hisite', 'Privacy policy'), 'url' => ['/pages/rules#privacyPolicy']],
        ];
    }
}