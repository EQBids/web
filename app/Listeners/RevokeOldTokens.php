<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Passport\Token;

class RevokeOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
    	$userid=$event->userId;
        $tokenid=$event->tokenId;
        if($userid==null){
	        $ip=app('request')->ip();
	        $secure_hosts=config('eqbids.system_allowed_hosts');
	        $secure_hosts=explode('|',$secure_hosts);
	        foreach ($secure_hosts as $host){
		        if (preg_match('/'.$host.'/',$ip)){
			        Token::where('user_id',$userid)->where('id','!=',$tokenid)->update(['revoked'=>1]);
			        return;
		        }
	        }
	        Token::where('user_id',$userid)->where('id',$tokenid)->update(['revoked'=>1]); //blocks unsecure tokens
        }else{

        }
	    Token::where('user_id',$userid)->where('id','!=',$tokenid)->update(['revoked'=>1]);

    }
}
