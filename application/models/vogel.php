<?php

class Vogel extends Eloquent {
	public static $table = "vogels";
	public static $timestamps = true;
	
	public static $thumbnailable = array(
		'default_field' => 'foto',
		'fields' => array(
			'foto' => array(
				'default_size' => 'small',
				'sizes' => array(
					'xsmall'  => '32x32',
					'small'  => '64x64',
					'medium' => '128x128',
					'large'  => '256x256',
				)
			)
		),
		"default_image" => "default_vogel.png"
	);
	
	public function soort()
	{
		return $this->belongs_to('Soort');
	}
	
	public function categorie()
	{
		return $this->belongs_to('Categorie');
	}
	
	public function vliegvolgorde()
	{
		return $this->has_many_and_belongs_to("Vliegvolgordelijst", "vliegvolgorde")->with('opmerkingen', 'volgorde');
	}
	
	public function eigenaar()
	{
		return $this->belongs_to('Gebruiker');
	}
	
	public function gewichten()
	{
		return $this->has_many('Gewicht');
	}
	
	public function vogelEten()
	{
		return $this->has_many('Eten');
	}
	
	public function etenIngevuld($datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		return $this->vogelEten()->where_datum($datum)->count() != 0;
	}
	
	public function eten($datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		return $this->vogelEten()->where_datum($datum)->first();
	}
	
	public function vliegpermissies()
	{
		return $this->has_many_and_belongs_to("Gebruiker", "vliegpermissies")->with('opmerkingen');
	}
	
	public function vliegpermissie($gebruiker_id)
	{
		return DB::table('vliegpermissies')->where_vogel_id_and_gebruiker_id($this->id, $gebruiker_id)->count() == 1;
	}
	
	public function vliegpermissieOpmerkingen($gebruiker_id)
	{
		return DB::table('vliegpermissies')->where_vogel_id_and_gebruiker_id($this->id, $gebruiker_id)->only("opmerkingen");
	}
	
	public function gelezendoor()
	{
		return $this->has_many_and_belongs_to("Gebruiker", "vogelgelezen");
	}
	
	public function isGelezen($gebruiker_id = null)
	{
		if($gebruiker_id == null) {
			$gebruiker_id = Auth::user()->id;
		}
		return DB::table('vogelgelezen')->where_vogel_id_and_gebruiker_id($this->id, $gebruiker_id)->count() == 1;
	}
	
	public function gewicht($datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		$gewicht = Gewicht::where_datum_and_vogel_id($datum, $this->id)->first();
		if($gewicht === null) {
			return null;
		} else {
			return $gewicht->gewicht;
		}
	}
	
	public function braakbal($datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		$gewicht = Gewicht::where_datum_and_vogel_id($datum, $this->id)->first();
		if($gewicht === null) {
			return null;
		} else {
			return $gewicht->braakbal;
		}
	}
	
	public function set_gewicht($gewicht, $datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		$g = Gewicht::where_datum_and_vogel_id($datum, $this->id)->first();
		if($g === null) {
			$g = new Gewicht();
			$g->gewicht = $gewicht;
			$g->braakbal = null;
			$g->datum = $datum;
			$this->gewichten()->insert($g);
		} else {
			$g->gewicht = $gewicht;
			$g->save();
		}
	}
	
	public function set_braakbal($braakbal = true, $datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		$g = Gewicht::where_datum_and_vogel_id($datum, $this->id)->first();
		if($g === null) {
			$g = new Gewicht();
			$g->gewicht = null;
			$g->braakbal = $braakbal;
			$g->datum = $datum;
			$this->gewichten()->insert($g);
		} else {
			$g->braakbal = $braakbal;
			$g->save();
		}
	}
	
	public function verslagen()
	{
		return $this->has_many('Vogelverslag');
	}
	
	public function vliegevaluaties()
	{
		return $this->has_many('Vliegevaluatie');
	}
	
	public function get_leeftijd()
	{
		if($this->geboortedatum !== null) {
			$birthday = new DateTime($this->geboortedatum);
			if($this->overleidensdatum === null) {
				$eindDatum = new DateTime("today");
			} else {
				$eindDatum = new DateTime($this->overleidensdatum);
			}
			$interval = $birthday->diff($eindDatum);
			return $interval->y;
		} else {
			return "Onbekend";
		}
	}
	
	public function geschreven($datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		return Vogelverslag::where_datum_and_vogel_id($datum, $this->id)->count() > 0;
	}
	
	public function thumbnail( $field=null, $size=null )
	{
		return Thumbnailer::get( $this, $field, $size );
	}
	
	public function thumbnail_path( $field=null, $size=null )
	{
		return Thumbnailer::get_path( $this, $field, $size );
	}
	
	public function thumbnail_url( $field=null, $size=null )
	{
		return Thumbnailer::get_url( $this, $field, $size );
	}

	public function thumbnail_image( $field=null, $size=null, $alt=null, $attributes=array() )
	{
		return HTML::image( Thumbnailer::get_url( $this, $field, $size ), $alt, $attributes );
	}
}
