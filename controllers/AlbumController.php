<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/8
 * Time: 10:33
 */

namespace wallpaper\controllers;


use common\components\Utility;
use wallpaper\models\Album;
use wallpaper\models\AlbumFavForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\rest\Controller;

class AlbumController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['fav', 'fav-list'],
        ];

        return $behaviors;
    }
    public function actionIndex()
    {
        $query = Album::find()->where(['status'=>Album::STATUS_ACTIVE]);
        return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')
        ]);
    }


    public function actionView($sid)
    {
        return Album::findOne(Utility::id($sid));
    }

    public function actionFavList() {
        $user = \Yii::$app->user->identity;
        $query =  Album::find()
            ->leftJoin('album_fav', '`album_fav`.`album_id` = `album`.`id`')
            ->where([
                'status' => Album::STATUS_ACTIVE,
                '`album_fav`.`user_id`' => $user->id,
            ]);
        return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')
        ]);
    }


    public function actionFav()
    {
        $favForm = new AlbumFavForm();
        if ($favForm->load(Yii::$app->getRequest()->post(), '') && $favForm->fav()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $favForm->getFirstErrors())];
    }
}