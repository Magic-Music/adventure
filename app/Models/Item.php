<?php

namespace App\Models;
use App\Models\Location;
use App\Models\Character;
use App\Models\Verb;
use App\Game\Items;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;
    protected $appends = ['currentLocation'];
    
    protected $fillable = [
        'slug',
        'full_description',
        'short_description',
        'article',
        'other_nouns',
        'location',
        'character',
        'takable',
        'describe_look',
    ];
    
    public function getCurrentLocationAttribute()
    {
        return Items::location($this->slug);
    }
    
    public function getArticleAttribute($article)
    {
        return $article ?: 'a';
    }
    
    public function getShortDescriptionWithArticleAttribute()
    {
        return $this->article . " " . $this->short_description;
    }
    
    public function getShortDescriptionWithCapitalisedArticleAttribute()
    {
        return ucfirst($this->article) . " " . $this->short_description;
    }

    public function setOtherNounsAttribute($value)
    {
        $this->attributes['other_nouns'] = '|' . implode('|', explode(',', $value)) . '|';
    }
    
    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function character()
    {
        return $this->hasOne(Character::class);
    }

    public function verbs()
    {
        return $this->hasMany(Verb::class);
    }
}
