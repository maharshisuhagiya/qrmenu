<?php

namespace App\Repositories\Restaurant;

use App\Models\MainMenu;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


/**
 * Class MainMenuRepository.
 */
class MainMenuRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return MainMenu::class;
    }

    public function getRestaurantFoodCategoriesQuery($params)
    {
        return $this->model->when(isset($params['restaurant_id']), function ($query) use ($params) {
            $query->where('restaurant_id', $params['restaurant_id']);
        });
    }

    public function getRestaurantFoodCategories($params)
    {

        $table = $this->model->getTable();
        $restaurants = $this->getRestaurantFoodCategoriesQuery($params)->orderBy("$table.sort_order")->when(isset($params['filter']), function ($q) use ($params, $table) {
            $q->where(function ($query) use ($params, $table) {
                $query->where("$table.main_menu_name", 'like', '%' . $params['filter'] . '%');
                $query->orWhere("$table.id", '=',  $params['filter']);
            });
        })->select(
            "$table.id",
            "$table.restaurant_id",
            "$table.main_menu_name",
            "$table.main_menu_image",
            "$table.lang_main_menu_name",
            "$table.sort_order",
            "$table.created_at",

        )->get();

        return $restaurants;
    }

    public function getCountRestaurantFoodCategories($params)
    {

        $table = $this->model->getTable();
        $restaurants = $this->getRestaurantFoodCategoriesQuery($params)->count('id');

        return $restaurants;
    }
    public function getRestaurantCategories($params)
    {

        $table = $this->model->getTable();
        $restaurants = $this->getRestaurantFoodCategoriesQuery($params)->orderBy('id', 'desc')->select(
            "$table.id",
            "$table.restaurant_id",
            "$table.main_menu_name",
            "$table.main_menu_image",
            "$table.lang_main_menu_name",
            "$table.sort_order",
            "$table.created_at",

        );
        if (isset($params['recodes'])) {
            $restaurants = $restaurants->limit($params['recodes']);
        }
        $restaurants = $restaurants->get();


        return $restaurants;
    }
}
