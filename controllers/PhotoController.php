<?php

namespace wallpaper\controllers;


use yii\rest\Controller;
use common\components\Utility;
use wallpaper\models\WpImage;
use wallpaper\models\AlbumImgRel;
use yii\data\ActiveDataProvider;


class PhotoController extends Controller
{
    public $modelClass = 'wallpaper\models\WpImage';
    
    public function behaviors(){
    	
        return parent::behaviors();
    }
    
    public function actionPhotoList(){
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
    
    public function actionView($sid){
        return WpImage::findOne(Utility::id($sid));
    }
}