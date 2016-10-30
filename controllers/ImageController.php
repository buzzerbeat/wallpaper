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
use wallpaper\models\AlbumImgRel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class ImageController extends Controller
{
    public $modelClass = 'wallpaper\models\WpImage';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => 'yii\filters\PageCache',
            'only' => ['index'],
            'duration' => 180,
            'variations' => [
                \Yii::$app->request->get('album', ''),
                \Yii::$app->request->get('photo', ''),
                \yii::$app->request->get('page', 0),
                \yii::$app->request->get('per-page', 54),
                \yii::$app->request->get('expand', ''),
                \yii::$app->request->get('cachekey', ''),
            ],
            'dependency' => [
                'class' => 'common\components\WpDbDependency',
                'sql' => 'SELECT COUNT(*) FROM wp_image',
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['fav',  'like', 'fav-list', 'like-list'],
        ];


        return $behaviors;
    }
    public function actionIndex()
    {
        $album = \Yii::$app->request->get('album', '');
        $photo = \Yii::$app->request->get('photo', '');
         
        $query = WpImage::find()->where(['status'=>WpImage::STATUS_ACTIVE]);
        if(!empty($album) || !empty($photo)){
            $query->leftJoin('album_img_rel', '`wp_image`.`id` = `album_img_rel`.`wp_img_id`');
             
            if(!empty($album)){
                $query->andWhere(['`album_img_rel`.`album_id`' => Utility::id($album)]);
            }
            if(!empty($photo)){
                $photoId = Utility::id($photo);
                $albums = AlbumImgRel::find()->select('album_id')->where(['wp_img_id'=>$photoId])->all();
                $albumIds = [];
                foreach($albums as $alb){
                    $albumIds[] = $alb->album_id;
                }
                $query->andWhere(['or', '`wp_image`.`id` =' . Utility::id($photo), ['in', '`album_img_rel`.`album_id`', $albumIds]]);
            }
        }
        
        return new ActiveDataProvider([
            'query' => empty($photo) ? $query->orderBy('rank desc') : $query->orderBy("`wp_image`.`id` = {$photoId} desc, rank desc")
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
    
    public function actionLikeList() {
        $user = \Yii::$app->user->identity;
        $query =  WpImage::find()
            ->leftJoin('wp_image_like', '`wp_image_like`.`wp_image_id` = `wp_image`.`id`')
            ->where([
                'status' => WpImage::STATUS_ACTIVE,
                '`wp_image_like`.`user_id`' => $user->id,
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
