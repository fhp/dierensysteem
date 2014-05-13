<?php

class Vergaderingen {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("agendapunten", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			
			$table->string("titel");
			$table->text("omschrijving")->nullable();
			
			$table->boolean("voltooid");
			
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			
			$table->engine = "InnoDB";
		});
		
		Schema::create("notulen", function($table)
		{
			$table->increments("id");
			$table->integer("agendapunt_id")->unsigned();
			$table->integer("gebruiker_id")->unsigned();
			
			$table->text("omschrijving");
			
			$table->timestamps();
			
			$table->foreign("agendapunt_id")->references("id")->on("agendapunten");
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			
			$table->engine = "InnoDB";
		});
		
		Schema::create("actiepunten", function($table)
		{
			$table->increments("id");
			$table->integer("agendapunt_id")->unsigned();
			$table->integer("gebruiker_id")->unsigned();
			
			$table->string("titel");
			$table->text("omschrijving")->nullable();
			$table->date("deadline")->nullable();
			
			$table->boolean("voltooid");
			$table->text("opmerkingen")->nullable();
			
			$table->timestamps();
			
			$table->foreign("agendapunt_id")->references("id")->on("agendapunten");
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
		Schema::drop("notulen");
		Schema::drop("actiepunten");
		Schema::drop("agendapunten");
	}

}