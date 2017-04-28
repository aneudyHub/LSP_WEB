<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
class UserController extends Controller
{
    public function register()
    {
        return view('pages.register');
    }

    public function signup(Request $request)
    {
        $appId = "YOUR-APPID";
        $appKey = "YOUR-APPKEY";
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $password = $request->input('password');
        $respAuth = \Guzzle::post('https://api.mesosfer.com/api/v2/users', [
            'json'    => ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'password' => $password],
            'headers' => ['X-Mesosfer-AppId' => $appId, 'X-Mesosfer-AppKey' => $appKey]
        ]);

        $bodyAuth = (string) $respAuth->getBody();
        $auth = json_decode($bodyAuth);
        echo "<pre>";
        var_dump($auth);
        echo "</pre>";
    }
    public function login()
    {
        if(Session::get('userdata'))
            return view('welcome');

        if (session()->has('users')) {

        }

        return view('pages.login');
    }

    public function signin(Request $request)
    {
        $client_id = "id1";
        $grant_type = "password";
        $client_secret = "secret1";

        $email = $request->input('email');
        $password = $request->input('password');

        $client = new \GuzzleHttp\Client(['base_uri' => url('oauth/access_token'), 'cookies' => true, 'content-type' => 'application/json', 'http_errors' => false]);
        try {

            $respAuth= $client->post('access_token', [
                'form_params'    => ['username' => $email, 'password' => $password, 'client_id' => $client_id, 'grant_type' => $grant_type, 'client_secret' => $client_secret]
            ]);



            if ($respAuth->getStatusCode() =="200") {

                $bodyAuth = (string)$respAuth->getBody();
                $auth = json_decode($bodyAuth);

                $userdata = array(
                    'access_token' => $auth->access_token,
                    'token_type' => $auth->token_type,
                    'expires_in' => $auth->expires_in
                );
                Session::put('userdata', $userdata);
                return json_encode($userdata);
            }
            else
            {
                $bodyAuth = (string)$respAuth->getBody();
                $auth = json_decode($bodyAuth);

                $error = array(
                    'error' => $auth->error,
                    'error_description' => $auth->error_description
                );

                return json_encode($error);
            }



        } catch(GuzzleHttp\Exception\ClientException $e){
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    public function logOut()
    {
        Session::flush();
    }

}