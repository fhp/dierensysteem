<?php

class Eten {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("vogels", function($table)
		{
			$table->text("kuikens");
			$table->text("hamsters");
			$table->boolean("duif");
			$table->text("eten_opmerking");
		});
		
		Schema::create("vogeleten", function($table)
		{
			$table->increments("id");
			$table->integer("vogel_id")->unsigned();
			$table->integer("gebruiker_id")->unsigned();
			$table->date("datum");
			
			$table->text("kuikens");
			$table->text("hamsters");
			$table->boolean("duif");
			$table->text("opmerking");
			
			$table->timestamps();
			
			$table->foreign("vogel_id")->references("id")->on("vogels");
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			
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
		Schema::drop("vogeleten");
		
		Schema::table("vogels", function($table)
		{
			$table->drop_column("kuikens");
			$table->drop_column("hamsters");
			$table->drop_column("duif");
			$table->drop_column("eten_opmerking");
		});
	}

}