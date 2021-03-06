<?php
use hipanel\modules\domain\assets\DomainCheckPluginAsset;
use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;

DomainCheckPluginAsset::register($this);

$this->registerJs(/** @lang text/javascript */
    <<<'JS'
    // DOMAIN CHECK
    $(document).on('click', 'a.add-to-cart-button', function(event) {
        event.preventDefault();
        var addToCartElem = $(this);
        addToCartElem.button('loading');
        $.post(addToCartElem.data('domain-url'), function() {
            Hisite.updateCart(addToCartElem.data('topcart'), function() {
                addToCartElem.button('complete');
                setTimeout(function () {
                    addToCartElem.addClass('disabled');
                }, 0);
            });
        });

        return false;
    });

    $.fn.isOnScreen = function(x, y){

        if(x == null || typeof x == 'undefined') x = 1;
        if(y == null || typeof y == 'undefined') y = 1;

        var win = $(window);

        var viewport = {
            top : win.scrollTop(),
            left : win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();

        var height = this.outerHeight();
        var width = this.outerWidth();

        if(!width || !height){
            return false;
        }

        var bounds = this.offset();
        bounds.right = bounds.left + width;
        bounds.bottom = bounds.top + height;

        var visible = (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

        if(!visible){
            return false;
        }

        var deltas = {
            top : Math.min( 1, ( bounds.bottom - viewport.top ) / height),
            bottom : Math.min(1, ( viewport.bottom - bounds.top ) / height),
            left : Math.min(1, ( bounds.right - viewport.left ) / width),
            right : Math.min(1, ( viewport.right - bounds.left ) / width)
        };

        return (deltas.left * deltas.right) >= x && (deltas.top * deltas.bottom) >= y;
    };

    $('.domain-list').domainsCheck({
        domainRowClass: '.domain-line',
        success: function(data, domain, element) {
            var $elem = $(element).find("div[data-domain='" + domain + "']");
            var $parentElem = $(element).find("div[data-domain='" + domain + "']").parents('div.domain-iso-line').eq(0);
            $elem.replaceWith($(data).find('.domain-line'));
            $parentElem.attr('class', $(data).attr('class'));

            return this;
        },
        beforeQueryStart: function (item) {
            var $item = $(item);
            if ($item.isOnScreen() && !$item.hasClass('checked') && $item.is(':visible')) {
                $item.addClass('checked');
                return true;
            }

            return false;
        },
        finally: function () {
            // init Isotope
            var grid = $('.domain-list').isotope({
                itemSelector: '.domain-iso-line',
                layout: 'vertical',
                // disable initial layout
                isInitLayout: false
            });
            //grid.isotope({ filter: '.popular' });
            // bind event
            grid.isotope('on', 'arrangeComplete', function () {
                $('.domain-iso-line').css({'visibility': 'visible'});
                $('.domain-list').domainsCheck().startQuerier();
            });
            // manually trigger initial layout
            grid.isotope();
            // store filter for each group
            var filters = {};
            $('.filters').on('click', 'a', function(event) {
                event.preventDefault();
                // get group key
                var $buttonGroup = $(this).parents('.filter-nav');
                var $filterGroup = $buttonGroup.attr('data-filter-group');
                // set filter for group
                filters[$filterGroup] = $(this).attr('data-filter');
                // combine filters
                var filterValue = concatValues(filters);
                // set filter for Isotope
                grid.isotope({filter: filterValue});

                $('html, body').animate({ scrollTop: $('.domain-form-container').offset().top }, 'fast');
            });
            // change is-checked class on buttons
            $('.filter-nav').each(function(i, buttonGroup) {
                $(buttonGroup).on( 'click', 'a', function(event) {
                    $(buttonGroup).find('.active').removeClass('active');
                    $(this).parents('li').addClass('active');
                });
            });
            // flatten object by concatting values
            function concatValues(obj) {
                var value = '';
                for (var prop in obj) {
                    value += obj[prop];
                }

                return value;
            }
        }
    });

    $(document).on('scroll', function() {
        if ($('.domain-list').length) {
            $('.domain-list').domainsCheck().startQuerier();
        }
    });
JS
);

$this->title = Yii::t('hipanel/domainchecker', 'Domain check');
$this->blocks['subHeaderClass'] = 'domainavailability';
$this->blocks['dropDownZonesOptions'] = $dropDownZonesOptions;

?>

<?php $this->beginBlock('subHeader') ?>
    <?= $this->render('//site/_domainSearchForm', ['model' => $model]) ?>
<?php $this->endBlock() ?>
<!-- Blog -->
<div class="blog">
    <div class="row">
        <div class="col-sm-8">
            <article>
                <div class="post-content">
                    <div class="domain-list">
                        <?php foreach ($results as $model) : ?>
                            <?= $this->render('_checkDomainItem', ['model' => $model]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-sm-4 filters">
            <div class="sidebar">

                <div class="widget">
                    <h3 class="badge"><?= Yii::t('hipanel/domainchecker', 'Status') ?></h3>
                    <ul class="filter-nav" data-filter-group="status">
                        <li class="active"><a href="#" data-filter=""><?= Yii::t('hipanel/domainchecker', 'All') ?></a>
                        </li>
                        <li><a href="#" data-filter=".available"><?= Yii::t('hipanel/domainchecker', 'Available') ?></a>
                        </li>
                        <li><a href="#"
                               data-filter=".unavailable"><?= Yii::t('hipanel/domainchecker', 'Unavailable') ?></a>
                        </li>
                    </ul>
                </div>

                <div class="widget">
                    <h3 class="badge"><?= Yii::t('hipanel/domainchecker', 'Special') ?></h3>
                    <ul class="filter-nav" data-filter-group="status">
                        <li class="active"><a href="#" data-filter=""><?= Yii::t('hipanel/domainchecker', 'All') ?></a>
                        </li>
                        <li><a href="#"
                               data-filter=".popular"><?= Yii::t('hipanel/domainchecker', 'Popular Domains') ?></a></li>
                        <li><a href="#" data-filter=".promotion"><?= Yii::t('hipanel/domainchecker', 'Promotion') ?></a>
                        </li>
                    </ul>
                </div>

                <div class="widget">
                    <h3 class="badge"><?= Yii::t('hipanel/domainchecker', 'Categories') ?></h3>
                    <ul class="filter-nav" data-filter-group="status">
                        <li class="active">
                            <a href="#" data-filter=""><?= Yii::t('hipanel/domainchecker', 'All') ?>
                                <span class="badge"><?= count($results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".adult"><?= Yii::t('hipanel/domainchecker', 'Adult') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('adult', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".geo"><?= Yii::t('hipanel/domainchecker', 'GEO') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('geo', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".general"><?= Yii::t('hipanel/domainchecker', 'General') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('general', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".nature"><?= Yii::t('hipanel/domainchecker', 'Nature') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('nature', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".internet"><?= Yii::t('hipanel/domainchecker', 'Internet') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('internet', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".sport"><?= Yii::t('hipanel/domainchecker', 'Sport') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('sport', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".society"><?= Yii::t('hipanel/domainchecker', 'Society') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('society', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".audio_music"><?= Yii::t('hipanel/domainchecker', 'Audio&Music') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('audio_music', $results) ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-filter=".home_gifts"><?= Yii::t('hipanel/domainchecker', 'Home&Gifts') ?>
                                <span class="badge"><?= Domain::getCategoriesCount('home_gifts', $results) ?></span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- End of Blog -->
