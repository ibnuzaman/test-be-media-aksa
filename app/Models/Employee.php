<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Employee extends Model
{
    use HasFactory, HasUuids;
    // public $timestamps = false;

    protected $fillable = [
        'name',
        'image',
        'phone',
        'division_id',
        'position'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
