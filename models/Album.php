<?php

namespace wallpaper\models;

use common\components\Utility;
use common\models\Image;
use Yii;

/**
 * This is the model class for table "album".
 *
 * @property integer $id
 * @property integer $status
 * @property string $title
 * @property string $key
 * @property integer $create_time
 * @property string $icon
 * @property string $section
 * */
class Album extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'album';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wpDb');
    }

    public function getImages() {
        return $this->hasMany(Image::className(), ['id' => 'img_id'])
            ->via('rels');
    }

    public function getRels()
    {
        return $this->hasMany(AlbumImgRel::className(), ['album_id'=>'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'create_time', 'icon'], 'integer'],
            [['title'], 'required'],
            [['title', 'section',  'key'], 'string', 'max' => 255],
        ];
    }


    public function getSid() {
        return Utility::sid($this->id);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['id'], $fields['status'], $fields['key']);
        $fields[] = 'sid';
        $fields[] = 'iconImg';
        return $fields;
    }

    public function getIconImg() {
        return Image::findOne($this->icon);
    }

    public function extraFields()
    {
        return ['images'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'title' => '类别名称',
            'create_time' => 'Create Time',
            'key' => 'Key/Tag',
            'icon' => 'Icon',
            'section' => '所属类别',
        ];
    }
}
