<?php

class Takenactief {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("taken", function($table)
		{
			$table->boolean("actief");
		});
		DB::table('taken')->update(array("actief"=>1));
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('taken', function($table)
		{
			$table->drop_column('actief');
		});
	}

}