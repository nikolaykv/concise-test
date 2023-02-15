<?php

namespace app\commands;

use Yii;
use yii\db\Exception;
use yii\console\ExitCode;
use yii\console\Controller;
use Faker\Factory as Faker;
use yii\helpers\BaseConsole;


/**
 * Это консольный контроллер обеспечивает выполнение консольных команд, в рамках тестового задания компании от Concise
 *
 * @author Николай Квасов <kvasov1992007@gmail.com>
 * @since 2.0
 */
class ConciseController extends Controller
{
    /**
     * Это действие сгенериует 5 связанных записей в БД product.id=store_product.product_id
     * @return int Exit code
     */
    public function actionFaker()
    {

        $images = [
            'https://avatars.mds.yandex.net/i?id=b2de2531286164e892bea5c762af88d1aed22be0-7006309-images-thumbs&n=13',
            'https://avatars.mds.yandex.net/i?id=119fb2fba51e249af75f7f6ba89b386e5a340976-8497407-images-thumbs&n=13',
            'https://avatars.mds.yandex.net/i?id=712e64a647bfdd36fd43791008de97a9b663fd34-5291580-images-thumbs&n=13',
            'https://avatars.mds.yandex.net/i?id=9eaa8c631df4a830f3a34674cb921a7e70e93caf-8474952-images-thumbs&n=13',
            'https://avatars.mds.yandex.net/i?id=723be8d29610aacc6187f2ddf14ec9b36b272195-6356076-images-thumbs&n=13'
        ];

        try {

            $db = Yii::$app->db;
            $faker = Faker::create();

            foreach ($images as $image) {
                $product = Yii::$app->db->createCommand()
                    ->insert(
                        'product', [
                        'image' => $image,
                        'is_deleted' => $faker->randomElement([0, 1])
                    ])->execute();

                if ($product) {

                    $store_product = $db->createCommand()->insert(
                        'store_product', [
                        'product_id' => $db->getLastInsertID(),
                        'product_image' => $image
                    ])->execute();

                    if (!$store_product) throw new Exception('Не удалось создать store_product!');

                } else {
                    throw new Exception('Не удалось создать product!');
                }
            }

        } catch (\Exception $exception) {
            $this->stdout($exception->getMessage() . "\n", BaseConsole::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout('Успешно' . "\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }
}