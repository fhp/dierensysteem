<?php

class Soortenengelsenaam {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('soorten', function($table)
		{
			$table->string('engelsenaam');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('soorten', function($table)
		{
			$table->drop_column('engelsenaam');
		});
	}

}