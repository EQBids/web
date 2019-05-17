<?php

namespace App\Providers;

use App\Guards\pinGuard;
use App\Guards\pinUserProvider;
use App\User;
use App\Extensions\eqBidsPinGrant;
use Illuminate\Auth\SessionGuard;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use League\OAuth2\Server\AuthorizationServer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


		//passport conf
		Passport::routes();
	    app(AuthorizationServer::class)->enableGrantType($this->makeEqbidsPinGrant(),Passport::tokensExpireIn());

	    //pin guard and provider

	    Auth::provider('pin-users', function ($app, array $config) {
		    // Return an instance of Illuminate\Contracts\Auth\UserProvider...

		    return new pinUserProvider($app['hash'], $config['model']);
	    });

	    Auth::extend('pin', function ($app, $name, array $config) {
		    $provider = Auth::createUserProvider($config['provider']);

//		    $provider = $this->createUserProvider($config['provider'] ?? null);
			
		    $guard = new SessionGuard('web', $provider, $app['session.store']);

		    // When using the remember me functionality of the authentication services we
		    // will need to be set the encryption instance of the guard, which allows
		    // secure, encrypted cookie values to get generated for those cookies.
		    if (method_exists($guard, 'setCookieJar')) {
			    $guard->setCookieJar($this->app['cookie']);
		    }

		    if (method_exists($guard, 'setDispatcher')) {
			    $guard->setDispatcher($this->app['events']);
		    }

		    if (method_exists($guard, 'setRequest')) {
			    $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
		    }

		    return $guard;
	    });



    }

	/**
	 * creates a custom grant for eqbids pin system
	 * @return CustomRequestGrant
	 */
	protected function makeEqbidsPinGrant()
	{
		$grant = new eqBidsPinGrant(
			$this->app->make(UserRepository::class),
			$this->app->make(RefreshTokenRepository::class)
		);
		$grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
		return $grant;
	}

}
