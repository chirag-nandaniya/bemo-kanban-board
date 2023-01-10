<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        
        $cards = auth()->user()->statuses()->with('cards')->get();

        return view('cards.index', compact('cards'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
            'description' => ['required', 'string'],
            'status_id' => ['required', 'exists:statuses,id']
        ]);
    
        return $request->user()
            ->cards()
            ->create($request->only('title', 'description', 'status_id'));
    }

    public function sync()
    {
        //
    }

    public function update()
    {
        //
    }
}
