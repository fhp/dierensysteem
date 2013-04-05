<?php

class Vliegpermissie_Opmerking {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("vliegpermissies", function($table)
		{
			$table->text("opmerkingen")->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vliegpermissies', function($table)
		{
			$table->drop_column('opmerkingen');
		});
	}

}