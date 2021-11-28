<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'itemname', 'group_id','color'
    ];

    public function group() {
        return $this->belongsTo(ItemGroup::class, 'group_id');
    }
}
