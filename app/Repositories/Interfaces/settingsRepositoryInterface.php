<?php
namespace App\Repositories\Interfaces;

interface settingsRepositoryInterface extends baseRepositoryInterface{



	public function getValue($key,$default=null);
}