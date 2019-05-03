<?php
namespace App\Traits;


use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

trait Source{

    public function rememberSource($source,$ref = null){

        Session::forget('source');

        Session::put('source',$source);

        if($ref){

            Request::put('ref',$ref);
        }

    }

    public function redirectToSource(){


        if(Session::has('source')){

            $source = Session::pull('source');
            $ref = Session::pull('ref');
            return redirect()->to($source)->with('ref',$ref);
        }

        return null;
    }
}