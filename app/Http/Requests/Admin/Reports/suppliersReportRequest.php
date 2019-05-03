<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 7/25/18
 * Time: 7:56 PM
 */

namespace App\Http\Requests\Admin\Reports;


class suppliersReportRequest extends baseReportRequest {

	public function rules() {
		$rules = parent::rules();
		$rules['equipment_id']='nullable|integer|exists:equipments,id';
		$rules['supplier_id']='nullable|integer|exists:suppliers,id';
		return $rules;
	}

}