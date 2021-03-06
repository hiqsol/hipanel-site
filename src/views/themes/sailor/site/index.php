<?php

use hipanel\modules\finance\widgets\AvailableMerchants;
use hipanel\site\widgets\DomainPriceTable;

/** @var array $availableMerchants */
$this->title = Yii::t('hipanel:site:domain', 'Domain names search and registration');
?>

<?php $this->beginBlock('subHeader') ?>
    <?= $this->render('//site/_domainSearchForm', ['model' => $model]) ?>
<?php $this->endBlock() ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                <h2><?= Yii::t('hipanel:site:domain', 'Domain pricing') ?></h2>
                <p>
                    <?= Yii::t('hipanel:site:domain', 'Today our company, offers the most competitive and fair prices for domain registration services. Due to the large number of domains that are in our care, we get a significant discount from our registrar. This creates a very positive impact on the price of domain names offered to you!') ?> </p>
	            </p>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="solidline">
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?= DomainPriceTable::widget([
                'domains' => $domains,
                'promotion' => $promotion,
                'domainZones' => $domainZones,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
            ]) ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="solidline">
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h2><?= Yii::t('hipanel:site:domain', 'PAYMENT METHODS') ?></h2>
                <p>
	                <?= Yii::t('hipanel:site:domain', 'We accept the following automatic payment methods') ?>:
                    <?= AvailableMerchants::widget(['merchants' => $availableMerchants]) ?>
                    <?= Yii::t('hipanel:site:domain', 'as well as PayPal payments from your Visa and MasterCard') ?>
                </p>
            </div>
        </div>
    </div>
</div>
