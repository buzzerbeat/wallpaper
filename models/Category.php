<?php

namespace wallpaper\models;

use common\components\Utility;
use Yii;
use wallpaper\models\Album;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $keyword
 * @property integer $rank
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
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
            [['name'], 'required'],
            [['rank'], 'integer'],
            [['name', 'keyword'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'keyword' => 'Keyword',
            'rank' => 'Rank',
        ];
    }

    public function getSid() {
        return Utility::sid($this->id);
    }
    
    public function extraFields(){
    	return ['albums'];
    }

    public function fields()
    {
        $fields = [
            'sid',
            'name',
            'keyword',
        ];
        return $fields;
    }
    
    public function getAlbums(){
    	return $this->hasMany(Album::className(), ['category' => 'id']);
    }
}
