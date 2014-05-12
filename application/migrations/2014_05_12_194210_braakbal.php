<?php

class Braakbal {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("gewichten", function($table)
		{
			$table->boolean("braakbal")->default(null)->nullable();
		});
		
		DB::query('ALTER TABLE `gewichten` MODIFY `gewicht` int(11) NULL;');
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("gewichten", function($table)
		{
			$table->drop_column("braakbal");
		});
		
		DB::query('ALTER TABLE `gewichten` MODIFY `gewicht` int(11) NOT NULL;');
	}

}