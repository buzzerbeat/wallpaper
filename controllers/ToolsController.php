<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/6
 * Time: 14:46
 */

namespace wallpaper\controllers;


use common\components\Utility;
use Yii;
use yii\web\Controller;

class ToolsController extends Controller
{
    public function actionSid() {
        $id = Yii::$app->request->get('id');
        return Utility::sid($id);
    }
    public function actionId() {
        $sid = Yii::$app->request->get('sid');
        return Utility::id($sid);
    }



}