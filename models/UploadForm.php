<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $statement;

    public function rules()
    {
        return [
            [['statement'], 'file', 'skipOnEmpty' => true, 'extensions' => 'html'],
        ];
    }
}