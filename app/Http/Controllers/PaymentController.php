<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Transaction;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $options['secret_api_key']='xnd_development_7TYV29AzCRzJwRk26ZsZRR74jyS8XaFDmGX3plTUryEirZGnsztGjog9w1ihCd';
        $this->server_domain = 'https://api.xendit.co';
        $this->secret_api_key = env('XND_SECRET');
    }
    function getBalance () {
        // $token = JWTAuth::getToken();
        // $apy = JWTAuth::getPayload($token)->toArray();
        // $data = User::all();
        // return $apy;
        $curl = curl_init();
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $end_point = $this->server_domain.'/balance';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERPWD, $this->secret_api_key.":");
        curl_setopt($curl, CURLOPT_URL, $end_point);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $responseObject = json_decode($response, true);
        return $responseObject;
    }
    function createInvoice (Request $request) {
        

        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $user = User::find($apy['sub']);
        // return $apy;
        $data = array(
            'external_id' => $request->ex_id,
            'payerEmail' => $user->email,
            'description' => $request->description,
            'amount' => $request->amount
        );
        //var_dump($data);
        $curl = curl_init();
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $end_point = $this->server_domain.'/v2/invoices';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_USERPWD, $this->secret_api_key.":");
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($curl, CURLOPT_URL, $end_point);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $responseObject = json_decode($response, true);
        $transaction = new Transaction();
        $transaction->product_name = $request->product_name;
        $transaction->user_id = $apy['sub'];
        $transaction->xendit_init = $responseObject;
        $transaction->save();
        return response()->json(['status' => "success", "invoice_url" => $transaction->xendit_init['invoice_url']], 201);
    }
    public function callback(){
        
    }
    //
}
