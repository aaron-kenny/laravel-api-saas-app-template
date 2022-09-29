<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // INDEX
    //////////////////////////////////////////////////
    public function index()
    {
        $settings = request()->user()->settings()->get('product_two');

        return view('settings.index', compact('settings'));
    }
    
    // UPDATE
    //////////////////////////////////////////////////
    public function update()
    {
        // Make sure nobody adds random settings
        $settings = request()->only([
            'settings.notifications.brokerResponse.email',
        ]);
        // dd($settings);

        $settings['product_two']['notifications']['brokerResponse']['email'] = request()->boolean('settings.notifications.brokerResponse.email');

        request()->user()->settings()->set('product_two', $settings['product_two']);

        // Validate request object?
        // request()->validate([

        // ]);

        // Returns false if not present, true for checked state
        // Transform request object notifications to boolean values
        
        return redirect()->route('settings.index')->with('status', 'Settings successfully updated!');
    }
    
    // DESTROY
    //////////////////////////////////////////////////
    public function destroy()
    {
        request()->user()->settings()->delete('product_two');

        return redirect()->route('settings.index')->with('status', 'Settings have been reset to defaults!');
    }
}
