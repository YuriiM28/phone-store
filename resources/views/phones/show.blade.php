@extends('layouts.app')

@section('title', $phone->name)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('phones.index') }}">Каталог</a></li>
        <li class="breadcrumb-item"><a href="{{ route('phones.index', ['brand' => $phone->brand->slug]) }}">{{ $phone->brand->name }}</a></li>
        <li class="breadcrumb-item active">{{ $phone->name }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Левая колонка с изображением -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($phone->image)
                <img src="{{ $phone->image }}" class="img-fluid rounded"
                    alt="{{ $phone->name }}" style="max-height: 500px;">
                @else
                <div class="placeholder-image rounded">
                    <i class="fas fa-mobile-alt fa-5x"></i>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Правая колонка с информацией -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="h3">{{ $phone->name }}</h1>
                <p class="text-muted">{{ $phone->brand->name }} • {{ $phone->category->name }}</p>

                <!-- Рейтинг -->
                <div class="mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rating-stars me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($phone->average_rating) ? '' : '-empty' }}"></i>
                                @endfor
                        </div>
                        <span class="text-muted">
                            {{ number_format($phone->average_rating, 1) }} ({{ $phone->reviews_count }} отзывов)
                        </span>
                    </div>
                </div>

                <!-- Цена и наличие -->
                <div class="mb-4">
                    <span class="h2 text-primary">{{ number_format($phone->price, 0, '', ' ') }} ₽</span>
                    @if($phone->stock > 0)
                    <span class="badge bg-success ms-2">
                        <i class="fas fa-check"></i> В наличии
                    </span>
                    @else
                    <span class="badge bg-danger ms-2">
                        <i class="fas fa-times"></i> Нет в наличии
                    </span>
                    @endif
                </div>

                <!-- Основные характеристики -->
                <div class="mb-4">
                    <h5>Основные характеристики:</h5>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <small class="text-muted">Экран:</small>
                            <div><strong>{{ $phone->screen_size }}</strong></div>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted">ОЗУ:</small>
                            <div><strong>{{ $phone->ram }}</strong></div>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted">Память:</small>
                            <div><strong>{{ $phone->storage }}</strong></div>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted">Камера:</small>
                            <div><strong>{{ $phone->camera }}</strong></div>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted">Батарея:</small>
                            <div><strong>{{ $phone->battery }}</strong></div>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted">Процессор:</small>
                            <div><strong>{{ $phone->processor }}</strong></div>
                        </div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                @if($phone->stock > 0)
                <a href="{{ route('orders.create', $phone->slug) }}" class="btn btn-primary btn-lg w-100 mb-3">
                    <i class="fas fa-shopping-cart"></i> Купить сейчас
                </a>
                @else
                <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                    <i class="fas fa-bell"></i> Уведомить о поступлении
                </button>
                @endif

                <div class="d-flex gap-2">
                    @auth
                    <button class="btn {{ Auth::user()->favoritePhones->contains($phone->id) ? 'btn-danger' : 'btn-outline-danger' }} flex-fill favorite-toggle"
                        data-phone-id="{{ $phone->id }}">
                        <i class="fas fa-heart"></i>
                        {{ Auth::user()->favoritePhones->contains($phone->id) ? 'В избранном' : 'В избранное' }}
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-danger flex-fill">
                        <i class="fas fa-heart"></i> В избранное
                    </a>
                    @endauth
                    <button class="btn btn-outline-secondary flex-fill">
                        <i class="fas fa-chart-bar"></i> Сравнить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Табы с дополнительной информацией -->
<div class="row mt-4">
    <div class="col-12">
        <ul class="nav nav-tabs" id="phoneTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                    data-bs-target="#description" type="button" role="tab">
                    Описание
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                    data-bs-target="#specifications" type="button" role="tab">
                    Характеристики
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                    data-bs-target="#reviews" type="button" role="tab">
                    Отзывы ({{ $phone->reviews_count }})
                </button>
            </li>
        </ul>

        <div class="tab-content p-3 border border-top-0 rounded-bottom">
            <!-- Описание -->
            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <h5>Описание товара</h5>
                <p>{{ $phone->description ?? 'Описание отсутствует.' }}</p>
            </div>

            <!-- Характеристики -->
            <div class="tab-pane fade" id="specifications" role="tabpanel">
                <h5>Полные характеристики</h5>
                @if($phone->specifications)
                <table class="table specs-table">
                    <tbody>
                        @foreach($phone->specifications as $spec)
                        <tr>
                            <td style="width: 30%;"><strong>{{ $spec['name'] }}</strong></td>
                            <td>{{ $spec['value'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">Характеристики не указаны.</p>
                @endif
            </div>

            <!-- Отзывы -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <h5 class="mb-4">Отзывы о товаре</h5>

                @auth
                @if(!$phone->reviews->where('user_id', auth()->id())->count())
                <div class="mb-4">
                    <a href="{{ route('reviews.create', $phone->slug) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Написать отзыв
                    </a>
                </div>
                @else
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> Вы уже оставили отзыв на этот товар.
                </div>
                @endif
                @else
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    Чтобы оставить отзыв, пожалуйста,
                    <a href="{{ route('login') }}" class="alert-link">войдите</a>
                    или
                    <a href="{{ route('register') }}" class="alert-link">зарегистрируйтесь</a>.
                </div>
                @endauth

                @if($phone->reviews_count > 0)
                <!-- Статистика отзывов -->
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="display-4 text-primary">{{ number_format($phone->average_rating, 1) }}</div>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($phone->average_rating) ? '' : '-empty' }}"></i>
                                @endfor
                        </div>
                        <div class="text-muted">на основе {{ $phone->reviews_count }} отзывов</div>
                    </div>
                </div>

                <!-- Список отзывов -->
                <div class="reviews-list">
                    @foreach($phone->reviews as $review)
                    <div class="card mb-3 review-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">
                                        {{ $review->author_name }}
                                        @if($review->user_id)
                                        <small class="text-muted">(пользователь)</small>
                                        @endif
                                    </h6>
                                    <div class="rating-stars small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-empty' }}"></i>
                                            @endfor
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <small class="text-muted">
                                        {{ $review->created_at->format('d.m.Y') }}
                                    </small>
                                    @auth
                                    @if($review->user_id === auth()->id())
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Удалить отзыв?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                    @endauth
                                </div>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5>Отзывов пока нет</h5>
                    <p class="text-muted">Будьте первым, кто оставит отзыв об этом товаре!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Рекомендуемые товары -->
@if(isset($relatedPhones) && $relatedPhones->count() > 0)
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">Похожие товары</h3>
        <div class="row">
            @foreach($relatedPhones as $relatedPhone)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    @if($relatedPhone->image)
                    <img src="{{ $relatedPhone->image }}" class="card-img-top product-image"
                        alt="{{ $relatedPhone->name }}">
                    @else
                    <div class="placeholder-image">
                        <i class="fas fa-mobile-alt fa-3x"></i>
                    </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $relatedPhone->name }}</h6>
                        <p class="card-text text-muted small">{{ $relatedPhone->brand->name }}</p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 text-primary mb-0">{{ number_format($relatedPhone->price, 0, '', ' ') }} ₽</span>
                                <a href="{{ route('phones.show', $relatedPhone->slug) }}"
                                    class="btn btn-outline-primary btn-sm">Смотреть</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection