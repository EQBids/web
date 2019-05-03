<?php

namespace App\Http\Requests\Buyer\Office;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AddWorkerToOfficeRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        If the current user is a contractor-superadmin, he can add any other contractor
        if(Auth::user()->hasRole('contractor-superadmin')){
            return true;
        }
        /**
         * Otherwise, contractor-managers can only add contractor-workers, and contractor-admins can only add
         *[contractor-managers, contractor-workers]
         */
        else{

            $creatorRole = Auth::user()->rols()->first();

            $worker = User::with('rols')->find($this->request->get('eligible_worker'));
            $workerRole = $worker->rols()->first();

            $allowed = false;
            switch (strtolower($creatorRole->name)){

                case 'contractor-admin':{

                    if($workerRole->name == 'contractor-worker' || $workerRole->name == 'contractor-manager')
                        $allowed = true;
                    break;
                }
                case 'contractor-manager':{

                    if($workerRole->name == 'contractor-worker')
                        $allowed = true;
                    break;
                }

                default:{
                    $allowed = false;
                    break;
                }
            }

            return $allowed;
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'eligible_worker'   =>  'required|integer',
        ];
    }
}
