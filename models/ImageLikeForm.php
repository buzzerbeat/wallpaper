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

class ImageLikeForm extends Model
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

    public function like()
    {
        $this->userId = \Yii::$app->user->identity->id;
        $wpLike = WpImageLike::find()->where([
            'wp_image_id' => $this->getId(),
            'user_id' => $this->userId,
            'like' => 1,
        ])->one();
        if (!$wpLike) {
            $wpLike = new WpImageLike();
            $wpLike->wp_image_id = $this->getId();
            $wpLike->user_id = $this->userId;
            $wpLike->like = 1;
            if (!$wpLike->save()) {
                $this->addErrors($wpLike->getErrors());
                return false;
            }
        }
        return true;
    }
}