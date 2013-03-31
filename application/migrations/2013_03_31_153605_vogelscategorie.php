<?php

class Vogelscategorie {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("categorieen", function($table)
		{
			$table->increments("id");
			$table->string("naam");
			$table->boolean("in_overzicht");
			$table->integer("order");
			$table->timestamps();
			
			$table->unique('naam');
			
			$table->engine = "InnoDB";
		});
		
		Schema::table("vogels", function($table)
		{
			$table->integer("categorie_id")->unsigned();
			$table->date("overleidensdatum")->nullable();
		});
		
		$categorie = new Categorie();
		$categorie->naam = "Vliegvogels";
		$categorie->in_overzicht = true;
		$categorie->order = 1;
		$categorie->save();
		
		DB::table('vogels')->update(array('categorie_id' => $categorie->id));
		
		Schema::table("vogels", function($table)
		{
			$table->foreign("categorie_id")->references("id")->on("categorieen");
		});
		
		$categorie = new Categorie();
		$categorie->naam = "Voliere vogels";
		$categorie->in_overzicht = true;
		$categorie->order = 2;
		$categorie->save();
		
		$categorie = new Categorie();
		$categorie->naam = "Overleden vogels";
		$categorie->in_overzicht = false;
		$categorie->order = 3;
		$categorie->save();
		
		$categorie = new Categorie();
		$categorie->naam = "Verhuisde vogels";
		$categorie->in_overzicht = false;
		$categorie->order = 4;
		$categorie->save();
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
			$table->drop_foreign("vogels_categorie_id_foreign");
			$table->drop_column('categorie_id');
		});
		
		Schema::drop("categorieen");
	}

}