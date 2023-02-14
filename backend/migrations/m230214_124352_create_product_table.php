<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m230214_124352_create_product_table extends Migration
{
    public function up()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'image' => $this->string(),
            'is_deleted' => $this->boolean()->notNull()->defaultValue(false)
        ]);
    }

    public function down()
    {
        $this->dropTable('product');
    }
}
