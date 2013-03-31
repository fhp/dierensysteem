<?php

class Gebruikernonactief {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("gebruikers", function($table)
		{
			$table->boolean("nonactief");
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gebruikers', function($table)
		{
			$table->drop_column('nonactief');
		});
	}

}