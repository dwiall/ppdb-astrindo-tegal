<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM SPMB SMK ASTRINDO TEGAL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --accent-color: #06b6d4;
            --sidebar-width: 280px;
            --sidebar-collapsed: 70px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color), #1e40af);
            z-index: 1000;
            transition: .3s;
        }

        .sidebar-header {
            padding: 20px;
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            transition: .2s;
        }

        .sidebar-menu .menu-link:hover,
        .sidebar-menu .menu-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.9);
            font-size: 12px;
            background: rgba(0,0,0,.08);
        }
        .sidebar-footer .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .sidebar-footer .meta-item:last-child {
            margin-bottom: 0;
        }

        .top-navbar {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: 0;
            height: 70px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            z-index: 999;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
        }
        .header-search {
            max-width: 420px;
            width: 100%;
        }
        .search-empty-state {
            display: none;
            background: #fff;
            border-radius: 12px;
            padding: 14px 16px;
            color: #6b7280;
            margin-top: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }
        .layout-footer {
            margin-top: auto;
            padding-top: 22px;
            color: #64748b;
            font-size: 13px;
            text-align: center;
        }

        .page-header {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .page-subtitle {
            color: #6b7280;
            margin-top: 5px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #3b82f6, #06b6d4);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 700;
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header"><img src=" {{ asset('assets/logo/logo-astrindo.png') }}" alt="Logo" class="img-fluid me-2"
         style="width: 40px;"></img>SPMB Admin</div>

    <nav class="sidebar-menu">
        <a href="/dashboard" class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="/analisis" class="menu-link {{ request()->is('analisis') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Analisis
        </a>
        <a href="/prediksi" class="menu-link {{ request()->is('prediksi') ? 'active' : '' }}">
            <i class="fas fa-brain"></i> Prediksi
        </a>
        <a href="/laporan" class="menu-link {{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Manajemen Data
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="meta-item">
            <i class="fas fa-user-shield"></i>
            <span>Role: {{ auth()->user()->role->name ?? auth()->user()->role_name ?? 'Administrator' }}</span>
        </div>
        <div class="meta-item">
            <i class="fas fa-clock"></i>
            <span>
                Last login:
                {{ auth()->user()->last_login_at ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->format('d M Y H:i') : '-' }}
            </span>
        </div>
    </div>
</aside>

<nav class="top-navbar">
    <div class="header-search">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input
                type="text"
                id="globalSearchInput"
                class="form-control border-start-0"
                placeholder="Cari data secara real-time..."
                autocomplete="off"
            >
        </div>
    </div>

    <div class="dropdown">
        <div class="user-avatar" data-bs-toggle="dropdown">
            <i class="fas fa-user"></i>
        </div>
        <ul class="dropdown-menu dropdown-menu-end">
            <li class="dropdown-header text-center">
                <i class="fas fa-user me-2 text-primary"></i>{{ auth()->user()->name }}
            </li>
            <li class="dropdown-header text-center">
                <i class="fas fa-envelope me-2 text-secondary"></i>{{ auth()->user()->email }}
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-right-from-bracket me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">@yield('page-title')</h1>
        <p class="page-subtitle">@yield('page-subtitle')</p>
    </div>

    @yield('content')
    <div id="searchEmptyState" class="search-empty-state">
        Tidak ada data yang cocok dengan kata kunci pencarian.
    </div>

    <footer class="layout-footer">
        <div>
            &copy; {{ date('Y') }} Sistem Informasi SPMB SMK Astrindo Tegal.
            Dikembangkan untuk analisis tren dan prediksi penerimaan peserta didik baru.
        </div>
    </footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function() {
        const searchInput = document.getElementById('globalSearchInput');
        if (!searchInput) return;

        const mainContent = document.querySelector('.main-content');
        const emptyState = document.getElementById('searchEmptyState');

        const searchableSelector = [
            '.stat-card',
            '.card',
            'table tbody tr',
            '.list-group-item'
        ].join(', ');

        const getItems = () => Array.from(mainContent.querySelectorAll(searchableSelector))
            .filter(el => !el.closest('.layout-footer') && !el.closest('#searchEmptyState'));

        const normalize = (value) => (value || '').toLowerCase().trim();

        const filterRealtime = () => {
            const keyword = normalize(searchInput.value);
            const items = getItems();
            let visibleCount = 0;

            items.forEach((item) => {
                if (!keyword) {
                    item.style.display = '';
                    visibleCount++;
                    return;
                }

                const text = normalize(item.innerText);
                const isMatch = text.includes(keyword);
                item.style.display = isMatch ? '' : 'none';
                if (isMatch) visibleCount++;
            });

            if (!keyword) {
                emptyState.style.display = 'none';
                return;
            }

            emptyState.style.display = visibleCount > 0 ? 'none' : 'block';
        };

        searchInput.addEventListener('input', filterRealtime);
    })();
</script>
@stack('scripts')

</body>
</html>
