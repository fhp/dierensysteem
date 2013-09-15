<?php

class Belangrijke_Verslagen {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("vogelverslagen", function($table)
		{
			$table->boolean("belangrijk")->default(false);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("vogelverslagen", function($table)
		{
			$table->drop_column("belangrijk");
		});
	}

}