<?php

namespace app\controllers;


use Imagine\Image\Box;
use yii\db\Query;
use yii\imagine\Image;
use app\models\Product;
use yii\web\Controller;

class ImagesController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'products' => Product::find()->asArray()->all()
        ]);
    }


    public static function generateMiniature($url, $arr)
    {
        $path = \Yii::getAlias('@webroot/img.jpg');

        switch (true) {
            case !is_string($url):
                $result = 'url изображения должен быть строкой!';
                break;
            case !strlen($url) > 0 || $url == null:
                $result = 'url изображения не может быт пуст!';
                break;
            case !is_array($arr):
                $result = 'Параметры ресайза нужно передать в массиве!';
                break;
            case !count($arr) == 2:
                $result = 'В массив нужно передать ровно два параметра!';
                break;
            case array_key_exists('width', $arr) === false:
                $result = 'Ширину ресайза картинки нужно передать под ключом width!';
                break;
            case array_key_exists('height', $arr) === false:
                $result = 'Высоту ресайза картинки нужно передать под ключом height!';
                break;
            default:

                $row = (new Query())
                    ->select(['image'])
                    ->from('product')
                    ->where(['is_deleted' => '0', 'image' => $url])
                    ->one();

                Image::getImagine()->open($row['image'])
                    ->thumbnail(new Box($arr['width'], $arr['height']))
                    ->save($path, ['quality' => 90]);

                $result = $path;
                break;
        }
        return $result;
    }

}
