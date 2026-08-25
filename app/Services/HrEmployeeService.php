<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HrEmployeeService
{
    public function fetchEmployees()
    {
        // config(), not env(): env() calls in application code return null
        // once config is cached (php artisan config:cache), which silently
        // breaks the daily sync in any environment that caches config.
        $response = Http::withToken(config('services.hr.token'))
            ->get(config('services.hr.url'));

        if (! $response->successful()) {
            throw new \Exception('Failed to fetch employees from HR system');
        }

        return $response->json();
    }
}
