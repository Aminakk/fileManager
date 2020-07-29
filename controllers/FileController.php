<?php

namespace app\controllers;

use app\models\DeleteHistory;
use app\models\UploadHistory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;

class FileController extends Controller
{

    /**
     * Displays file listing page.
     *
     * @return string
     */
    public function actionIndex()
    {
        $path = './files/';

        $search = Yii::$app->request->post('search', null);
        if ($search === null) {
            $search = '*';
        }

        $files = glob($path . $search);
        $files = array_diff($files, array('.', '..'));
        $count = count($files);

        $limit = 5;
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $limit]);
        $offset = $pages->offset;

        $files = array_splice($files, $offset, $limit);

        $model = new UploadForm();

        return $this->render('index', [
                'files' => $files,
                'pages' => $pages,
                'offset' => $offset,
                'search' => Yii::$app->request->post('search', null),
                'path' => $path,
                'model' => $model,
            ]
        );
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $model->file->saveAs('./files/' . $model->file->baseName . '.' . $model->file->extension);
                $uploadHistory = new UploadHistory();
                $uploadHistory->filename = $model->file->baseName;
                $uploadHistory->created_at = time();
                $uploadHistory->save();
            }
        }

        return Yii::$app->response->redirect(['file/index']);
    }

    public function actionUploadHistory()
    {
        $query = UploadHistory::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        return $this->renderPartial('upload-history', ['dataProvider' => $dataProvider]);
    }

    public function actionDeleteHistory()
    {
        $query = DeleteHistory::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        return $this->renderPartial('delete-history', ['dataProvider' => $dataProvider]);
    }

    public function actionDelete()
    {
        $path = './files/';
        $file = Yii::$app->request->post('file');
        if(unlink($file)) {
            $deleteHistory = new DeleteHistory();
            $deleteHistory->filename = str_replace($path, '', $file);
            $deleteHistory->created_at = time();
            $deleteHistory->save();
            return 'ok';
        }
        return 'no';
    }
}
