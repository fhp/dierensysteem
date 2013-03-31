<?php

class Vogelseigenaar {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("vogels", function($table)
		{
			$table->integer("eigenaar_id")->unsigned()->nullable();
			$table->foreign("eigenaar_id")->references("id")->on("gebruikers");
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vogels', function($table)
		{
			$table->drop_foreign("vogels_eigenaar_id_foreign");
			$table->drop_column('eigenaar_id');
		});
	}

}