<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use App\Models\User;
use App\Repositories\Restaurant\FoodCategoryRepository;
use App\Repositories\Restaurant\FoodRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Repositories\Restaurant\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {

        $user = auth()->user();
        $restaurant = $user->restaurant;
        $params = [];
        if ($user->user_type != User::USER_TYPE_ADMIN)
            $params['user_id'] = $user->id;
        $count['restaurants_count'] = (new RestaurantRepository())->getCountRestaurants($params);
        $count['users_count'] =  (new UserRepository())->getRestaurantUsersCount(['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $count['categories_count'] =  (new FoodCategoryRepository)->getCountRestaurantFoodCategories(['restaurant_id' => $restaurant->id]);
        $count['foods_count'] =  (new FoodRepository())->getUserRestaurantFoodCount(['restaurant_id' => $restaurant->id]);

        $count['restaurants'] = (new RestaurantRepository())->getUserRestaurantsDetails(['user_id' => $user->id, 'latest' => 1, 'recodes' => 6]);
        $count['users'] =  (new UserRepository())->getRestaurantUsersRecodes(['restaurant_id' => $restaurant->id, 'user_id' => $user->id,  'recodes' => 6]);
        $count['categories'] =  (new FoodCategoryRepository)->getRestaurantCategories(['restaurant_id' => $restaurant->id, 'recodes' => 6]);
        $count['foods'] =  (new FoodRepository)->getUserRestaurantFoodsCustome(['restaurant_id' => $restaurant->id, 'recodes' => 6]);
        return view('home', $count);
    }

    public static function getCurrentUsersAllRestaurants()
    {
        $user = auth()->user();
        $params = [];
        if ($user->user_type != User::USER_TYPE_ADMIN) {
            $params['user_id'] = $user->id;
        }
        return (new RestaurantRepository())->getUserRestaurantsDetails($params);
    }



    public function globalSearch()
    {
        $request = request();
        $search = [];
        if (strlen($request->search) > 2) {
            $search = globalSearch($request->search, $request->user());
        }
        return view('layouts.search')->with('search', $search);
    }
}
