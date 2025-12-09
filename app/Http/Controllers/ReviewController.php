<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function create($phoneSlug)
    {
        $phone = Phone::where('slug', $phoneSlug)->firstOrFail();
        
        $existingReview = Review::where('phone_id', $phone->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('phones.show', $phone->slug)
                ->with('error', 'Вы уже оставляли отзыв на этот товар.');
        }

        return view('reviews.create', compact('phone'));
    }

    public function store(Request $request, $phoneSlug)
    {
        $phone = Phone::where('slug', $phoneSlug)->firstOrFail();

        $existingReview = Review::where('phone_id', $phone->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('phones.show', $phone->slug)
                ->with('error', 'Вы уже оставляли отзыв на этот товар.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Пожалуйста, поставьте оценку.',
            'rating.min' => 'Оценка должна быть от 1 до 5.',
            'rating.max' => 'Оценка должна быть от 1 до 5.',
            'comment.required' => 'Пожалуйста, напишите отзыв.',
            'comment.min' => 'Отзыв должен содержать не менее 10 символов.',
            'comment.max' => 'Отзыв не должен превышать 1000 символов.',
        ]);

        Review::create([
            'phone_id' => $phone->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('phones.show', $phone->slug)
            ->with('success', 'Спасибо! Ваш отзыв успешно добавлен.');
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав для редактирования этого отзыва.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав для редактирования этого отзыва.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('phones.show', $review->phone->slug)
            ->with('success', 'Отзыв успешно обновлен.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав для удаления этого отзыва.');
        }

        $phoneSlug = $review->phone->slug;
        $review->delete();

        return redirect()->route('phones.show', $phoneSlug)
            ->with('success', 'Отзыв успешно удален.');
    }
}