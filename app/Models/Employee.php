<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'division_id',
        'district_id',
        'country_id',
        'department_id',
        'zip_code',
        'birth_date',
        'date_hired'
    ];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}