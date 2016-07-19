<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/8
 * Time: 10:33
 */

namespace wallpaper\controllers;


use yii\rest\ActiveController;

class AlbumController extends ActiveController
{
    public $modelClass = 'wallpaper\models\Album';
}