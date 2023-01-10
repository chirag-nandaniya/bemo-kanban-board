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

    public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);

        foreach ($request->columns as $status) {
            foreach ($status['cards'] as $i => $card) {
                $order = $i + 1;
                if ($card['status_id'] !== $status['id'] || $card['order'] !== $order) {
                    request()->user()->cards()
                        ->find($card['id'])
                        ->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }

        return $request->user()->statuses()->with('cards')->get();
    }

    public function update()
    {
        //
    }
}
