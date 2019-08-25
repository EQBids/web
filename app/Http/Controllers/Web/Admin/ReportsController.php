<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Requests\Admin\Reports\baseReportRequest;
use App\Models\Buyer\Contractor;
use App\Models\Geo\Country;
use App\Models\Geo\State;
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
			], [ 'country', 'state', 'city' ] ) );
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

			],  [ 'country', 'state', 'city' ] ) );
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

			], [ 'country', 'state', 'city' ] ) );
		}
	}

	public function quotes(baseReportRequest $request){
		$suppliers        = Supplier::orderBy( 'name' )->get();
		if($request->get("supplier_id") == ""){
			
			return view( 'web.admin.reports.quotes' )->with( array_merge( ['suppliers'  => $suppliers]));
		}

		$suppliers_invitations=DB::select(DB::raw(" 
									select  s.name , bo.bid_id, concat(u.first_name , ' ', u.last_name) as contractor, e.name as equipment, bo.price, oi.qty, (bo.price * oi.qty) as total_value, o.created_at as orderdate , if(b.status=1,'Active', if(b.status = 2, 'Canceled', 'Closed')) as status,  oi.deliv_date, oi.return_date  from orders o
									inner join order_supplier os on os.order_id = o.id
									inner join suppliers s on s.id = os.supplier_id
									inner join order_items oi on oi.order_id = o.id
									inner join equipments e on e.id = oi.equipment_id
									inner join bid_order_item bo on bo.order_item_id = oi.id
									inner join bids b on b.id = bo.bid_id
									inner join users u on u.id = o.user_id
									inner join contractors co on co.user_id = u.id
									where s.id = '".$request->get("supplier_id")."'
									and o.created_at <= '" . $request->get("to") . "'
									and o.created_at >= '" . $request->get("from") . "'
									group by bo.bid_id
									order by bo.bid_id
									
									"
										));
										
		return view( 'web.admin.reports.quotes' )->with( array_merge( [
			'suppliers'  => $suppliers,
			'items' => $suppliers_invitations,
			

		] ) );		 

	}

	public function quotesStatus(baseReportRequest $request){
		
		$suppliers        = Supplier::orderBy( 'name' )->get();
		
		if($request->get("supplier_id") == ""){
			
			return view( 'web.admin.reports.quotesStatus' )->with( array_merge( ['suppliers'  => $suppliers]));
		}

		$quotesReceived=DB::select(DB::raw(" 
						select count(*) as result from order_supplier s
						where  s.supplier_id = '".$request->get("supplier_id")."'
						"
							));

		$quotesReplied=DB::select(DB::raw(" 
						select count(*) as result from bids b
						where  b.supplier_id = '".$request->get("supplier_id")."'
						"
							));

		$quotesActive=DB::select(DB::raw(" 
						select count(*) as result from bids b
						where  b.supplier_id = '".$request->get("supplier_id")."'
							and b.status = 1"
							));
		$quotesCancel=DB::select(DB::raw(" 
						select count(*) as result from bids b
						where  b.supplier_id = '".$request->get("supplier_id")."'
							and b.status = 2"
							));	
		$quotesClosed=DB::select(DB::raw(" 
						select count(*) as result from bids b
						where  b.supplier_id = '".$request->get("supplier_id")."'
							and b.status = 3"
							));										
		return view( 'web.admin.reports.quotesStatus' )->with( array_merge( [
			'suppliers'  => $suppliers,
			'quotesReceived' => $quotesReceived,
			'quotesReplied' => $quotesReplied,
			'quotesActive' => $quotesActive,
			'quotesCancel' => $quotesCancel,
			'quotesClosed' => $quotesClosed
		] ) );		 

	}

	public function whoQuoted(baseReportRequest $request){
		$suppliers        = Supplier::orderBy( 'name' )->get();
		if($request->get("supplier_id") == ""){
			
			return view( 'web.admin.reports.whoQuoted' )->with( array_merge( ['suppliers'  => $suppliers]));
		}

		$items=DB::select(DB::raw(" 
						SELECT u.email , concat(u.first_name, u.last_name) as name, count(*)  as total FROM bids b 
						inner join orders o on o.id = b.order_id
						inner join users u on u.id = o.user_id
						where b.supplier_id = '".$request->get("supplier_id")."'
						group by o.user_id 
						"
							));
		return view( 'web.admin.reports.whoQuoted' )->with( array_merge( [
			'suppliers'  => $suppliers,
			'items' => $items
		] ) );	
	}

	public function topEquipmentRequests(baseReportRequest $request){
		$items=DB::select(DB::raw(" 
						select e.name, count(*) as total from order_items oit
						inner join equipments e on e.id = oit.equipment_id
						group by oit.equipment_id
						order by total desc limit 5
						"
							));
		return view( 'web.admin.reports.topEquipmentRequests' )->with( array_merge( [
			
			'items' => $items
		] ) );	
	}

	public function equipmentHistory(baseReportRequest $request){
		$equipments        = Equipment::orderBy( 'name' )->get();
		if($request->get("equipment_id") == ""){
			
			return view( 'web.admin.reports.equipmentHistory' )->with( array_merge( ['equipments'  => $equipments]));
		}

		$items=DB::select(DB::raw(" 
					select o.id, concat(u.first_name, u.last_name) as name, s.name as site, oi.qty,oi.deliv_date, oi.return_date, b.price as bid from equipments e
					inner join order_items oi on oi.equipment_id = e.id
					inner join orders o on o.id = oi.order_id 
					inner join users u on u.id = o.user_id
					inner join sites s on o.site_id = s.id
					left join bid_order_item b on b.order_item_id = oi.id
					where e.id =" . $request->get("equipment_id") 
							));
		return view( 'web.admin.reports.equipmentHistory' )->with( array_merge( [
			'equipments'  => $equipments,
			'items' => $items
		] ) );	
	}
}
