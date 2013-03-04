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
	
	public function gewichten()
	{
		return $this->has_many('Gewicht');
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
			$interval = $birthday->diff(new DateTime);
			return $interval->y;
		} else {
			return "Onbekend";
		}
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
