<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Панель управления') - Phone Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
            border-left-color: #007bff;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('manager.dashboard') }}">
                <i class="fas fa-cogs"></i> Панель управления
            </a>
            <div class="d-flex">
                <a href="{{ route('phones.index') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-store"></i> Магазин
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-circle"></i> Профиль</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Выйти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}" 
                               href="{{ route('manager.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Дашборд
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.phones.*') ? 'active' : '' }}" 
                               href="{{ route('manager.phones.index') }}">
                                <i class="fas fa-mobile-alt"></i> Телефоны
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.promocodes.*') ? 'active' : '' }}" 
                               href="{{ route('manager.promocodes.index') }}">
                                <i class="fas fa-tag"></i> Промокоды
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.catalog.*') ? 'active' : '' }}" 
                               href="{{ route('manager.catalog.brands') }}">
                                <i class="fas fa-tags"></i> Каталог
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Управление
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('manager.catalog.brands') }}">
                                        <i class="fas fa-tag"></i> Бренды
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('manager.catalog.categories') }}">
                                        <i class="fas fa-folder"></i> Категории
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Основной контент -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>