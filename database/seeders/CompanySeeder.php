<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [

            'Blue Diamond Global Ventures Corporation',
            'Boqueria Prime Corp.',
            'Casa Nuova Incorporated',
            'Cherry Peak Lifestyle Inc.',
            'Cigraal Incorporated - The Cedar Room',
            'Cinco Siete Ventures Inc.',
            'Cork Elite',
            'Cork Wine Bar And Shop Inc.',
            'Cosmic Technologies Incorporated',
            'Fimobile Technology Incorporated',
            'Gizmobile Ventures Incorproated',
            'GMM Sky Ventures Inc.',
            'Harlan Fractional Estate Incorporated',
            'Kinetics Advance Physiotherapy',
            'Kosmos Technomobile Inc.',
            'Moksha Ventures Inc.',
            'MSN Foundation Inc.',
            'MSN Lifestyle Concepts Incorporated',
            'Omega Magnus Electronics Incorporated',
            'Optimus Mobile Technologies Inc.',
            'Paragon Communications Corp',
            'Penthouse',
            'Pure Living Wellness International, Inc.',
            'Quantum Mobile Gears Corporation',
            'Radiant 88 Global Trading Corporation',
            'Rigor Wellness Center Inc.',
            'Starway Piping Technology Inc.',
            'Steadfast Motors Corp.',
            'Tekila 888 Inc.',
            'Tekila Bar And Grill Inc.',
            'Versa Tile Customer Care Solutions Inc.',
            'Vino Enoteca Manila Corp.',
            'Xionz Tech Inc.',
            'CORK WESTIN',

        ];

        foreach ($companies as $company) {

            Company::updateOrCreate(
                ['name' => $company],
            );
        }
    }
}
