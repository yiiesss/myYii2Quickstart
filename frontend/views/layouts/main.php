<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use \lajax\languagepicker\widgets\LanguagePicker;

/* @var $this \yii\web\View */
/* @var $content string */
if(Yii::$app->language !='ar'){
    Yii::$app->assetManager->bundles['airani\bootstrap\BootstrapRtlAsset'] = false;
}
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="<?= Yii::$app->language == 'ar' ? 'rtl' : '' ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::t('app', Yii::$app->name),
                'brandUrl' => Yii::$app->urlManagerFrontEnd->baseUrl,
                'options' => [
                ],
            ]);
            $menuItems = [];
            if (Yii::$app->user->isGuest) {
                //$menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/user-management/auth/login']];
            } else {
                $menuItems[] = [
                    'label' => Yii::t('app', 'Déconnexion') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/user-management/auth/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }

            echo Nav::widget([
                'options' => ['class' => Yii::$app->language =='ar'?'navbar-nav navbar-left':'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            ?>
            <div class="navbar-text">
                <?=
                LanguagePicker::widget([
                    'skin' => LanguagePicker::SKIN_DROPDOWN,
                    'size' => LanguagePicker::SIZE_LARGE
                ]);
                ?>
            </div>
            <?php
            NavBar::end();
            ?>

            <div class="container body">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <br/>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; <?= Yii::t('app', Yii::$app->name) ?> <?= date('Y') ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
