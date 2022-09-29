<?php

namespace App\Services\Brokers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Notifications\BrokerResponse;

class OandaRequest implements BrokerRequestInterface
{
    protected $name;
    protected $baseUrl = 'https://api-fxtrade.oanda.com';
    protected $fullUrl;
    protected $data;
    protected $token;
    protected $account;
    protected $action;
    protected $instrument;
    protected $method;

    public function __construct()
    {
        $this->validate();

        $this->data = request()->except([
            'productTwoApiToken',
            'broker',
            'action',
            'oandaAccount',
            'oandaApiToken',
        ]);

        $this->name = request('broker');
        $this->token = request('oandaApiToken');
        $this->account = request('oandaAccount');
        $this->action = request('action');

        $action = $this->action;
        $this->$action();
    }

    protected function placeOrder()
    {
        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/orders';
        $this->method = 'post';
    }
    
    protected function replaceOrder()
    {
        request()->validate([
            'orderSpecifier' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/orders/' . request('orderSpecifier');
        $this->method = 'put';
    }
    
    protected function cancelOrder()
    {
        request()->validate([
            'orderSpecifier' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/orders/' . request('orderSpecifier') . '/cancel';
        $this->method = 'put';
    }
    
    protected function updateOrderClientExtensions()
    {
        request()->validate([
            'orderSpecifier' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/orders/' . request('orderSpecifier') . '/clientExtensions';
        $this->method = 'put';
    }

    protected function closeTrade()
    {
        request()->validate([
            'tradeSpecifier' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/trades/' . request('tradeSpecifier') . '/close';
        $this->method = 'put';
    }
    
    protected function updateTradeClientExtensions()
    {
        request()->validate([
            'tradeSpecifier' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/trades/' . request('tradeSpecifier') . '/clientExtensions';
        $this->method = 'put';
    }
    
    protected function updateTradeOrders()
    {
        request()->validate([
            'tradeSpecifier' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/trades/' . request('tradeSpecifier') . '/orders';
        $this->method = 'put';
    }
    
    protected function closePosition()
    {
        request()->validate([
            'instrument' => 'required'
        ]);

        $this->fullUrl = $this->baseUrl . '/v3/accounts/' . $this->account . '/positions/' . request('instrument') . '/close';
        $this->method = 'put';
    }

    public function send()
    {
        $method = $this->method;
        $response = Http::withToken($this->token)->$method($this->fullUrl, $this->data);

        $response_pretty = json_encode($response->json(), JSON_PRETTY_PRINT);

        request()->user()->notify(new BrokerResponse($this->name, $response_pretty));
        return response($response->json(), $response->status());
    }

    protected function validate()
    {
        request()->validate([
            'oandaAccount' => 'required',
            'oandaApiToken' => 'required',
            'action' => [
                'required',
                Rule::in([
                    'placeOrder',
                    'replaceOrder',
                    'cancelOrder',
                    'updateOrderClientExtensions',
                    'closeTrade',
                    'updateTradeClientExtensions',
                    'updateTradeOrders',
                    'closePosition',
                ]),
            ],
        ]);
    }

    protected function formatOandaInstrument()
    {
        $formatted = Str::of(request()['order.instrument'])->substr(0, 4)->endsWith('_');
        if(!$formatted) {
            request()['order.instrument'] = Str::of(request()['order.instrument'])->substr(0, 3) . '_' . Str::of(request()['order.instrument'])->substr(3);
        }
    }
}
