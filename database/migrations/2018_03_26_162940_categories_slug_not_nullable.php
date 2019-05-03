<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Product\Category;

class CategoriesSlugNotNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = Category::all();
        foreach ($categories as $category){
        	$category->slug=$category->id.' '.$category->name; //the model is configured to slug the variable
	        $category->save();
        }
        Schema::table('categories',function (Blueprint $table){
	        $table->string('slug',50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('categories',function (Blueprint $table){
		    $table->string('slug',50)->nullable()->change();
	    });
    }
}
