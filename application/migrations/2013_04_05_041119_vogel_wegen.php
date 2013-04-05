<?php

class Vogel_Wegen {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("vogels", function($table)
		{
			$table->boolean("wegen");
		});
		DB::table('vogels')->update(array("wegen"=>0));
		DB::table('vogels')->where("categorie_id", "=", 1)->update(array("wegen"=>1));
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
			$table->drop_column('wegen');
		});
	}

}