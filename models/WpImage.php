<?php

namespace wallpaper\models;

use common\components\Utility;
use common\models\Image;
use Yii;

/**
 * This is the model class for table "wp_image".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $img_id
 * @property string $desc
 * @property string $source_url
 */
class WpImage extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_MAP = [
        self::STATUS_INACTIVE => "不可用",
        self::STATUS_ACTIVE => "可用",
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_image';
    }


    public function getImage() {
        return $this->hasOne(Image::className(), ['id' => 'img_id']);
    }

    public function getRel() {
        return $this->hasOne(AlbumImgRel::className(), ['wp_img_id'=>'id']);
    }

    public function getAlbum() {
        return $this->hasOne(Album::className(), ['id' => 'album_id'])
            ->via('rel');
    }

    public function getSid() {
        return Utility::sid($this->id);
    }

    public function fields()
    {
        $fields = [
            'sid',
            'image',
            'album',
        ];
        return $fields;
    }


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */


    public static function getDb()
    {
        return Yii::$app->get('wpDb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'img_id'], 'required'],
            [['status', 'img_id'], 'integer'],
            [['desc', 'source_url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'img_id' => 'Img ID',
            'desc' => 'Desc',
            'source_url' => 'Source Url',
        ];
    }
}
