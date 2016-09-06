<?php
/**
 * Created by PhpStorm.
 * User: yx
 * Date: 2016/7/25
 * Time: 20:43
 */
namespace wallpaper\models;

use common\models\ImageForm;
use Yii;
use yii\base\Model;

class WpImageForm extends Model{
    public $url, $albumname;

    public function rules()
    {
        return [
            // username and password are both required
            [['url', 'albumname'], 'required'],
            // rememberMe must be a boolean value
            ['url', 'string'],
            ['albumname', 'string'],
        ];
    }

    public function save() {

        $album = Album::find()->where(['title'=>$this->albumname])->one();
        if (empty($album)) {
            $album = new Album;
            $album->title = $this->albumname;
            $album->status = 1;
            $album->key = '1';
            $album->create_time = time();
            if (!$album->save()) {
                print_r($album->getErrors());
            }
        }

        $wp = WpImage::find()->where(['source_md5'=>md5($this->url)])->one();
        if (empty($wp)) {
            $imageForm = new ImageForm;
            $imageForm->url = $this->url;
            $image = $imageForm->save();
            if (!empty($image)) {
                $wp = new WpImage();
                $wp->source_url = $this->url;
                $wp->img_id = $image->id;
                $wp->status = 1;
                $wp->source_md5 = md5($this->url);
                if (!$wp->save()) {
                    print_r($wp->getErrors());
                }
            }
        }


        if (!empty($wp) && !empty($album)) {
            $wprel = AlbumImgRel::find()->where(['album_id'=>$album->id, 'wp_img_id'=>$wp->id])->one();
            if (empty($wprel)) {
                $wprel = new AlbumImgRel();
                $wprel->album_id = $album->id;
                $wprel->wp_img_id = $wp->id;
                $wprel->save();
            }
        }
        return true;
    }
}