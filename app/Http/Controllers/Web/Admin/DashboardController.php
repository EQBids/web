<?php

namespace App\Http\Controllers\Web\Admin;

use App\Charts\ContractorsChart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

	public function index(){
		$contractors_chart = new ContractorsChart();
		$contractors_chart->dataset('Sample', 'line', [100, 65, 84, 45, 90]);
		return view('web.admin.dashboard.index')->with(compact('contractors_chart'));
	}

}
