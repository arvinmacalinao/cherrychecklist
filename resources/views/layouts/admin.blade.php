<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-base-200">

    <!-- 🔥 GLOBAL NAVBAR (FULL WIDTH, FIXES ALIGNMENT) -->
    <div class="navbar bg-base-100 border-b sticky top-0 z-50">

        <!-- Burger -->
        <div class="flex-none lg:hidden">
            <label for="admin-drawer" class="btn btn-square btn-ghost">
                ☰
            </label>
        </div>

        <!-- Title -->
        <div class="flex-1 px-2">
            <span class="text-lg font-semibold">
                Device Exit System
            </span>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost">
                {{ auth()->user()->name }}
            </label>

            <ul tabindex="0"
                class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-40">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- 🔥 MAIN LAYOUT -->
    <div class="drawer lg:drawer-open">

        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

        <!-- CONTENT -->
        <div class="drawer-content p-6">
            {{ $slot }}
        </div>

        <!-- SIDEBAR -->
        <div class="drawer-side">
            <label for="admin-drawer" class="drawer-overlay"></label>

            <aside class="w-64 min-h-full bg-base-100 border-r">

                <!-- REMOVE HEADER (fix alignment issue) -->
                <ul class="menu p-4">

                    <li class="menu-title">
                        <span>Admin</span>
                    </li>

                    <li>
                        <a href="/admin/users"
                           class="{{ request()->is('admin/users') ? 'active' : '' }}">
                            Users
                        </a>
                    </li>

                    <li>
                        <a href="/admin/devices"
                           class="{{ request()->is('admin/devices') ? 'active' : '' }}">
                            Devices
                        </a>
                    </li>

                    <li>
                        <a href="/admin/logs"
                           class="{{ request()->is('admin/logs') ? 'active' : '' }}">
                            Exit Logs
                        </a>
                    </li>

                </ul>

            </aside>
        </div>

    </div>

@livewireScripts
</body>
</html>
