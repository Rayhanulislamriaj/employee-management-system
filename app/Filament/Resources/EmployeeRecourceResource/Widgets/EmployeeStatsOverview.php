<?php

namespace App\Filament\Resources\EmployeeRecourceResource\Widgets;


use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', Employee::all()->count()),
            Stat::make('Total Department', Department::all()->count()),
            Stat::make('Total Admin User', User::all()->count()),
        ];


    }

}