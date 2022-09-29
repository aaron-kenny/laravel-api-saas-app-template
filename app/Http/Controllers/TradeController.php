<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TradeController extends Controller
{
    public function __invoke()
    {
        if(! request()->user()->tokenCan('product_two:write')) {
            return response()->json([
                'message' => 'The Product Two personal access token does not have write permissions'
            ], 403);
        }

        request()->validate([
            'broker' => [
                'required',
                Rule::in([
                    'oanda',
                    'oandaPractice',
                    'coinbasePro',
                    'coinbaseProSandbox',
                ]),
            ],
        ]);

        $brokerRequest = 'App\\Services\\Brokers\\' . ucfirst(request('broker')) . 'Request';

        return (new $brokerRequest())->send();
    }
}
