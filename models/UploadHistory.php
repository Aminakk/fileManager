<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "upload_history".
 *
 * @property int $id
 * @property string|null $filename
 * @property int|null $created_at
 */
class UploadHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['filename'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'created_at' => 'Created At',
        ];
    }
}
