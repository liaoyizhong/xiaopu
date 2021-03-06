<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ChangeUsers extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->dropTable('UsersModel');

        $table = $this->table('UsersModel');
        $table->addColumn('nick_name','string',['limit'=>200])
            ->addColumn('password','text')
            ->addColumn('createtime', 'datetime')
            ->addColumn('updatetime','datetime',['default'=>'CURRENT_TIMESTAMP'])
            ->addColumn('phone','string',['limit'=>20])
            ->addIndex(['phone'],['unique'=>true])
            ->create();
    }
}
