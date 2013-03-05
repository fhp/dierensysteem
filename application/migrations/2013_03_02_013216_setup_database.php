<?php

class Setup_Database {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("gebruikers", function($table)
		{
			$table->increments("id");
			$table->string("gebruikersnaam");
			$table->string("wachtwoord");
			$table->string("email");
			$table->string("telefoon");
			$table->string("naam");
			$table->text("informatie");
			$table->boolean("admin");
			$table->string("foto")->nullable();
			$table->timestamps();
			
			$table->unique('gebruikersnaam');
			
			$table->engine = "InnoDB";
		});
		
		Schema::create("soorten", function($table)
		{
			$table->increments("id");
			$table->string("naam");
			$table->string("latijnsenaam")->nullable();
			$table->text("informatie");
			$table->timestamps();
			
			$table->engine = "InnoDB";
		});
		
		Schema::create("vogels", function($table)
		{
			$table->increments("id");
			$table->integer("soort_id")->unsigned();
			$table->string("naam");
			$table->string("geslacht")->nullable();
			$table->date("geboortedatum")->nullable();
			$table->text("informatie");
			$table->string("foto")->nullable();
			$table->timestamps();
			
			$table->foreign("soort_id")->references("id")->on("soorten");
			$table->engine = "InnoDB";
		});
		
		Schema::create("gewichten", function($table)
		{
			$table->increments("id");
			$table->integer("vogel_id")->unsigned();
			$table->integer("gewicht");
			$table->date("datum");
			$table->timestamps();
			
			$table->foreign("vogel_id")->references("id")->on("vogels");
			$table->engine = "InnoDB";
		});
		
		Schema::create("vogelverslagen", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->integer("vogel_id")->unsigned();
			$table->text("tekst");
			$table->date("datum");
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			$table->foreign("vogel_id")->references("id")->on("vogels");
			$table->engine = "InnoDB";
		});
		
		Schema::create("vliegevaluaties", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->integer("vogel_id")->unsigned();
			$table->integer("score");
			$table->date("datum");
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			$table->foreign("vogel_id")->references("id")->on("vogels");
			$table->engine = "InnoDB";
		});
		
		Schema::create("dagverslagen", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->text("tekst");
			$table->date("datum");
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			$table->engine = "InnoDB";
		});
		
		Schema::create("aanwezigheid", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->date("datum");
			$table->date("start")->nullable()->default(null);
			$table->date("einde")->nullable()->default(null);
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			$table->engine = "InnoDB";
		});
		
		Schema::create("taken", function($table)
		{
			$table->increments("id");
			$table->string("naam");
			$table->text("beschrijving");
			$table->integer("frequentie");
			$table->timestamps();
			
			$table->engine = "InnoDB";
		});
		
		Schema::create("taakuitvoeringen", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->integer("taak_id")->unsigned();
			$table->date("datum");
			$table->timestamps();
			
			$table->foreign("gebruiker_id")->references("id")->on("gebruikers");
			$table->foreign("taak_id")->references("id")->on("taken");
			$table->engine = "InnoDB";
		});
		
		Schema::create("mededelingen", function($table)
		{
			$table->increments("id");
			$table->integer("gebruiker_id")->unsigned();
			$table->string("titel");
			$table->text("tekst");
			$table->date("datum");
			$table->timestamps();
			
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
		Schema::drop("gewichten");
		Schema::drop("vogelverslagen");
		Schema::drop("vliegevaluaties");
		Schema::drop("dagverslagen");
		Schema::drop("aanwezigheid");
		Schema::drop("taakuitvoeringen");
		Schema::drop("taken");
		Schema::drop("mededelingen");
		Schema::drop("vogels");
		Schema::drop("soorten");
		Schema::drop("gebruikers");
	}

}