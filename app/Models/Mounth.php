<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mounth extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['persian_state', 'symbol_name'];

    public function getPersianStateAttribute()
    {
        return __("MOUNTH_STATE_{$this->state}");
    }

    public function getSymbolNameAttribute()
    {
        return $this->symbol.$this->num;
    }
}
