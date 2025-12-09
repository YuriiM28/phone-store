@extends('layouts.app')

@section('title', 'Избранное')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-heart text-danger"></i> Избранное</h2>
    <div class="d-flex align-items-center">
        <span class="badge bg-primary me-2" id="favorites-count">{{ $favorites->total() }} товаров</span>
        <a href="{{ route('phones.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Вернуться в каталог
        </a>
    </div>
</div>

@if($favorites->count() > 0)
    <div class="row">
        @foreach($favorites as $phone)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 favorite-card">
                    <div class="position-relative">
                        @if($phone->image)
                            <img src="{{ $phone->image }}" class="card-img-top product-image" alt="{{ $phone->name }}">
                        @else
                            <div class="placeholder-image">
                                <i class="fas fa-mobile-alt fa-3x"></i>
                            </div>
                        @endif
                        <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 favorite-toggle" 
                                data-phone-id="{{ $phone->id }}" title="Удалить из избранного">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $phone->brand->name }}</span>
                        </div>

                        <h6 class="card-title">{{ $phone->name }}</h6>
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-memory"></i> {{ $phone->ram }} | 
                                <i class="fas fa-hdd"></i> {{ $phone->storage }}
                            </small>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 text-primary mb-0">{{ number_format($phone->price, 0, '', ' ') }} ₽</span>
                                @if($phone->stock > 0)
                                    <a href="{{ route('phones.show', $phone->slug) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                            
                            <div class="mt-2">
                                @if($phone->stock > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> В наличии
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
    <div class="d-flex justify-content-center mt-4">
        {{ $favorites->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-heart fa-4x text-muted mb-3"></i>
        <h3>В избранном пока пусто</h3>
        <p class="text-muted mb-4">Добавляйте товары в избранное, чтобы не потерять</p>
        <a href="{{ route('phones.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bag"></i> Перейти в каталог
        </a>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Удаление из избранного
    document.querySelectorAll('.favorite-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const phoneId = this.getAttribute('data-phone-id');
            const card = this.closest('.favorite-card');
            
            fetch(`/favorites/${phoneId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Удаляем карточку
                    card.remove();
                    
                    // Обновляем счетчик
                    const favoritesCount = document.getElementById('favorites-count');
                    const currentCount = parseInt(favoritesCount.textContent);
                    favoritesCount.textContent = (currentCount - 1) + ' товаров';
                    
                    // Показываем уведомление
                    showToast(data.message, 'success');
                    
                    // Если товаров не осталось, показываем сообщение
                    if (currentCount - 1 === 0) {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка', 'error');
            });
        });
    });
    
    function showToast(message, type = 'info') {
        // Простая реализация toast уведомления
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>
@endpush