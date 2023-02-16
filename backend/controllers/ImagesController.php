<?php

namespace app\controllers;

use yii\db\Query;
use Imagine\Image\Box;
use yii\imagine\Image;
use app\models\Product;
use yii\web\Controller;

class ImagesController extends Controller
{

    public static $join = true;
    public static $common_behavior = true;

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'products' => Product::find()->asArray()->all()
        ]);
    }

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


    public static function generateMiniature($url, $arr)
    {
        $result = [];

        $where = ['is_deleted' => '0'];

        if (self::$common_behavior) {
            self::consiseImageHandler($url, $arr);
            $where[] = ['image' => $url];
        }

        $query = (new Query());

        if (self::$join) {

            $where[] = ['product_image' => $url];
            $rows = $query
                ->select('product.image, store_product.product_image')
                ->from('product')
                ->innerJoin('store_product', 'product.id = store_product.product_id')
                ->where($where)
                ->all();
        } else {
            $rows = $query
                ->select('image')
                ->from('product')
                ->where($where)
                ->all();
        }

        foreach ($rows as $key => $row) {

            $path = \Yii::getAlias('@webroot/img.jpg') . "_" . $key;

            Image::getImagine()->open($row['image'])
                ->thumbnail(new Box($arr['width'], $arr['height']))
                ->save($path, ['quality' => 90]);

            $result[] = $path;
        }

        return $result;
    }


    public static function generateWatermarkedMiniature($url, $arr)
    {
        $result = [];
        $watermark = \Yii::getAlias('@webroot/watermark.png');

        if (self::$common_behavior) {
            self::consiseImageHandler($url, $arr);
        }

        $images = self::generateMiniature($url, $arr);

        foreach ($images as $image) {

            $path = \Yii::getAlias('@webroot/img-watermark.jpg');

            Image::watermark($image, $watermark)->save($path);
            $result[] = $path;

        }

        return $result;
    }


}
