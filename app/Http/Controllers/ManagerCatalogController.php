<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManagerCatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('manager');
    }

    // Бренды
    public function brandsIndex()
    {
        $brands = Brand::withCount('phones')->latest()->get();
        return view('manager.catalog.brands', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('manager.catalog.brands')
            ->with('success', 'Бренд успешно добавлен.');
    }

    public function brandUpdate(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
        ]);

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('manager.catalog.brands')
            ->with('success', 'Бренд успешно обновлен.');
    }

    public function brandDestroy(Brand $brand)
    {
        if ($brand->phones()->exists()) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить бренд, так как есть связанные телефоны.');
        }

        $brand->delete();

        return redirect()->route('manager.catalog.brands')
            ->with('success', 'Бренд успешно удален.');
    }

    // Категории
    public function categoriesIndex()
    {
        $categories = Category::withCount('phones')->latest()->get();
        return view('manager.catalog.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('manager.catalog.categories')
            ->with('success', 'Категория успешно добавлена.');
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('manager.catalog.categories')
            ->with('success', 'Категория успешно обновлена.');
    }

    public function categoryDestroy(Category $category)
    {
        if ($category->phones()->exists()) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить категорию, так как есть связанные телефоны.');
        }

        $category->delete();

        return redirect()->route('manager.catalog.categories')
            ->with('success', 'Категория успешно удалена.');
    }
}