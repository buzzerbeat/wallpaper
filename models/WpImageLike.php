<?php

namespace wallpaper\models;

use Yii;

/**
 * This is the model class for table "wp_image_like".
 *
 * @property integer $id
 * @property integer $wp_image_id
 * @property integer $user_id
 * @property integer $like
 */
class WpImageLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_image_like';
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
            [['wp_image_id', 'user_id', 'like'], 'required'],
            [['wp_image_id', 'user_id', 'like'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wp_image_id' => 'Wp Image ID',
            'user_id' => 'User ID',
            'like' => 'Like',
        ];
    }
}
