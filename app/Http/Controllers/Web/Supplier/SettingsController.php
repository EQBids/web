<?php

namespace App\Http\Controllers\Web\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\Settings\SupplierSettingRequest;
use App\Repositories\Interfaces\Supplier\settingsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected $settingsRepo;
    public function __construct(settingsRepositoryInterface $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }

    public function index(){

        $supplier = Auth::user()->suppliers()->first();
        if (!$supplier){
            die();
        }
        $settings = $this->settingsRepo->getAll($supplier);
        return view('web.supplier.settings.index',compact('settings'));
    }

    public function create(){

        return view('web.supplier.settings.create');
    }


    public function store(SupplierSettingRequest $request){
    	return url();
    }

    public function edit($id){

        $setting = $this->settingsRepo->findOneBy($id);
        return view('web.supplier.settings.edit',compact('setting'));
    }

    public function update(SupplierSettingRequest $request,$id){

        $data = [
            'value'     =>  $request->get('value'),
        ];
        $this->settingsRepo->updateBy($data,$id);

        return redirect()->route('supplier.settings.index')->with('notifications',collect([
            [
                'text'=>__("The setting was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function delete($id){

        $setting = $this->settingsRepo->findOneBy($id);
        return view('web.supplier.settings.delete',compact('setting'));
    }

    public function destroy($id){

        $this->settingsRepo->delete($id);

        return redirect()->route('supplier.settings.index')->with('notifications',collect([
            [
                'text'=>__("The setting was deleted successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }
}

