<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManagerPromocodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('manager');
    }

    public function index()
    {
        $promocodes = Promocode::latest()->paginate(15);
        return view('manager.promocodes.index', compact('promocodes'));
    }

    public function create()
    {
        return view('manager.promocodes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promocodes,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        Promocode::create($request->all());

        return redirect()->route('manager.promocodes.index')
            ->with('success', 'Промокод успешно создан.');
    }

    public function edit(Promocode $promocode)
    {
        return view('manager.promocodes.edit', compact('promocode'));
    }

    public function update(Request $request, Promocode $promocode)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promocodes,code,' . $promocode->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean'
        ]);

        $promocode->update($request->all());

        return redirect()->route('manager.promocodes.index')
            ->with('success', 'Промокод успешно обновлен.');
    }

    public function destroy(Promocode $promocode)
    {
        $promocode->delete();

        return redirect()->route('manager.promocodes.index')
            ->with('success', 'Промокод успешно удален.');
    }

    public function generateCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Promocode::where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }
}