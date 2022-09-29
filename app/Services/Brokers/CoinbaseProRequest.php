<?php

namespace App\Services\Brokers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Notifications\BrokerResponse;

class CoinbaseProRequest implements BrokerRequestInterface
{
    protected $name;
    protected $baseUrl = 'https://api.pro.coinbase.com';
    protected $fullUrl;
    protected $requestPath;
    protected $method;
    protected $data;
    protected $action;
    protected $key;
    protected $secret;
    protected $passphrase;
    protected $timestamp;

    public function __construct()
    {
        $this->validate();
        
        $this->data = request()->except([
            'productTwoApiToken',
            'broker',
            'action',
            'coinbaseProApiKey',
            'coinbaseProApiSecret',
            'coinbaseProApiKeyPassphrase',
        ]);

        $this->name = request('broker');
        $this->key = request('coinbaseProApiKey');
        $this->secret = request('coinbaseProApiSecret');
        $this->passphrase = request('coinbaseProApiKeyPassphrase');
        $this->action = request('action');

        $action = $this->action;
        $this->$action();
    }

    protected function placeOrder()
    {
        $this->requestPath = '/orders';
        $this->fullUrl = $this->baseUrl . $this->requestPath;
        $this->method = 'post';
    }
    
    protected function cancelOrder()
    {
        request()->validate([
            'id' => 'required'
        ]);

        $this->requestPath = '/orders/' . request('id');
        if(request()->filled('product_id')) {
            $this->requestPath .= '?product_id=' . request('product_id');
        }
        $this->fullUrl = $this->baseUrl . $this->requestPath;
        $this->method = 'delete';
    }
    
    protected function cancelAllOrders()
    {
        $this->requestPath = '/orders';
        if(request()->filled('product_id')) {
            $this->requestPath .= '?product_id=' . request('product_id');
        }
        $this->fullUrl = $this->baseUrl . $this->requestPath;
        $this->method = 'delete';
    }

    public function send()
    {
        $method = $this->method;
        $this->timestamp = Http::get($this->baseUrl . '/time')['epoch'] ?? time();
        $signature = $this->signature();

        $response = Http::withHeaders([
            'CB-ACCESS-KEY' => $this->key,
            'CB-ACCESS-SIGN' => $signature,
            'CB-ACCESS-TIMESTAMP' => $this->timestamp,
            'CB-ACCESS-PASSPHRASE' => $this->passphrase,
        ])->$method($this->fullUrl, $this->data);

        $response_pretty = json_encode($response->json(), JSON_PRETTY_PRINT);

        request()->user()->notify(new BrokerResponse($this->name, $response_pretty));
        return response($response->json(), $response->status());
    }

    protected function signature() {
        $what = $this->timestamp . strtoupper($this->method) . $this->requestPath . json_encode($this->data);
        return base64_encode(hash_hmac("sha256", $what, base64_decode($this->secret), true));
    }

    protected function validate()
    {
        request()->validate([
            'coinbaseProApiKey' => 'required',
            'coinbaseProApiSecret' => 'required',
            'coinbaseProApiKeyPassphrase' => 'required',
            'action' => [
                'required',
                Rule::in([
                    'placeOrder',
                    'cancelOrder',
                    'cancelAllOrders',
                ]),
            ],
        ]);
    }
}
