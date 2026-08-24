<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HrEmployeeService
{
    public function fetchEmployees()
    {
        $response = Http::withToken(env('HR_API_TOKEN'))
            ->get(env('HR_API_URL'));

        if (! $response->successful()) {
            throw new \Exception('Failed to fetch employees from HR system');
        }

        return $response->json();
    }
}
