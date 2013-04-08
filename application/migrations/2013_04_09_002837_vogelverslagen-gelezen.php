<?php

class Vogelverslagen_Gelezen {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("vogelgelezen", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->integer("vogel_id")->unsigned();
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			$table->foreign("vogel_id")->references("id")->on("vogels");
			
			$table->engine = "InnoDB";
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("vogelgelezen");
	}

}