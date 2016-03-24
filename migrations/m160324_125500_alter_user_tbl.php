<?php

use app\components\db\Migration;

class m160324_125500_alter_user_tbl extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'hh', $this->string()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'hh');
    }
}
