<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    // INDEX
    //////////////////////////////////////////////////
    public function index()
    {
        $tokens = request()->user()->tokens->sortByDesc(function ($value) {
            return $value->created_at->getTimestamp();
        })->filter(function($token, $key) {
            return array_key_exists('product_two:read', array_flip($token->abilities));
        });

        return view('api.token.index', compact('tokens'));
    }
    
    // CREATE
    //////////////////////////////////////////////////
    public function create()
    {
        return view('api.token.create');
    }
    
    // STORE
    //////////////////////////////////////////////////
    public function store()
    {
        request()->validate([
            'token_name' => 'required|string|max:60',
        ]);

        // User selected abilities
        $user_selected_abilities = [];
        if(request('write_permission')) {
            $user_selected_abilities[] = 'product_two:write';
        }
        $abilities = array_merge(['product_two:read'], $user_selected_abilities);

        // Check max number of tokens
        $number_of_tokens = request()->user()->tokens->filter(function($token, $key) {
            return array_key_exists('product_two:read', array_flip($token->abilities));
        })->count();
        if($number_of_tokens >= 10) {
            return redirect()->route('api.token.index')->with('status', 'You already have the maximum number of Product Two access tokens (10). Please delete tokens you don\'t need, then try again.');
        }

        $token = request()->user()->createToken(request('token_name'), $abilities)->plainTextToken;

        return redirect()->route('api.token.index')->with('status', 'Here is your personal access token. This is the only time this will be shown to you. Please save it in a safe place. ' . $token);
    }
    
    // EDIT
    //////////////////////////////////////////////////
    public function edit($token_id)
    {
        $token = request()->user()->tokens()->findOrFail($token_id);

        $has_write_permission = array_key_exists('product_two:write', array_flip($token->abilities));

        return view('api.token.edit', compact('token', 'has_write_permission'));
    }
    
    // UPDATE
    //////////////////////////////////////////////////
    public function update($token_id)
    {
        request()->validate([
            'token_name' => 'required|string|max:60'
        ]);


        $user_selected_abilities = [];
        if(request('write_permission')) {
            $user_selected_abilities[] = 'product_two:write';
        }
        $abilities = array_merge(['product_two:read'], $user_selected_abilities);

        $token = request()->user()->tokens()->findOrFail($token_id);
        $token->name = request('token_name');
        $token->abilities = $abilities;
        $token->save();

        return redirect()->route('api.token.index')->with('status', 'Personal access token was successfully updated!');
    }
    
    // DESTROY API TOKENS
    //////////////////////////////////////////////////
    public function destroy($token_id)
    {
        request()->user()->tokens()->where('id', $token_id)->delete();

        return redirect()->route('api.token.index')->with('status', 'Personal access token was successfully deleted!');
    }
}
