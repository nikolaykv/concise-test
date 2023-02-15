<?php

namespace app\controllers;

use yii\db\Query;
use Imagine\Image\Box;
use yii\imagine\Image;
use app\models\Product;
use yii\web\Controller;

class ImagesController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'products' => Product::find()->asArray()->all()
        ]);
    }

    /**
     * @param $url
     * @param $arr
     * @return false|string
     */
    protected static function consiseImageHandler($url, $arr)
    {
        $error = false;

        switch (true) {
            case !is_string($url):
                $error = 'url изображения должен быть строкой!';
                break;
            case !strlen($url) > 0 || $url == null:
                $error = 'url изображения не может быт пуст!';
                break;
            case !is_array($arr):
                $error = 'Параметры ресайза нужно передать в массиве!';
                break;
            case !count($arr) == 2:
                $error = 'В массив нужно передать ровно два параметра!';
                break;
            case array_key_exists('width', $arr) === false:
                $error = 'Ширину ресайза картинки нужно передать под ключом width!';
                break;
            case array_key_exists('height', $arr) === false:
                $error = 'Высоту ресайза картинки нужно передать под ключом height!';
                break;
        }
        return $error;
    }


    /**
     * @param $url
     * @param $arr
     * @return false|string
     */
    public static function generateMiniature($url, $arr)
    {
        $path = \Yii::getAlias('@webroot/img.jpg');
        $error = self::consiseImageHandler($url, $arr);

        if ($error) {
            $result = $error;
        } else {
            $row = (new Query())
                ->select(['image'])
                ->from('product')
                ->where(['is_deleted' => '0', 'image' => $url])
                ->one();

            Image::getImagine()->open($row['image'])
                ->thumbnail(new Box($arr['width'], $arr['height']))
                ->save($path, ['quality' => 90]);
            $result = $path;
        }
        return $result;
    }


    /**
     * @param $url
     * @param $arr
     * @return false|string
     */
    public static function generateWatermarkedMiniature($url, $arr)
    {
        $path = \Yii::getAlias('@webroot/img-watermark.jpg');
        $watermark = \Yii::getAlias('@webroot/watermark.png');

        $error = self::consiseImageHandler($url, $arr);

        if ($error) {
            $result = $error;
        } else {
            $image = self::generateMiniature($url, $arr);

            Image::watermark($image, $watermark)
                ->save($path);

            $result = $path;
        }

        return $result;
    }

}
