<?php
use Migrations\AbstractMigration;

class CreateUrlDetailsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        //creating users table
        $table = $this->table('url_details');
        $table->addColumn('hash', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('original_url', 'text', [
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('expiration_date', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
