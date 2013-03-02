<?php

class Testdata {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$stef = new Gebruiker();
		$stef->gebruikersnaam = "stef";
		$stef->wachtwoord = Hash::make("test");
		$stef->email = "fhp@full-hyperion.nl";
		$stef->naam = "Stef Louwers";
		$stef->admin = true;
		$stef->save();
		
		$bosuil = new Soort();
		$bosuil->naam = "Bosuil";
		$bosuil->save();
		
		$kiran = new Vogel();
		$kiran->naam = "Kiran";
		$kiran->geslacht = "tarsel";
		$bosuil->vogels()->insert($kiran);
		
		$gewicht = new Gewicht();
		$gewicht->gewicht = 750;
		$gewicht->datum = time();
		$kiran->gewichten()->insert($gewicht);
		
		$vogelVerslag = new Vogelverslag();
		$vogelVerslag->titel = "Test verslag";
		$vogelVerslag->tekst = "Bla die bla die bla.";
		$vogelVerslag->datum = time();
		$vogelVerslag->gebruiker_id = $stef->id;
		$kiran->verslagen()->insert($vogelVerslag);
		
		$bosuilInfo = new Soortinfo();
		$bosuilInfo->titel = "Bosuilen zijn cool";
		$bosuilInfo->tekst = "Omdat appel";
		$bosuil->info()->insert($bosuilInfo);
		
		$kiranInfo = new Vogelinfo();
		$kiranInfo->titel = "Kiran is cool";
		$kiranInfo->tekst = "Omdat hij van Nadine is.";
		$kiran->info()->insert($kiranInfo);
		
		$evaluatie = new Vliegevaluatie();
		$evaluatie->score = 10;
		$evaluatie->gebruiker_id = $stef->id;
		$kiran->vliegevaluaties()->insert($evaluatie);
		
		$dagverslag = new Dagverslag();
		$dagverslag->titel = "Vandaag was cool";
		$dagverslag->tekst = "Omdat er een super cool nieuw computer systeem is.";
		$dagverslag->datum = time();
		$stef->dagverslagen()->insert($dagverslag);
		
		$poep = new Taak();
		$poep->naam = "Poepscheppen";
		$poep->beschrijving = "Alle poep weghalen.";
		$poep->save();
		
		$geschept = new Taakuitvoering();
		$geschept->datum = time();
		$geschept->gebruiker_id = $stef->id;
		$poep->uitvoeringen()->insert($geschept);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}