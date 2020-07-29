<?php

/* @var $this yii\web\View */


use yii\grid\GridView; ?>
<div class="site-index">

    <div class="body-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'filename',
                'created_at:datetime'
                // ...
            ],
        ]) ?>
    </div>
</div>

