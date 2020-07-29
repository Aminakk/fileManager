<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;

?>

<div class="container">
    <h4>Files Listing</h4>
    <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#exampleModal">
        Upload File
    </button>
    <button type="button" style="margin-right: 4px;" class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#uploadHistoryModal">
        Upload History
    </button>
    <button type="button" style="margin-right: 4px;" class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#deleteHistoryModal">
        Delete History
    </button>
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['file/index']),
        'options' => ['class' => 'form-inline']
    ]); ?>

    <?= Html::textInput('search', $search, ['class' => 'form-control']); ?>

    <?php
    if ($search !== null) {
        echo Html::a("Clear", Url::to(['file/index'], ['class' => 'btn btn-default']));
    } else {
        echo Html::submitButton('Search', ['class' => 'btn btn-primary']);
    }
    ?>



    <?php ActiveForm::end(); ?>
    <hr/>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Filename</th>
            <th></th>
        </tr>
        <?php
        foreach ($files as $file) { ?>
            <tr>
                <td><?= ++$offset ?></td>
                <td><?= str_replace($path, '', $file) ?></td>
                <td><a href="#" class="delete-file text-danger" data-file="<?= $file ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
    <?php
    echo LinkPager::widget([
        'pagination' => $pages,
    ]);
    ?>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Upload a file</h4>
                </div>
                <div class="modal-body">
                    <?php
                    $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
                        'action' => \yii\helpers\Url::to(['file/upload']),
                        'enableClientValidation' => true
                    ]); ?>

                    <?= $form->field($model, 'file')->fileInput() ?>

                    <button class="btn btn-primary">Submit</button>

                    <?php ActiveForm::end(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-link" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Upload History</h4>
                </div>
                <div class="modal-body" id="upload-history">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Delete History</h4>
                </div>
                <div class="modal-body" id="delete-history">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = " 
$(document).ready(function(){  
    $('#uploadHistoryModal').on('show.bs.modal', function (e) {
        $.ajax({
            type: 'GET',
            url:'" . Url::to(['/file/upload-history']) . "',
            success: function(html) {
                $('#upload-history').html(html);
            }
        });
    });
    
    $('#deleteHistoryModal').on('show.bs.modal', function (e) {
        $.ajax({
            type: 'GET',
            url:'" . Url::to(['/file/delete-history']) . "',
            success: function(html) {
                $('#delete-history').html(html);
            }
        });
    });
    
    $('.delete-file').click(function(e){
        e.preventDefault();
        if (confirm('Are you sure you want to delete this file?')) {
            let file = $(this).data('file');
            $.post('" . Url::to(['/file/delete']) . "', {file: file}, function(html) {
                if (html === 'ok') {
                    window.location.reload();
                } else {
                    alert('Could not delete file!');
                }
            });
        }
    });
});
";
$this->registerJs($js, View::POS_READY, 'show-upload');
?>
