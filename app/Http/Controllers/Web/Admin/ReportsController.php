<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Requests\Admin\Reports\baseReportRequest;
use App\Models\Buyer\Contractor;
use App\Models\Geo\Country;
use App\Models\Industry;
use App\Models\Product\Equipment;
use App\Models\Supplier\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade as PdfReport;
use App\Models\Geo\City;

class ReportsController extends Controller
{
	private $country;
	private $state;
	private $city;

	public function __construct(){
		$country=Country::query()->selectRaw('select name from countries');
		$state = State::query()->selectRaw('select name from states');
		$city = City::query()->selectRaw('select name from cities');
	}

	public function contractors(baseReportRequest $request){
		$industries = Industry::query()->orderBy('name')->get();
		$contractors=Contractor::query();
		
		
		if($request->exists('country_id') && $request->get('country_id')){
			
			$contractors->where('country_id',$request->get('country_id'));
			$country=Country::find($request->get('country_id'));
		}

		if($request->exists('state_id') && $request->get('state_id')){
			$contractors->where('state_id',$request->get('state_id'));
			$state=Country::find($request->get('state_id'));
		}

		if($request->exists('city_id') && $request->get('city_id')){
			$contractors->where('city_id',$request->get('city_id'));
			$city=Country::find($request->get('city_id'));
		}
		if($request->exists('industry_id') && $request->get('industry_id')){
			$contractors->where('industry_id',$request->get('industry_id'));
		}

		$dates_range='';
		if ($request->exists('from') && $request->get('from')){
			$dates_range=sprintf(" date(c_orders.created_at) >= '%s' ",$request->get('from'));
		}

		if ($request->exists('to') && $request->get('to')){
			if($dates_range){
				$dates_range.=' and ';
			}
			$dates_range.=sprintf(" date(c_orders.created_at) <= '%s' ",$request->get('to'));
		}


		$contractors->selectRaw('contractors.*, ifnull(c_orders.orders_count,0) orders_count')->leftJoin(DB::raw(
			"(select `sites`.`contractor_id`,count(*) as `orders_count` 
			from `orders`,`sites` 
			where `orders`.`site_id` = `sites`.`id` 
			group by `contractor_id` ) `c_orders`"
		),'c_orders.contractor_id','=','contractors.id');

		if($dates_range){
			$contractors->whereRaw($dates_range);
		}

		$contractors->withCount('users');

