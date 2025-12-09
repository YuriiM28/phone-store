<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManagerPhoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('manager');
    }

    public function index()
    {
        $phones = Phone::with(['brand', 'category'])->latest()->paginate(15);
        return view('manager.phones.index', compact('phones'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('manager.phones.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'screen_size' => 'nullable|string|max:50',
            'ram' => 'nullable|string|max:50',
            'storage' => 'nullable|string|max:50',
            'camera' => 'nullable|string|max:100',
            'battery' => 'nullable|string|max:50',
            'processor' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specifications' => 'nullable|array',
        ]);

        $phoneData = $request->except('image');
        $phoneData['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('phones', 'public');
            $phoneData['image'] = $imagePath;
        }

        if ($request->has('specifications')) {
            $specifications = [];
            foreach ($request->specifications as $key => $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    $specifications[$key] = $spec;
                }
            }
            $phoneData['specifications'] = !empty($specifications) ? $specifications : null;
        }

        Phone::create($phoneData);

        return redirect()->route('manager.phones.index')
            ->with('success', 'Телефон успешно добавлен в каталог.');
    }

    public function show(Phone $phone)
    {
        return view('manager.phones.show', compact('phone'));
    }

    public function edit(Phone $phone)
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('manager.phones.edit', compact('phone', 'brands', 'categories'));
    }

    public function update(Request $request, Phone $phone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'screen_size' => 'nullable|string|max:50',
            'ram' => 'nullable|string|max:50',
            'storage' => 'nullable|string|max:50',
            'camera' => 'nullable|string|max:100',
            'battery' => 'nullable|string|max:50',
            'processor' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specifications' => 'nullable|array',
        ]);

        $phoneData = $request->except('image');
        $phoneData['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($phone->image && Storage::disk('public')->exists($phone->image)) {
                Storage::disk('public')->delete($phone->image);
            }
            
            $imagePath = $request->file('image')->store('phones', 'public');
            $phoneData['image'] = $imagePath;
        }

        if ($request->has('specifications')) {
            $specifications = [];
            foreach ($request->specifications as $key => $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    $specifications[$key] = $spec;
                }
            }
            $phoneData['specifications'] = !empty($specifications) ? $specifications : null;
        }

        $phone->update($phoneData);

        return redirect()->route('manager.phones.index')
            ->with('success', 'Телефон успешно обновлен.');
    }

    public function destroy(Phone $phone)
    {
        if ($phone->orders()->exists()) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить телефон, так как есть связанные заказы.');
        }

        if ($phone->image && Storage::disk('public')->exists($phone->image)) {
            Storage::disk('public')->delete($phone->image);
        }

        $phone->delete();

        return redirect()->route('manager.phones.index')
            ->with('success', 'Телефон успешно удален.');
    }
}