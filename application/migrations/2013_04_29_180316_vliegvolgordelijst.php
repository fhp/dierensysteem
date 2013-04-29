<?php

class Vliegvolgordelijst {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("vliegvolgorde", function($table)
		{
			$table->increments("id");
			$table->string("naam");
			$table->integer("order");
			$table->timestamps();
			
			$table->unique('naam');
			
			$table->engine = "InnoDB";
		});
		
		Schema::table("vogels", function($table)
		{
			$table->integer("lijst_id")->unsigned()->nullable();
			$table->integer("lijst_volgorde")->nullable();
			$table->foreign("lijst_id")->references("id")->on("vliegvolgorde");
		});
		
		$v = new Vliegvolgorde();
		$v->naam = "Demo 1";
		$v->order = 1;
		$v->save();
		
		$v = new Vliegvolgorde();
		$v->naam = "Demo 2";
		$v->order = 2;
		$v->save();
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
			$table->drop_foreign("vogels_lijst_id_foreign");
			$table->drop_column('lijst_id');
			$table->drop_column('lijst_volgorde');
		});
		
		Schema::drop("vliegvolgorde");
	}

}