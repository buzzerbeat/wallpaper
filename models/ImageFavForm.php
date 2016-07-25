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

class ImageFavForm extends Model
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
        $wpFav = WpImageFav::find()->where([
            'wp_image_id' => $this->getId(),
            'user_id' => $this->userId,
            'fav' => 1,
        ])->one();
        if (!$wpFav) {
            $wpFav = new WpImageFav();
            $wpFav->wp_image_id = $this->getId();
            $wpFav->user_id = $this->userId;
            $wpFav->fav = 1;
            if (!$wpFav->save()) {
                $this->addErrors($wpFav->getErrors());
                return false;
            }
        }
        return true;
    }
}