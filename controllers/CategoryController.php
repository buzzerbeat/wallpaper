<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/8
 * Time: 10:33
 */

namespace wallpaper\controllers;


use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use wallpaper\models\Category;
use common\components\Utility;

class CategoryController extends Controller
{
    public $modelClass = 'wallpaper\models\Category';
    
    public function actionIndex(){
        $query = Category::find();
        
        return new ActiveDataProvider([
            'query' => $query->orderBy('rank desc')
        ]);
    }
    
    public function actionView($sid){
        return Category::findOne(Utility::id($sid));
    }
    
}