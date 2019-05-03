<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\SettingRequest;
use App\Repositories\Interfaces\settingsRepositoryInterface;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsRepo;
    public function __construct(settingsRepositoryInterface $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }

    public function index(){

        $settings = $this->settingsRepo->findAll();
        return view('web.admin.settings.index',compact('settings'));
    }

    public function create(){
        return view('web.admin.settings.create');
    }


    public function store(SettingRequest $request){

        $data = $request->except('_token');

        $this->settingsRepo->create($data);

        return redirect()->route('admin.settings.index')->with('notifications',collect([
            [
                'text'=>__("The setting was created successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function edit($id){

        $setting = $this->settingsRepo->findOneBy($id);
        return view('web.admin.settings.edit',compact('setting'));
    }

    public function update(SettingRequest $request,$id){

        $data = $request->except('_token');

        $this->settingsRepo->updateBy($data,$id);

        return redirect()->back()->with('notifications',collect([
            [
                'text'=>__("The setting was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function delete($id){
        $setting = $this->settingsRepo->findOneBy($id);
        return view('web.admin.settings.delete',compact('setting'));
    }


    public function destroy($id){

        $this->settingsRepo->delete($id);

        return redirect()->route('admin.settings.index')->with('notifications',collect([
            [
                'text'=>__("The setting was deleted successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }
}
