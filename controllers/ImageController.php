<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/8
 * Time: 11:03
 */

namespace wallpaper\controllers;


use common\components\Utility;
use wallpaper\models\ImageFavForm;
use wallpaper\models\ImageLikeForm;
use wallpaper\models\WpImage;
use wallpaper\models\WpImageFav;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class ImageController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => 'yii\filters\HttpCache',
            'only' => ['index'],
            'lastModified' => function ($action, $params) {
                return WpImage::find()->max('id');
            },
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['fav',  'like', 'fav-list'],
        ];


        return $behaviors;
    }
    public function actionIndex()
    {
        $album = \Yii::$app->request->get('cat', 0);
        $query =  WpImage::find()
            ->leftJoin('album_img_rel', '`album_img_rel`.`wp_img_id` = `wp_image`.`id`')
            ->where([
                'status' => WpImage::STATUS_ACTIVE,
            ]);
        if ($album) {
            $query->andWhere(['`album_img_rel`.`album_id`' => $album]);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')
        ]);
    }


    public function actionView($sid)
    {
        return WpImage::findOne(Utility::id($sid));
    }

    public function actionFavList() {
        $user = \Yii::$app->user->identity;
        $query =  WpImage::find()
            ->leftJoin('wp_image_fav', '`wp_image_fav`.`wp_image_id` = `wp_image`.`id`')
            ->where([
                'status' => WpImage::STATUS_ACTIVE,
                '`wp_image_fav`.`user_id`' => $user->id,
            ]);
        return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')
        ]);
    }

    public function actionLike()
    {
        $likeForm = new ImageLikeForm();
        if ($likeForm->load(Yii::$app->getRequest()->post(), '') && $likeForm->like()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $likeForm->getFirstErrors())];
    }

    public function actionFav()
    {
        $favForm = new ImageFavForm();
        if ($favForm->load(Yii::$app->getRequest()->post(), '') && $favForm->fav()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $favForm->getFirstErrors())];

    }

}