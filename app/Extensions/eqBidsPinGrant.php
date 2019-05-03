<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 2/28/18
 * Time: 3:12 PM
 */

namespace  App\Extensions;

use App\Models\Security\Pin;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use \League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;


class eqBidsPinGrant extends AbstractGrant{


	public function __construct(
		UserRepositoryInterface $userRepository,
		RefreshTokenRepositoryInterface $refreshTokenRepository
	)
	{
		$this->setUserRepository($userRepository);
		$this->setRefreshTokenRepository($refreshTokenRepository);
		$this->refreshTokenTTL = new \DateInterval('P1Y');
	}

	public function getIdentifier() {
		return 'pin';
	}

	public function respondToAccessTokenRequest(
		\Psr\Http\Message\ServerRequestInterface $request,
		\League\OAuth2\Server\ResponseTypes\ResponseTypeInterface $responseType,
		\DateInterval $accessTokenTTL
	) {


        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user = $this->validateUser($request);
        $scopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getAuthIdentifier());
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getAuthIdentifier(), $scopes);
        $refreshToken = $this->issueRefreshToken($accessToken);
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);
        return $responseType;

	}

	private function validateUser(ServerRequestInterface $request)
	{
		$laravelRequest = new Request($request->getParsedBody());
		if (is_null($model = config('auth.providers.users.model'))) {
			throw OAuthServerException::serverError('Unable to determine user model.');
		}

		if($laravelRequest->input('pin') && $laravelRequest->input('email')){
			$user = User::where('email',$laravelRequest->input('email'))->first();
			if($user){
				$pin = Pin::where('user_id',$user->id)->first();
				if(!$pin){
					throw OAuthServerException::invalidCredentials();
				}

				if ($user->status==User::STATUS_BANNED || $user->status==User::STATUS_REJECTED){
					throw OAuthServerException::accessDenied('banned or rejected');
				}

				if($user->status==User::STATUS_INACTIVE){
					throw OAuthServerException::invalidCredentials();
				}

				if($user->status==User::STATUS_ON_APPROVAL){
					throw  OAuthServerException::accessDenied('On approval');
				}

				if ($pin->expires_at->lt(Carbon::now())){
					throw OAuthServerException::accessDenied('expired pin');
				}

				if($pin->checkAndInvalidate($laravelRequest->input('pin'))){
					$user->status=1;
					$user->save();
					return $user;
				}else{
					throw OAuthServerException::invalidCredentials();
				}

			}else{
				throw OAuthServerException::serverError('user not found');
			}
		}else{
			throw OAuthServerException::serverError('insufficient data');
		}


		$user= ($user) ? new User($user->id) : null;

		if ($user instanceof UserEntityInterface === false) {
			$this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
			throw OAuthServerException::invalidCredentials();
		}
		return $user;
	}






}