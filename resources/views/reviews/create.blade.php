<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Написать отзыв - Phone Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rating-stars {
            font-size: 2rem;
            cursor: pointer;
        }
        .rating-stars .star {
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-stars .star:hover,
        .rating-stars .star.active {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('phones.index') }}">
                <i class="fas fa-mobile-alt"></i> Phone Store
            </a>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-edit"></i> Написать отзыв</h4>
                        </div>
                        <div class="card-body">
                            <!-- Информация о товаре -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    @if($phone->image)
                                        <img src="{{ $phone->image }}" class="img-fluid rounded" alt="{{ $phone->name }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="fas fa-mobile-alt fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h5>{{ $phone->name }}</h5>
                                    <p class="text-muted mb-1">{{ $phone->brand->name }}</p>
                                    <p class="mb-1">{{ $phone->ram }}, {{ $phone->storage }}</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Форма отзыва -->
                            <form method="POST" action="{{ route('reviews.store', $phone->slug) }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="form-label">Оценка *</label>
                                    <div class="rating-stars mb-2" id="ratingStars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star star" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="0" required>
                                    @error('rating')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="form-label">Отзыв *</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              id="comment" name="comment" rows="6" 
                                              placeholder="Поделитесь вашим мнением о товаре..." 
                                              required>{{ old('comment') }}</textarea>
                                    <div class="form-text">Минимум 10 символов</div>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Информация:</h6>
                                    <ul class="mb-0">
                                        <li>Отзыв будет опубликован от вашего имени: <strong>{{ auth()->user()->name }}</strong></li>
                                        <li>Вы сможете редактировать или удалить свой отзыв позже</li>
                                        <li>Отзывы помогают другим покупателям сделать правильный выбор</li>
                                    </ul>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane"></i> Опубликовать отзыв
                                    </button>
                                    <a href="{{ route('phones.show', $phone->slug) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Вернуться к товару
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('ratingInput');
            let currentRating = 0;

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    currentRating = rating;
                    ratingInput.value = rating;

                    // Обновляем отображение звезд
                    stars.forEach(s => {
                        const starRating = parseInt(s.getAttribute('data-rating'));
                        if (starRating <= rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    stars.forEach(s => {
                        const starRating = parseInt(s.getAttribute('data-rating'));
                        if (starRating <= rating) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    stars.forEach(s => {
                        const starRating = parseInt(s.getAttribute('data-rating'));
                        if (starRating <= currentRating) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>