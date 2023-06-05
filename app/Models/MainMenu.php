<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MainMenu extends Model
{
    use HasFactory, Sortable;

    public $table = 'main_menus';

    protected $fillable = [
        'restaurant_id',
        'main_menu_name',
        'main_menu_image',
        'main_menu_description',
        'lang_main_menu_name',
        'sort_order',
    ];

    protected $casts = [
        'restaurant_id' => "integer",
        'sort_order' => "integer",
        'main_menu_name' => "string",
        'main_menu_image' => "string",
        'lang_main_menu_name' => "array",
    ];
    public $sortable = [
        'id',
        'main_menu_name',
        'created_at',
        'sort_order',
    ];

    public function getMainMenuImageNameAttribute()
    {
        return ucfirst(substr($this->main_menu_name, 0, 1));
    }

    public function getMainMenuImageUrlAttribute()
    {
        return getFileUrl($this->attributes['main_menu_image']);
    }

    public function setMainMenuImageAttribute($value)
    {
        if ($value != null) {
            if (gettype($value) == 'string') {
                $this->attributes['main_menu_image'] = $value;
            } else {
                $this->attributes['main_menu_image'] = uploadFile($value, 'main_menu_image');
            }
        }
    }
    public function setLangMainMenuNameAttribute($value)
    {
        if (gettype($value) == 'array') {
            $this->attributes['lang_main_menu_name'] = json_encode($value);
        }
    }

    public function getLocalLangNameAttribute()
    {
        if (app()->getLocale() == 'en') {
            return $this->main_menu_name;
        } else {
            return $this->lang_main_menu_name[app()->getLocale()] ?? $this->main_menu_name;
        }
    }
    public function getNameAttribute()
    {
        return $this->main_menu_name;
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
