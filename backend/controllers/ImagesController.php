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
     * @return void
     */
    protected static function consiseImageHandler($url, $arr)
    {
        try {
            switch (true) {
                case !is_string($url):
                    throw new \Exception('url изображения должен быть строкой!');
                case !strlen($url) > 0 || $url == null:
                    throw new \Exception('url изображения не может быт пуст!');
                case !is_array($arr):
                    throw new \Exception('Параметры ресайза нужно передать в массиве!');
                case !count($arr) == 2:
                    throw new \Exception('В массив нужно передать ровно два параметра!');
                case array_key_exists('width', $arr) === false:
                    throw new \Exception('Ширину ресайза картинки нужно передать под ключом width!');
                case array_key_exists('height', $arr) === false:
                    throw new \Exception('Высоту ресайза картинки нужно передать под ключом height!');
            }
        } catch (\Exception $exception) {
            \Yii::error($exception->getMessage());
        }
    }

    /**
     * @param $url
     * @param $arr
     * @return false|string
     */
    public static function generateMiniature($url, $arr)
    {
        $path = \Yii::getAlias('@webroot/img.jpg');

        self::consiseImageHandler($url, $arr);

        $row = (new Query())
            ->select(['image'])
            ->from('product')
            ->where(['is_deleted' => '0', 'image' => $url])
            ->one();

        Image::getImagine()->open($row['image'])
            ->thumbnail(new Box($arr['width'], $arr['height']))
            ->save($path, ['quality' => 90]);

        return $path;
    }

    /**
     * @param $url
     * @param $arr
     * @return false|string
     */
    public static function generateWatermarkedMiniature($url, $arr)
    {
        $path = \Yii::getAlias('@webroot/img-watermark.jpg'); // представим что это файл есть
        $watermark = \Yii::getAlias('@webroot/watermark.png');

        self::consiseImageHandler($url, $arr); // тут исключение

        $image = self::generateMiniature($url, $arr); // миниатюра
        Image::watermark($image, $watermark)->save($path); // наложение на миниатюру

        return $path;
    }
    
}
