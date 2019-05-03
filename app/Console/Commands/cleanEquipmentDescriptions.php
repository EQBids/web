<?php

namespace App\Console\Commands;

use App\Models\Product\Equipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class cleanEquipmentDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:flush_equipment_descriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty the descriptions and excerpt of all the equipments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $equipments = Equipment::all();
	    $bar = $this->output->createProgressBar(count($equipments));
    	DB::transaction(function () use($equipments,$bar){

		    foreach ($equipments as $equipment){
		    	$details = $equipment->details;
		    	$details['description']="";
		    	$details['excerpt']="";
		    	$equipment->details=$details;
		    	$equipment->save();
		    	$bar->advance();
		    }
	    });
    	$bar->finish();
    	$this->info("equipment descriptions cleaned");


    }
}
