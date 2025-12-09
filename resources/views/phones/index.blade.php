@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Фильтры -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Фильтры</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('phones.index') }}" id="filter-form">
                        <!-- Скрытые поля для сохранения сортировки -->
                        <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                        <!-- Поиск -->
                        <div class="mb-3">
                            <label class="form-label">Поиск</label>
                            <input type="text" name="search" class="form-control"
                                value="{{ request('search') }}" placeholder="Название телефона...">
                        </div>

                        <!-- Бренды -->
                        <div class="mb-3">
                            <label class="form-label">Бренд</label>
                            <select name="brand" class="form-select">
                                <option value="">Все бренды</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->slug }}"
                                    {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Категории -->
                        <div class="mb-3">
                            <label class="form-label">Категория</label>
                            <select name="category" class="form-select">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->slug }}"
                                    {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Цена -->
                        <div class="mb-3">
                            <label class="form-label">Цена, ₽</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control"
                                        placeholder="От" value="{{ request('min_price') }}" min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control"
                                        placeholder="До" value="{{ request('max_price') }}" min="0">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Применить фильтры
                        </button>
                        <a href="{{ route('phones.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times"></i> Сбросить все
                        </a>
                    </form>
                </div>
            </div>

            <!-- Быстрые фильтры -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Быстрый поиск</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => 0, 'max_price' => 30000]) }}"
                            class="btn btn-outline-primary btn-sm">До 30 000 ₽</a>
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => 30000, 'max_price' => 60000]) }}"
                            class="btn btn-outline-primary btn-sm">30 000 - 60 000 ₽</a>
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => 60000]) }}"
                            class="btn btn-outline-primary btn-sm">От 60 000 ₽</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Список товаров -->
        <div class="col-md-9">
            <!-- Заголовок и сортировка -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2>Мобильные телефоны</h2>
                    <p class="text-muted">
                        Найдено {{ $phones->total() }} товаров
                        @if(request()->anyFilled(['search', 'brand', 'category', 'min_price', 'max_price']))
                        <span class="badge bg-info">фильтры применены</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <label for="sort" class="form-label mb-0 me-2 fw-bold">Сортировка:</label>
                        <select name="sort" id="sort" class="form-select w-auto" onchange="updateSorting(this.value)">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена по возрастанию</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена по убыванию</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Название А-Я</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Название Я-А</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Популярные</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Активные фильтры -->
            @if(request()->anyFilled(['search', 'brand', 'category', 'min_price', 'max_price']))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted">Активные фильтры:</span>
                        @if(request('search'))
                        <span class="badge bg-primary">
                            Поиск: "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                        @endif
                        @if(request('brand'))
                        @php $activeBrand = $brands->where('slug', request('brand'))->first(); @endphp
                        @if($activeBrand)
                        <span class="badge bg-primary">
                            Бренд: {{ $activeBrand->name }}
                            <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                        @endif
                        @endif
                        @if(request('category'))
                        @php $activeCategory = $categories->where('slug', request('category'))->first(); @endphp
                        @if($activeCategory)
                        <span class="badge bg-primary">
                            Категория: {{ $activeCategory->name }}
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                        @endif
                        @endif
                        @if(request('min_price'))
                        <span class="badge bg-primary">
                            От {{ number_format(request('min_price'), 0, '', ' ') }} ₽
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                        @endif
                        @if(request('max_price'))
                        <span class="badge bg-primary">
                            До {{ number_format(request('max_price'), 0, '', ' ') }} ₽
                            <a href="{{ request()->fullUrlWithQuery(['max_price' => null]) }}" class="text-white ms-1">×</a>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Список товаров -->
            @if($phones->count() > 0)
            <div class="row">
                @foreach($phones as $phone)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Изображение -->
                        @if($phone->image)
                        <img src="{{ $phone->image }}" class="card-img-top product-image"
                            alt="{{ $phone->name }}"
                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjhmOWZhIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0jOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+0J/QtdGA0LXQvNC10L3QtdC90LjRjyDQvNC+0LHQuNC70YzQvdC+0LU8L3RleHQ+PC9zdmc+'">
                        @else
                        <div class="placeholder-image">
                            <i class="fas fa-mobile-alt fa-3x"></i>
                        </div>
                        @endif

                        <div class="position-absolute top-0 end-0 m-2">
                            @auth
                            <button class="btn btn-sm favorite-toggle {{ Auth::user()->favoritePhones->contains($phone->id) ? 'btn-danger' : 'btn-outline-danger' }}"
                                data-phone-id="{{ $phone->id }}"
                                title="{{ Auth::user()->favoritePhones->contains($phone->id) ? 'Удалить из избранного' : 'Добавить в избранное' }}">
                                <i class="fas fa-heart"></i>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm" title="Войдите чтобы добавить в избранное">
                                <i class="fas fa-heart"></i>
                            </a>
                            @endauth
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Бренд -->
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $phone->brand->name }}</span>
                                @if($phone->category)
                                <span class="badge bg-light text-dark">{{ $phone->category->name }}</span>
                                @endif
                            </div>

                            <!-- Название -->
                            <h5 class="card-title">{{ $phone->name }}</h5>

                            <!-- Характеристики -->
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-memory"></i> {{ $phone->ram }} |
                                    <i class="fas fa-hdd"></i> {{ $phone->storage }}
                                </small>
                            </div>

                            <!-- Описание (укороченное) -->
                            @if($phone->description)
                            <p class="card-text small text-muted flex-grow-1">
                                {{ Str::limit($phone->description, 80) }}
                            </p>
                            @endif

                            <div class="mt-auto">
                                <!-- Цена и кнопка -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 text-primary mb-0">
                                        {{ number_format($phone->price, 0, '', ' ') }} ₽
                                    </span>
                                    <a href="{{ route('phones.show', $phone->slug) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Подробнее
                                    </a>
                                </div>

                                <!-- Наличие -->
                                <div class="mt-2">
                                    @if($phone->stock > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> В наличии ({{ $phone->stock }})
                                    </span>
                                    @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times"></i> Нет в наличии
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($phones->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo; Назад</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $phones->previousPageUrl() }}" rel="prev">&laquo; Назад</a>
                                </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($phones->getUrlRange(1, $phones->lastPage()) as $page => $url)
                                @if ($page == $phones->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                                @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($phones->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $phones->nextPageUrl() }}" rel="next">Вперед &raquo;</a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <span class="page-link">Вперед &raquo;</span>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>

                    <!-- Информация о страницах -->
                    <div class="text-center text-muted mt-2">
                        Показано с {{ ($phones->currentPage() - 1) * $phones->perPage() + 1 }}
                        по {{ min($phones->currentPage() * $phones->perPage(), $phones->total()) }}
                        из {{ $phones->total() }} товаров
                    </div>
                </div>
            </div>
            @else
            <!-- Сообщение если товаров не найдено -->
            <div class="text-center py-5">
                <div class="py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h3>Товары не найдены</h3>
                    <p class="text-muted">Попробуйте изменить параметры фильтрации или поиска</p>
                    <a href="{{ route('phones.index') }}" class="btn btn-primary">
                        <i class="fas fa-times"></i> Сбросить фильтры
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-group .btn.active {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .product-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .placeholder-image {
        height: 200px;
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
</style>

<script>
    function updateSorting(sortValue) {
        const form = document.getElementById('filter-form');
        const sortInput = form.querySelector('input[name="sort"]');
        sortInput.value = sortValue;
        form.submit();
    }

    // Авто-сабмит при изменении фильтров
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filter-form');
        const filterSelects = filterForm.querySelectorAll('select[name="brand"], select[name="category"]');

        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Обработка быстрых фильтров цены
        const priceInputs = filterForm.querySelectorAll('input[name="min_price"], input[name="max_price"]');
        let priceTimeout;

        priceInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(priceTimeout);
                priceTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 1000);
            });
        });
    });
</script>
@endsection