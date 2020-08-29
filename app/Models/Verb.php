<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verb extends Model
{
    protected $fillable = [
        'verb',
        'class',
        'function',
        'parameters'
    ];
    
    public $timestamps = false;

    public function setVerbAttribute($value)
    {
        $this->attributes['verb'] = '|' . implode('|', explode(',', $value)) . '|';
    }
}
