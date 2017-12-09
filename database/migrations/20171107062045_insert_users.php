<?php

use think\migration\Migrator;
use think\migration\db\Column;

class InsertUsers extends Migrator
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
        $table = $this->table("users");

        $passwordToken = \app\users\service\UsersService::TOKENPRE;
        $password = "xiaoyujia";
        $mdPassword = md5($passwordToken.$password);
        $table->insert(["user_id"=>1,"nick_name"=>"admin","password"=>$mdPassword,"phone"=>"18888888888","createtime"=>date('H-m-d H:i:s')])->save();
    }
}
