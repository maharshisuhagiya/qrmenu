<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodTypes extends Model
{
    use HasFactory, Sortable;

    public $table = 'food_types';

    protected $fillable = [
        'restaurant_id',
        'food_types_name',
        'food_types_image',
        'lang_food_types_name',
    ];

    protected $casts = [
        'restaurant_id' => "integer",
        'food_types_name' => "string",
        'food_types_image' => "string",
        'lang_food_types_name' => "array",
    ];
    public $sortable = [
        'id',
        'food_types_name',
        'created_at',
    ];

    public function getFoodTypesImageNameAttribute()
    {
        return ucfirst(substr($this->food_types_name, 0, 1));
    }

    public function getFoodTypesImageUrlAttribute()
    {
        return getFileUrl($this->attributes['food_types_image']);
    }

    public function setFoodTypesImageAttribute($value)
    {
        if ($value != null) {
            if (gettype($value) == 'string') {
                $this->attributes['food_types_image'] = $value;
            } else {
                $this->attributes['food_types_image'] = uploadFile($value, 'food_types_image');
            }
        }
    }
    public function setLangFoodTypesNameAttribute($value)
    {
        if (gettype($value) == 'array') {
            $this->attributes['lang_food_types_name'] = json_encode($value);
        }
    }

    public function getLocalLangNameAttribute()
    {
        if (app()->getLocale() == 'en') {
            return $this->food_types_name;
        } else {
            return $this->lang_food_types_name[app()->getLocale()] ?? $this->food_types_name;
        }
    }
    public function getNameAttribute()
    {
        return $this->food_types_name;
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('restaurant.food_categories.edit',  $this->id);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
