<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Industries\industryRequest;
use App\Models\Industry;
use App\Repositories\Eloquent\Buyer\industryRepository;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
	var $industries_repository;

	public function __construct(industryRepository $industry_repository) {
		$this->industries_repository=$industry_repository;

	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

	    $industries = $this->industries_repository->with('parent')->findAll();
    	return view('web.admin.industries.index')->with(compact('industries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    $industries = $this->industries_repository->findAll();
        return view('web.admin.industries.create')->with(compact('industries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(industryRequest $request)
    {
        $data = $request->only(['name','parent']);
        $data['parent_id']=$data['parent'];
        try{
        	$this->industries_repository->create($data);
	        return redirect(route('admin.industries.index'))->with('notifications',collect([
		        [
			        'text'=>__("industry created successfully"),
			        'type'=>'success',
			        'wait'=>10,
		        ]
	        ]));
        }catch(\Error $error){
	        return redirect()->back()->with('notifications',collect([
		        [
			        'text'=>__("Something went wrong please try again"),
			        'type'=>'error'
		        ]
	        ]));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function edit(Industry $industry)
    {
	    $industries = $this->industries_repository->findAll();
        return view('web.admin.industries.edit')->with(compact('industry','industries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Industry $industry)
    {
	    $data = $request->only(['name','parent']);
	    $data['parent_id']=$data['parent'];
	    try{
		    $this->industries_repository->updateBy($data,$industry->id);
		    return redirect(route('admin.industries.index'))->with('notifications',collect([
			    [
				    'text'=>__("industry successfully updated"),
				    'type'=>'success',
				    'wait'=>10,
			    ]
		    ]));
	    }catch(\Error $error){
		    return redirect()->back()->with('notifications',collect([
			    [
				    'text'=>__("Something went wrong please try again"),
				    'type'=>'error'
			    ]
		    ]));
	    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Industry $industry)
    {
	    $this->industries_repository->delete($industry->id);

	    return redirect()->route('admin.industries.index')->with('notifications',collect([
		    [
			    'text'=>__("The industry was successfully deleted"),
			    'type'=>'success',
			    'wait'=>10,
		    ]
	    ]));
    }

	public function delete(Industry $industry){
    	return view('web.admin.industries.delete',compact('industry'));
	}
}
