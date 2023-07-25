<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mounth extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['brick_name', 'persian_state', 'symbol_name', 'color'];

    public function getBrickNameAttribute()
    {
        return __("BRICK_NAME_{$this->brick}");
    }

    public function getPersianStateAttribute()
    {
        return __("MOUNTH_STATE_{$this->state}");
    }

    public function getSymbolNameAttribute()
    {
        return $this->symbol.$this->num;
    }

    public function getColorAttribute()
    {
        return __("COLOR_FOR_MOUNTH_STATE_{$this->state}");
    }
}
