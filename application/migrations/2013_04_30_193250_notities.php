<?php

class Notities {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("notities", function($table)
		{
			$table->increments("id");
			$table->text("tekst");
			$table->timestamps();
			
			$table->engine = "InnoDB";
		});
		
		$notitie = new Notitie();
		$notitie->tekst = "";
		$notitie->save();
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("notities");
	}

}