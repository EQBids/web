<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/21/18
 * Time: 9:05 PM
 */

namespace App\Http\Controllers\Web\Supplier;


use App\Http\Controllers\Controller;
use App\Models\Product\Equipment;
use App\Repositories\Eloquent\Product\CategoryRepository;
use App\Repositories\Eloquent\Product\EquipmentRepository;
use App\Repositories\Eloquent\SettingsRepository;
use Zend\Diactoros\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentsController extends Controller {


	protected $equipment_repository,$settings_repositoy,$category_repository;
	public function __construct(EquipmentRepository $equipment,
		SettingsRepository $settings_repository,
		CategoryRepository $category_repository
	)
	{
		$this->equipment_repository = $equipment;
		$this->settings_repositoy=$settings_repository;
		$this->category_repository=$category_repository;
	}


}