<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $order = $request->get('order', 'desc');

        $phones = Phone::with(['brand', 'category'])
            ->filter($request->all())
            ->when($sort, function ($query) use ($sort, $order) {
                return $this->applySorting($query, $sort, $order);
            })
            ->paginate(12)
            ->withQueryString();

        $brands = Brand::all();
        $categories = Category::all();

        return view('phones.index', compact('phones', 'brands', 'categories'));
    }

    public function show(Phone $phone)
    {
        $phone->load(['brand', 'category', 'reviews' => function($query) {
            $query->latest();
        }]);

        $relatedPhones = Phone::where('brand_id', $phone->brand_id)
            ->where('id', '!=', $phone->id)
            ->with(['brand', 'category'])
            ->limit(4)
            ->get();

        return view('phones.show', compact('phone', 'relatedPhones'));
    }

    private function applySorting($query, $sort, $order)
    {
        switch ($sort) {
            case 'price_asc':
                return $query->orderBy('price', 'asc');
            case 'price_desc':
                return $query->orderBy('price', 'desc');
            case 'name_asc':
                return $query->orderBy('name', 'asc');
            case 'name_desc':
                return $query->orderBy('name', 'desc');
            case 'newest':
                return $query->orderBy('created_at', 'desc');
            case 'oldest':
                return $query->orderBy('created_at', 'asc');
            case 'popular':
                return $query->orderBy('stock', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }
}