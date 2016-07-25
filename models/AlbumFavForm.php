<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/25
 * Time: 13:39
 */

namespace wallpaper\models;


use common\components\Utility;
use yii\base\Model;

class AlbumFavForm extends Model
{
    public $sid;
    private $userId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sid'], 'required'],
        ];
    }

    public function getId() {
        return Utility::id($this->sid);
    }

    public function fav()
    {
        $this->userId = \Yii::$app->user->identity->id;
        $abFav = AlbumFav::find()->where([
            'album_id' => $this->getId(),
            'user_id' => $this->userId,
            'fav' => 1,
        ])->one();
        if (!$abFav) {
            $abFav = new AlbumFav();
            $abFav->album_id = $this->getId();
            $abFav->user_id = $this->userId;
            $abFav->fav = 1;
            if (!$abFav->save()) {
                $this->addErrors($abFav->getErrors());
                return false;
            }
        }
        return true;
    }
}