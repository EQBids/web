<?php
namespace App\Repositories\Interfaces\Supplier;

use App\Models\Supplier\Supplier;
use App\Repositories\Interfaces\baseRepositoryInterface;

interface settingsRepositoryInterface extends baseRepositoryInterface{

	function getValue(Supplier $supplier,$name,$default=null);

	function getAll(Supplier $supplier);

}