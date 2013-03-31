<?php

class Gebruiker extends Eloquent {
	public static $table = "gebruikers";
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
		"default_image" => "default_gebruiker.png"
	);
	
	public function gewichten()
	{
		return $this->has_many('Gewicht');
	}

	public function aanwezigheden()
	{
		return $this->has_many('Aanwezigheid');
	}
	
	public function aanwezigheid($datum)
	{
		return Aanwezigheid::where_gebruiker_id_and_datum($this->id, $datum)->first();
	}
	
	public function isAanwezig($datum)
	{
		return Aanwezigheid::where_gebruiker_id_and_datum($this->id, $datum)->count() >= 1;
	}

	public function taakuitvoeringen()
	{
		return $this->has_many('Taakuitvoering');
	}

	public function dagverslagen()
	{
		return $this->has_many('Dagverslag');
	}

	public function vliegevaluaties()
	{
		return $this->has_many('Vliegevaluatie');
	}

	public function vogelverslagen()
	{
		return $this->has_many('Vogelverslag');
	}
	
	public function vogels()
	{
		return $this->has_many('Vogel', 'eigenaar_id');
	}
	
	public function vliegpermissies()
	{
		return $this->has_many_and_belongs_to("Vogel", "vliegpermissies");
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
