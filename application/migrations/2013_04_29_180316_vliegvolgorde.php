<?php

class Vliegvolgorde {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("vliegvolgordelijsten", function($table)
		{
			$table->increments("id");
			$table->string("naam");
			$table->integer("volgorde");
			$table->timestamps();
			
			$table->engine = "InnoDB";
		});
		
		Schema::create("vliegvolgorde", function($table)
		{
			$table->increments("id");
			$table->integer("vliegvolgordelijst_id")->unsigned();
			$table->integer("vogel_id")->unsigned();
			$table->string("opmerkingen")->nullable();
			$table->integer("volgorde");
			$table->timestamps();
			
			$table->foreign("vliegvolgordelijst_id")->references("id")->on("vliegvolgordelijsten");
			$table->foreign("vogel_id")->references("id")->on("vogels");
			
			$table->engine = "InnoDB";
		});
		
		$v = new Vliegvolgordelijst();
		$v->naam = "Demo 1";
		$v->volgorde = 1;
		$v->save();
		
		$v = new Vliegvolgordelijst();
		$v->naam = "Demo 2";
		$v->volgorde = 2;
		$v->save();
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("vliegvolgorde");
		Schema::drop("vliegvolgordelijsten");
	}

}