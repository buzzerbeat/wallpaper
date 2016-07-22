<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/8
 * Time: 11:03
 */

namespace wallpaper\controllers;


use common\components\Utility;
use wallpaper\models\WpImage;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

class ImageController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors[] = [
//            'class' => 'yii\filters\HttpCache',
//            'only' => ['index'],
//            'lastModified' => function ($action, $params) {
//                $q = new \yii\db\Query();
//                return $q->from('random_cache')->max('updated_at');
//            },
//        ];


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
}