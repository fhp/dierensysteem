<?php

class Aanwezigheid_Opmerking {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("aanwezigheid", function($table)
		{
			$table->text("opmerkingen")->nullable();
			$table->boolean("actief")->default(true);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('aanwezigheid', function($table)
		{
			$table->drop_column('opmerkingen');
			$table->drop_column('actief');
		});
	}

}