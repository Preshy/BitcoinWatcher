<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use GuzzleHttp\Client as GuzzleClient;

class BitcoinPriceController extends Controller
{
    public $twilio;
    protected $sid;
    protected $token;

    public function __construct() {
        // Your Account SID and Auth Token from twilio.com/console
        $this->sid = env('TWILIO_ACCOUNT_SID');
        $this->token = env('TWILIO_AUTH_TOKEN');
        /** Initialize the Twilio client */
        $this->twilio = new Client($this->sid, $this->token);
    }

    protected function bitcoinRate() {

        $headers = array('Accept' => 'application/json');

        $client = new GuzzleClient([
            'headers' => $headers,
        ]); //GuzzleHttp\Client

        $result = $client->post('https://cex.io/api/last_price/BTC/USD', []);
        $response = json_decode($result->getBody()->getContents(), true);
        $responseCode = $result->getStatusCode();

        return "$".number_format($response['lprice']);
    }

    public function sendRate() {
        $to = '+2349019392949'; // Phone Number To Send Rates To
        $bitcoinRate = $this->bitcoinRate();
        $message = "Bitcoin Rate For Today: $bitcoinRate";

        $this->twilio->messages->create($to,
            ['from' => env( 'TWILIO_FROM' ), 'body' => $message]
        );

        return true;
    }


}
