<?php

namespace wallpaper\models;

use Yii;

/**
 * This is the model class for table "album_img_rel".
 *
 * @property integer $id
 * @property integer $album_id
 * @property integer $wp_img_id
 */
class AlbumImgRel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'album_img_rel';
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
            [['album_id', 'wp_img_id'], 'required'],
            [['album_id', 'wp_img_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'wp_img_id' => 'Wp Img ID',
        ];
    }
}
