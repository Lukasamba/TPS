<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use Hash;

class AuthController extends Controller
{

  public function signin(){
    // Initialize the OAuth client
    $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => config('azure.appId'),
      'clientSecret'            => config('azure.appSecret'),
      'redirectUri'             => config('azure.redirectUri'),
      'urlAuthorize'            => config('azure.authority').config('azure.authorizeEndpoint'),
      'urlAccessToken'          => config('azure.authority').config('azure.tokenEndpoint'),
      'urlResourceOwnerDetails' => '',
      'scopes'                  => config('azure.scopes')
    ]);

    $authUrl = $oauthClient->getAuthorizationUrl();

    // Save client state so we can validate in callback
    session(['oauthState' => $oauthClient->getState()]);

    // Redirect to AAD signin page
    return redirect()->away($authUrl);
  }

  public function callback(Request $request){
    // Validate state
    $expectedState = session('oauthState');
    $request->session()->forget('oauthState');
    $providedState = $request->query('state');

    if (!isset($expectedState)) {
      // If there is no expected state in the session,
      // do nothing and redirect to the home page.
      return redirect('/');
    }

    if (!isset($providedState) || $expectedState != $providedState) {
      return redirect('/')
        ->with('error', 'Invalid auth state')
        ->with('errorDetail', 'The provided auth state did not match the expected value');
    }

    // Authorization code should be in the "code" query param
    $authCode = $request->query('code');
    if (isset($authCode)) {
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => config('azure.appId'),
        'clientSecret'            => config('azure.appSecret'),
        'redirectUri'             => config('azure.redirectUri'),
        'urlAuthorize'            => config('azure.authority').config('azure.authorizeEndpoint'),
        'urlAccessToken'          => config('azure.authority').config('azure.tokenEndpoint'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => config('azure.scopes')
      ]);

      try {
        // Make the token request
        $accessToken = $oauthClient->getAccessToken('authorization_code', [
          'code' => $authCode

        ]);

        // dd($accessToken);
        $graph = new Graph();
        $graph->setAccessToken($accessToken->getToken());

        $user = $graph->createRequest('GET', '/me?$select=displayName,mail,mailboxSettings,userPrincipalName')
          ->setReturnType(Model\User::class)
          ->execute();

        $tokenCache = new TokenCache();
        $tokenCache->storeTokens($accessToken, $user);

        //return redirect('/');
        return redirect()->route('sync');
      }
      catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return redirect('/')
          ->with('error', 'Error requesting access token')
          ->with('errorDetail', json_encode($e->getResponseBody()));
      }
    }

    return redirect('/')
      ->with('error', $request->query('error'))
      ->with('errorDetail', $request->query('error_description'));
  }

  public function signout(){
    $tokenCache = new TokenCache();
    $tokenCache->clearTokens();
    return redirect('/');
  }

  public function sync(){
    $checker = User::select([
      'accessToken', 
      'refreshToken', 
      'tokenExpires'])->where('userEmail', session()->get('userEmail'))->exists();

    if(!$checker){
      $user = new User();
      $user->userEmail = session()->get('userEmail');
      $user->userName = session()->get('userName');
      $user->accessToken = Hash::make(session()->get('accessToken'));
      $user->refreshToken = Hash::make(session()->get('refreshToken'));
      $user->tokenExpires = session()->get('tokenExpires');
      $result = $user->save();
      return redirect('/');
    }
    else {
      $checker = User::select([
        'accessToken', 
        'refreshToken', 
        'tokenExpires'])->where('userEmail', session()->get('userEmail'))->exists();
      User::where('userEmail',session()->get('userEmail'))->update([
        'accessToken' => Hash::make(session()->get('accessToken')), 
        'refreshToken' => Hash::make(session()->get('refreshToken')), 
        'tokenExpires' => session()->get('tokenExpires')]);
        return redirect('/');
    }
  }

}