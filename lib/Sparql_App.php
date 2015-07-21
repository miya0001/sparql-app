<?php

abstract class Sparql_App
{
	protected $name;

	public function get_name()
	{
		if ( $this->name ) {
			return $this->name;
		}
	}

	// abstract public function load_script()
	// {
	//
	// }
}
