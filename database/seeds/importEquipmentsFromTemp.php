<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Storage;

class importEquipmentsFromTemp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//clean up
	    DB::table('category_equipment')->delete();
	    \App\Models\Product\Equipment::query()->forceDelete();

	    \App\Models\Product\Category::query()->update(['parent_id'=>null]);
	    \App\Models\Product\Category::query()->forceDelete();

	    \App\Models\Product\Brand::query()->forceDelete();

    	$categories_map=[];
    	$manufacturers_map=[];
    	$equipments_map=[];
    	//import categories
        $tmp_categories = DB::connection('tmp')->table('oc_category')->orderby('category_id')->get();
        foreach ($tmp_categories as $tmp_category){
        	$tmp_category_detail = DB::connection('tmp')->table('oc_category_description')
	                                                    ->where('category_id',$tmp_category->category_id)
	                                                    ->first();
        	$category = \App\Models\Product\Category::create([
        		'name'=>$tmp_category_detail->name,
		        'parent_id'=>$tmp_category->parent_id?$categories_map[$tmp_category->parent_id]:null,
		        'status'=>0,
		        'details'=>[
			        'image'=>Storage::url($tmp_category->image),
			        'description'=>$tmp_category_detail->description
		        ]

	        ]);

        	$categories_map[$tmp_category->category_id]=$category->id;
        }

        $tmp_manufacturers = DB::connection('tmp')->table('oc_manufacturer')->get();
        foreach ($tmp_manufacturers as $tmp_manufacturer){
			$brand = \App\Models\Product\Brand::create([
				'name'=>$tmp_manufacturer->name,
				'status'=>'0',
				'details'=>[
					'image'=>Storage::url($tmp_manufacturer->image)
				]
			]);
	        $manufacturers_map[$tmp_manufacturer->manufacturer_id]=$brand->id;
        }

	    $tmp_products = DB::connection('tmp')->table('oc_product')->get();
        foreach ($tmp_products as $tmp_product){
        	$tmp_product_details= DB::connection('tmp')->table('oc_product_description')
	                                ->where('product_id',$tmp_product->product_id)
	                                ->first();
        	$equipment = \App\Models\Product\Equipment::create([
        		'name'=>$tmp_product_details->name,
		        'status'=>'0',
		        'brand_id'=>$tmp_product->manufacturer_id?$manufacturers_map[$tmp_product->manufacturer_id]:null,
		        'details'=>[
					'model'=>$tmp_product->model,
			        'image'=>Storage::url($tmp_product->image),
			        'price'=>$tmp_product->price,
			        'description'=>$tmp_product_details->description,
		        ]
	        ]);

        	$equipments_map[$tmp_product->product_id]=$equipment->id;
        }

        $tmp_product_categories=DB::connection('tmp')->table('oc_product_to_category')->get();
        foreach ($tmp_product_categories as $tmp_product_category){
        	DB::table('category_equipment')->insert([
        		'category_id'=>$categories_map[$tmp_product_category->category_id],
		        'equipment_id'=>$equipments_map[$tmp_product_category->product_id]
	        ]);
        }




    }
}
