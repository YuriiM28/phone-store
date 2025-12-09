<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа - Phone Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .user-data-badge {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            color: #1976d2;
        }
        .discount-badge {
            background-color: #28a745;
            color: white;
        }
        .promocode-success {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .promocode-error {
            border-color: #dc3545;
            background-color: #fff8f8;
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
                            <h4 class="mb-0"><i class="fas fa-shopping-cart"></i> Оформление заказа</h4>
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
                                    <p class="mb-1"><strong>Характеристики:</strong> {{ $phone->ram }}, {{ $phone->storage }}</p>
                                    <h4 class="text-primary mb-0">{{ number_format($phone->price, 0, '', ' ') }} ₽</h4>
                                </div>
                            </div>

                            <hr>

                            <!-- Уведомление о предзаполнении -->
                            @auth
                                @if(!empty(array_filter($userData)))
                                    <div class="alert alert-info mb-4">
                                        <h6><i class="fas fa-info-circle"></i> Данные предзаполнены из вашего профиля</h6>
                                        <p class="mb-2">Мы автоматически подставили ваши контактные данные. Вы можете отредактировать их если нужно.</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($userData['customer_name'] ?? false)
                                                <span class="badge user-data-badge">Имя: {{ $userData['customer_name'] }}</span>
                                            @endif
                                            @if($userData['customer_email'] ?? false)
                                                <span class="badge user-data-badge">Email: {{ $userData['customer_email'] }}</span>
                                            @endif
                                            @if($userData['customer_phone'] ?? false)
                                                <span class="badge user-data-badge">Телефон: {{ $userData['customer_phone'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endauth

                            <!-- Блок промокода -->
                            <div class="card mb-4" id="promocode-section">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-tag text-success"></i> Применить промокод</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-end">
                                        <div class="col-md-8 mb-2">
                                            <input type="text" class="form-control" id="promocode-input" 
                                                   placeholder="Введите промокод (например: WELCOME10)" 
                                                   value="{{ old('promocode') }}">
                                            <div class="form-text">Есть промокод? Введите его для получения скидки</div>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <button type="button" class="btn btn-success w-100" id="apply-promocode">
                                                <i class="fas fa-check"></i> Применить
                                            </button>
                                        </div>
                                    </div>
                                    <div id="promocode-result" class="mt-2"></div>
                                    <input type="hidden" name="promocode_id" id="promocode_id" value="{{ old('promocode_id') }}">
                                    <input type="hidden" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}">
                                </div>
                            </div>

                            <!-- Итоговая стоимость -->
                            <div class="card mb-4 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3"><i class="fas fa-receipt"></i> Итоговая стоимость</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">Стоимость товара:</div>
                                            <div class="mb-2">Скидка:</div>
                                            <div class="fw-bold fs-5">Итого к оплате:</div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="mb-2" id="base-price">{{ number_format($phone->price, 0, '', ' ') }} ₽</div>
                                            <div class="mb-2 text-success" id="discount-display">0 ₽</div>
                                            <div class="fw-bold fs-5 text-primary" id="final-price">{{ number_format($phone->price, 0, '', ' ') }} ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Форма заказа -->
                            <form method="POST" action="{{ route('orders.store', $phone->slug) }}" id="order-form">
                                @csrf
                                
                                <!-- Скрытые поля для данных о промокоде и ценах -->
                                <input type="hidden" name="promocode_id" id="form_promocode_id" value="{{ old('promocode_id') }}">
                                <input type="hidden" name="discount_amount" id="form_discount_amount" value="{{ old('discount_amount', 0) }}">
                                <input type="hidden" name="total_amount" id="form_total_amount" value="{{ $phone->price }}">
                                <input type="hidden" name="final_amount" id="form_final_amount" value="{{ $phone->price }}">
                                
                                <h5 class="mb-3">Контактные данные</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_name" class="form-label">ФИО *</label>
                                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                               id="customer_name" name="customer_name" 
                                               value="{{ old('customer_name', $userData['customer_name'] ?? '') }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                               id="customer_email" name="customer_email" 
                                               value="{{ old('customer_email', $userData['customer_email'] ?? '') }}" required>
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_phone" class="form-label">Телефон *</label>
                                        <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" name="customer_phone" 
                                               value="{{ old('customer_phone', $userData['customer_phone'] ?? '') }}" required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_address" class="form-label">Адрес доставки *</label>
                                        <input type="text" class="form-control @error('customer_address') is-invalid @enderror" 
                                               id="customer_address" name="customer_address" 
                                               value="{{ old('customer_address', $userData['customer_address'] ?? '') }}" required>
                                        @error('customer_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="form-label">Комментарий к заказу</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Укажите удобное время доставки или другие пожелания">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Информация о заказе:</h6>
                                    <ul class="mb-0">
                                        <li>Доставка осуществляется в течение 2-3 рабочих дней</li>
                                        <li>С вами свяжется менеджер для подтверждения заказа</li>
                                        <li>Оплата при получении</li>
                                        @auth
                                            <li>Этот заказ будет сохранен в вашей 
                                                <a href="{{ route('orders.index') }}" class="alert-link">истории заказов</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-check"></i> 
                                        Подтвердить заказ
                                    </button>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('phones.show', $phone->slug) }}" class="btn btn-outline-secondary flex-fill">
                                            <i class="fas fa-arrow-left"></i> Вернуться к товару
                                        </a>
                                    </div>
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
        const basePrice = {{ $phone->price }};
        let currentDiscount = 0;
        let currentPromocodeId = null;

        // Применение промокода
        document.getElementById('apply-promocode').addEventListener('click', function() {
            const promocodeInput = document.getElementById('promocode-input');
            const code = promocodeInput.value.trim();
            
            if (!code) {
                showPromocodeResult('Введите промокод', 'error');
                return;
            }

            const button = this;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Проверка...';

            fetch('{{ route("promocode.validate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    order_amount: basePrice
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    currentDiscount = data.discount;
                    currentPromocodeId = data.promocode.id;
                    const finalAmount = basePrice - currentDiscount;
                    
                    // Обновляем скрытые поля формы
                    document.getElementById('form_promocode_id').value = currentPromocodeId;
                    document.getElementById('form_discount_amount').value = currentDiscount;
                    document.getElementById('form_final_amount').value = finalAmount;
                    
                    // Обновляем отображение цен
                    updatePriceDisplay();
                    
                    // Показываем успешное сообщение
                    const discountText = data.promocode.type === 'percentage' 
                        ? `${data.promocode.value}%` 
                        : `${data.discount_formatted} ₽`;
                    
                    showPromocodeResult(
                        `✅ Промокод применен! Скидка: ${discountText}`,
                        'success'
                    );
                    
                    // Меняем внешний вид блока промокода
                    document.getElementById('promocode-section').classList.add('promocode-success');
                    document.getElementById('promocode-section').classList.remove('promocode-error');
                    
                } else {
                    showPromocodeResult(data.message, 'error');
                    resetPromocode();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showPromocodeResult('Произошла ошибка при проверке промокода', 'error');
                resetPromocode();
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check"></i> Применить';
            });
        });

        // Сброс промокода при изменении поля
        document.getElementById('promocode-input').addEventListener('input', function() {
            if (currentDiscount > 0) {
                resetPromocode();
            }
        });

        function updatePriceDisplay() {
            const finalAmount = basePrice - currentDiscount;
            
            document.getElementById('discount-display').textContent = `-${formatPrice(currentDiscount)} ₽`;
            document.getElementById('final-price').textContent = `${formatPrice(finalAmount)} ₽`;
            
            // Добавляем анимацию
            document.getElementById('final-price').classList.add('text-success');
            setTimeout(() => {
                document.getElementById('final-price').classList.remove('text-success');
            }, 1000);
        }

        function resetPromocode() {
            currentDiscount = 0;
            currentPromocodeId = null;
            
            // Сбрасываем скрытые поля формы
            document.getElementById('form_promocode_id').value = '';
            document.getElementById('form_discount_amount').value = '0';
            document.getElementById('form_final_amount').value = basePrice;
            
            // Сбрасываем отображение
            document.getElementById('discount-display').textContent = '0 ₽';
            document.getElementById('final-price').textContent = `${formatPrice(basePrice)} ₽`;
            
            document.getElementById('promocode-section').classList.remove('promocode-success', 'promocode-error');
            document.getElementById('promocode-result').innerHTML = '';
        }

        function showPromocodeResult(message, type) {
            const resultDiv = document.getElementById('promocode-result');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            
            resultDiv.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show mb-0">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Автоматическое скрытие успешных сообщений
            if (type === 'success') {
                setTimeout(() => {
                    const alert = resultDiv.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }
        }

        function formatPrice(price) {
            return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }

        // Автозаполнение телефона
        const phoneInput = document.getElementById('customer_phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.startsWith('7') || value.startsWith('8')) {
                    value = value.substring(1);
                }
                
                if (value.length > 0) {
                    let formatted = '+7 (';
                    
                    if (value.length > 0) {
                        formatted += value.substring(0, 3);
                    }
                    if (value.length > 3) {
                        formatted += ') ' + value.substring(3, 6);
                    }
                    if (value.length > 6) {
                        formatted += '-' + value.substring(6, 8);
                    }
                    if (value.length > 8) {
                        formatted += '-' + value.substring(8, 10);
                    }
                    
                    e.target.value = formatted;
                }
            });
        }

        // Валидация формы перед отправкой
        document.getElementById('order-form').addEventListener('submit', function(e) {
            // Убедимся, что все скрытые поля обновлены
            document.getElementById('form_total_amount').value = basePrice;
            document.getElementById('form_final_amount').value = basePrice - currentDiscount;
            document.getElementById('form_discount_amount').value = currentDiscount;
            document.getElementById('form_promocode_id').value = currentPromocodeId;
            
            console.log('Отправляемые данные:', {
                total_amount: basePrice,
                final_amount: basePrice - currentDiscount,
                discount_amount: currentDiscount,
                promocode_id: currentPromocodeId
            });
        });
    });
    </script>
</body>
</html>