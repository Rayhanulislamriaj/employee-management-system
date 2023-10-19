<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeRecourceResource\Widgets\EmployeeStatsOverview;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Country;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('country_id')
                            ->label('Country')
                            ->options(Country::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('division_id', null)),
                        Select::make('division_id')
                            ->label('Division')
                            ->options(function (callable $get) {
                                $country = Country::find($get('country_id'));
                                if (!$country) {
                                    return Division::all()->pluck('name', 'id');
                                }
                                return $country->divisions->pluck('name', 'id');
                            })
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('district_id', null)),
                        Select::make('district_id')
                            ->label('District')
                            ->options(function (callable $get) {
                                $division = Division::find($get('division_id'));
                                if (!$division) {
                                    return District::all()->pluck('name', 'id');
                                }
                                return $division->districts->pluck('name', 'id');
                            })
                            ->reactive(),
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(75),
                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(75),
                        TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('zip_code')
                            ->required()
                            ->maxLength(4),
                        DatePicker::make('birth_date')
                            ->required(),
                        DatePicker::make('date_hired')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('date_hired')->date(),
                TextColumn::make('created_at')
                    ->date()
            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}