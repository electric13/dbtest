<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'material', 'thickness','color', 'actual', 'mtype'
    ];

}
