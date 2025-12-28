<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        // Dummy credentials — replace with real authentication logic later
        $username = 'bus@iium';
        $password = 'bustracker';

        if ($request->username === $username && $request->password === $password) {
            // Login success → redirect to admin page
            return redirect()->route('admin.admin');
        } else {
            // Login failed → back to login page with error message
            return back()->with('error', 'Invalid username or password.');
        }
    }

    public function busInfo()
    {
        // Fetch bus info from database
        $buses = Bus::all(); // Make sure you import the Bus model: use App\Models\Bus;

        return view('admin.businfo', compact('buses'));
    }

    public function index()
    {
        $busCount = Bus::count(); // Count all buses from database
        return view('admin.admin', compact('busCount'));
    }

    public function track()
    {
        // You can pass bus data here later if needed
        $buses = \App\Models\Bus::all();
        return view('admin.track', compact('buses'));
    }

    public function schedule()
    {
        // You can pass bus data here later if needed
        return view('admin.schedule');
    }

    public function logout(Request $request)
    {

        return redirect('adminportal'); // redirect to adminportal page
    }

}
