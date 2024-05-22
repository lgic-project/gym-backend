<?php

namespace App\Http\Controllers;

use App\Models\Suscription;
use Illuminate\Http\Request;

class SuscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suscriptions = Suscription::paginate(50);
        return view('suscriptions.index', compact('suscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suscriptions',
            // add other validation rules as needed
        ]);

        $suscription = Suscription::create($request->all());

        return redirect()->route('suscriptions.index')
                         ->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Suscription $suscription)
    {
        return view('suscriptions.show', compact('suscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Suscription $suscription)
    {
        return view('suscriptions.edit', compact('suscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Suscription $suscription)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suscriptions,email,' . $suscription->id,
            // add other validation rules as needed
        ]);

        $suscription->update($request->all());

        return redirect()->route('suscriptions.index')
                         ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suscription $suscription)
    {
        $suscription->delete();

        return redirect()->back()
                         ->with('success', 'Subscription deleted successfully.');
    }
}