		if($request->exists('export') && $request->get('export')=='pdf'){


			$contractors->orderBy('contractors.company_name','asc');

			// Report title
			$title = 'Contractors Report';

			// For displaying filters description on header
			$meta = [];

			if($dates_range) {
				$meta['Registered on'] = $request->get( 'from' ) . ' To ' . $request->get( 'to' );
			}
			$columns = [
				'Name' => 'company_name',
				'Users' => 'users_count',
				'Orders' => 'orders_count',
			];
			return PdfReport::of($title, $meta, $contractors, $columns)
			                ->setCss([
				                '.head-content' => 'border-width: 1px',
			                ])
			                ->editColumn('Namre', [
				                'class' => 'bold'
			                ])
			                ->download('eqbids contractors report'); // or download('filename here..') to download pdf

		}else {

			$contractors = $contractors->get();


			return view( 'web.admin.reports.base' )->with( array_merge( [
				'title'       => 'Contractors Report',
				'form_action' => route( 'admin.reports.contractors' ),
				'columns'     => [
					[
						'label' => 'name',
						'field' => 'company_name'
					],
					[
						'label' => 'users',
						'field' => 'users_count'
					],
					[
						'label' => 'orders',
						'field' => 'orders_count'
					]
				],
				'industries'  => $industries,
				'items'       => $contractors
			], ['country' => $country ,'state' => $state , 'city' => $city ] ) );
		}
	}

	public function equipments(baseReportRequest $request){
		$industries = Industry::query()->orderBy('name')->get();

		$equipment_orders = DB::table('order_items')
			->join('orders','order_items.order_id','=','orders.id')
			->join('sites','sites.id','=','orders.site_id')
			->join('contractors','contractors.id','=','sites.contractor_id')
			->groupBy('order_items.equipment_id')
			->selectRaw('order_items.equipment_id,count(*) as order_count');



		if($request->exists('country_id') && $request->get('country_id')){
			$equipment_orders->whereRaw('`contractors`.`country_id` =  '.$request->get('country_id'));
			$country=Country::find($request->get('country_id'));
		}

		if($request->exists('state_id') && $request->get('state_id')){
			$equipment_orders->whereRaw('`contractors`.`state_id` =  '.$request->get('state_id'));
			$state=Country::find($request->get('state_id'));
		}

		if($request->exists('city_id') && $request->get('city_id')){
			$equipment_orders->whereRaw('`contractors`.`city_id` =  '.$request->get('city_id'));
			$city=Country::find($request->get('city_id'));
		}
		if($request->exists('industry_id') && $request->get('industry_id')){
			$equipment_orders->whereRaw('`contractors`.`industry_id` =  '.$request->get('industry_id'));
		}

		$dates_range='';
		if ($request->exists('from') && $request->get('from')){
			$dates_range=sprintf(" date(orders.created_at) >= '%s' ",$request->get('from'));
		}

		if ($request->exists('to') && $request->get('to')){
			if($dates_range){
				$dates_range.=' and ';
			}
			$dates_range.=sprintf(" date(orders.created_at) <= '%s' ",$request->get('to'));
		}

		if($dates_range){
			$equipment_orders->whereRaw($dates_range);
		}

		//dd($equipment_orders->toSql(),$equipment_orders->getBindings());
		$equipment_orders=$equipment_orders->toSql();



		$equipments=Equipment::query();
		$equipments->leftJoin('category_equipment','equipments.id','=','category_equipment.equipment_id')
		           ->join('categories','category_equipment.category_id','=','categories.id')
		           ->leftJoin(DB::raw('('.$equipment_orders.') as c_equipments'),'c_equipments.equipment_id','=','equipments.id');



		$equipments->selectRaw('equipments.*,categories.name as category_name, ifnull(c_equipments.order_count,0) as orders_count');

		if($request->exists('export') && $request->get('export')=='pdf'){


			$equipments->orderBy('c_equipments.order_count','desc');

			// Report title
			$title = 'Supplier Report';

			// For displaying filters description on header
			$meta = [];

			if($dates_range) {
				$meta['Registered on'] = $request->get( 'from' ) . ' To ' . $request->get( 'to' );
			}
				$columns = [
					'Name' => 'name',
					'Category' => 'category_name',
					'# of orders' => 'orders_count',
				];
				return PdfReport::of($title, $meta, $equipments, $columns)
				                ->setCss([
					                '.head-content' => 'border-width: 1px',
				                ])
				                ->editColumn('Namre', [
					                'class' => 'bold'
				                ])
				                ->download('eqbids equipments report'); // or download('filename here..') to download pdf



		}else {
			$equipments = $equipments->get();

			return view( 'web.admin.reports.base' )->with( array_merge( [
				'title'       => 'Equipments Report',
				'form_action' => route( 'admin.reports.equipments' ),
				'columns'     => [
					[
						'label' => 'name',
						'field' => 'name'
					],
					[
						'label' => 'category',
						'field' => 'category_name'
					],
					[
						'label' => '# of orders',
						'field' => 'orders_count'
					]
				],
				'industries'  => $industries,
				'items'       => $equipments,
				'sort'        => [
					[
						2,
						'desc'
					],
					[
						0,
						'asc'
					]
				],

			], ['country' => $country ,'state' => $state , 'city' => $city ] ) );
		}
	}

	public function suppliers(baseReportRequest $request){

		$suppliers = Supplier::query();
		$suppliers_invitations=DB::table('order_supplier')
		                         ->groupBy('order_supplier.supplier_id')
								 ->selectRaw('order_supplier.supplier_id,count(*) as invitations');

		$suppliers_bids=DB::table('bids')
		                         ->groupBy('bids.supplier_id')
		                         ->selectRaw('bids.supplier_id,count(*) as bids');

		$suppliers_leads=DB::table('order_supplier')
		                  ->join('order_items','order_items.order_id','=','order_supplier.order_id')
		                  ->groupBy('order_supplier.supplier_id')
		                  ->selectRaw('order_supplier.supplier_id,sum(order_items.qty) as leads');

		if($request->exists('country_id') && $request->get('country_id')){
			$suppliers->whereRaw('`suppliers`.`country_id` =  '.$request->get('country_id'));
			$country=Country::find($request->get('country_id'));
		}

		if($request->exists('state_id') && $request->get('state_id')){
			$suppliers->whereRaw('`suppliers`.`state_id` =  '.$request->get('state_id'));
			$state=Country::find($request->get('state_id'));
		}

		if($request->exists('city_id') && $request->get('city_id')){
			$suppliers->whereRaw('`suppliers`.`city_id` =  '.$request->get('city_id'));
			$city=Country::find($request->get('city_id'));
		}
		if($request->exists('equipment_id') && $request->get('equipment_id')){
			$suppliers_bids->whereExists(function ($exists_equipment) use($request){
				$exists_equipment->select(DB::raw(1))
					->from('bid_order_item')
					->join('order_items','bid_order_item.order_item_id','=','order_items.id')
					->whereRaw('order_items.equipment_id = '.$request->get('equipment_id'))
					->whereRaw('bid_order_item.bid_id = bids.id');

			});
			$suppliers_leads->whereRaw('order_items.equipment_id = '.$request->get('equipment_id'));
		}

		$bids_dates_range='';
		$bid_invitations_dates_range='';
		if ($request->exists('from') && $request->get('from')){
			$bids_dates_range=sprintf(" date(bids.created_at) >= '%s' ",$request->get('from'));
			$bid_invitations_dates_range=sprintf(" date(corders.created_at) >= '%s' ",$request->get('from'));
		}

		if ($request->exists('to') && $request->get('to')){
			if($bids_dates_range){
				$bids_dates_range.=' and ';
				$bid_invitations_dates_range.=' and ';
			}
			$bids_dates_range.=sprintf(" date(bids.created_at) <= '%s' ",$request->get('to'));
			$bid_invitations_dates_range.=sprintf(" date(corders.created_at) <= '%s' ",$request->get('to'));
		}


		if ($request->exists('supplier_id') && $request->get('supplier_id')){
			$suppliers_invitations->where('order_supplier.supplier_id',$request->get('supplier_id'));
			$suppliers_bids->where('bids.supplier_id',$request->get('supplier_id'));
			$suppliers->where('suppliers.id',$request->get('supplier_id'));
		}

		if($bids_dates_range){
			$suppliers_bids->whereRaw($bids_dates_range);
			$suppliers_invitations->whereExists(function ($exists_bids) use($bid_invitations_dates_range){
				$exists_bids->selectRaw(DB::raw(1))
					->from('orders as corders')
					->whereRaw($bid_invitations_dates_range)
					->whereRaw('corders.id = order_supplier.order_id');
			});
			$suppliers_leads->whereExists(function ($exists_leads) use($bid_invitations_dates_range){
				$exists_leads->selectRaw(DB::raw(1))
				            ->from('orders as corders')
				            ->whereRaw($bid_invitations_dates_range)
				            ->whereRaw('corders.id = order_supplier.order_id');
			});

		}

		$suppliers_invitations_sql=$suppliers_invitations->toSql();
		$suppliers_bids_sql=$suppliers_bids->toSql();
		$suppliers_leads=$suppliers_leads->toSql();

		$suppliers->leftJoin(DB::raw('('.$suppliers_invitations_sql.') as c_invitations'),'c_invitations.supplier_id','=','suppliers.id');
		$suppliers->leftJoin(DB::raw('('.$suppliers_bids_sql.') as c_bids'),'c_bids.supplier_id','=','suppliers.id');
		$suppliers->leftJoin(DB::raw('('.$suppliers_leads.') as c_leads'),'c_leads.supplier_id','=','suppliers.id');
		$suppliers->selectRaw('suppliers.name,ifnull(c_invitations.invitations,0) as invitations
		,ifnull(c_bids.bids,0) as bids,ifnull(c_leads.leads,0) as leads');

		$bindings = array_merge($suppliers_invitations->getBindings(),$suppliers_bids->getBindings(),$suppliers->getBindings());
		$suppliers->setBindings($bindings);
		if($request->exists('export') && $request->get('export')=='pdf'){

			$suppliers->orderBy('c_invitations.invitations','desc');

			// Report title
			$title = 'Supplier Report';

			// For displaying filters description on header
			$meta = [];

			if($bids_dates_range) {
				$meta['Registered on'] = $request->get( 'from' ) . ' To ' . $request->get( 'to' );
			}
				$columns = [
					'Name' => 'name',
					'Bid invitations' => 'invitations',
					'Total Bids' => 'bids',
					'Leads' => 'leads'
				];

				return PdfReport::of($title, $meta, $suppliers, $columns)
								->setCss([
									'.head-content' => 'border-width: 1px',
								])
				                ->editColumn('Namre', [
					                'class' => 'bold'
				                ])
				                ->download('eqbids suppliers report'); // or download('filename here..') to download pdf



		}else {
			$suppliers_result = $suppliers->get();
			$suppliers        = Supplier::orderBy( 'name' )->get();

			return view( 'web.admin.reports.suppliers' )->with( array_merge( [
				'items'      => $suppliers_result,
				'suppliers'  => $suppliers,
				'equipments' => Equipment::orderBy( 'name' )->get(),
				'sort'       => [
					[
						2,
						'desc'
					],
					[
						0,
						'asc'
					]
				],

			], ['country' => $country ,'state' => $state , 'city' => $city ] ) );
		}
	}

}
