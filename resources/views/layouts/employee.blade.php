<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-base-200">

    <!-- GLOBAL NAVBAR (FULL WIDTH, FIXES ALIGNMENT) -->
    <div class="navbar bg-base-100 border-b sticky top-0 z-50">

        <!-- Burger -->
        <div class="flex-none lg:hidden">
            <label for="employee-drawer" class="btn btn-square btn-ghost">
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

    <!-- MAIN LAYOUT -->
    <div class="drawer lg:drawer-open">

        <input id="employee-drawer" type="checkbox" class="drawer-toggle" />

        <!-- CONTENT -->
        <div class="drawer-content p-6">
            {{ $slot }}
        </div>

        <!-- SIDEBAR -->
        <div class="drawer-side">
            <label for="employee-drawer" class="drawer-overlay"></label>

            <aside class="w-64 min-h-full bg-base-100 border-r">

                <ul class="menu p-4">

                    <li class="menu-title">
                        <span>My Account</span>
                    </li>

                    <li>
                        <a href="#my-information">
                            My Information
                        </a>
                    </li>

                    <li>
                        <a href="#my-qr">
                            My QR
                        </a>
                    </li>

                </ul>

            </aside>
        </div>

    </div>

@livewireScripts
</body>
</html>
