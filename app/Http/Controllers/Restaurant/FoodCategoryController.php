<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Support\Str;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\File;
use App\Http\Requests\Restaurant\FoodCategoryRequest;
use App\Models\Food;
use App\Repositories\Restaurant\FoodCategoryRepository;
use Illuminate\Http\UploadedFile;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $request = request();
        $user = auth()->user();
        $params = $request->only('par_page', 'sort', 'direction', 'filter', 'restaurant_id');
        $params['restaurant_id'] = $params['restaurant_id'] ?? $user->restaurant_id;
        $foodCategories = (new FoodCategoryRepository)->getRestaurantFoodCategories($params);
        return view('restaurant.food_categories.index', ['foodCategories' => $foodCategories]);
    }

    public function create()
    {
        return view('restaurant.food_categories.create');
    }

    public function store(FoodCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->only('category_name', 'main_menu', 'restaurant', 'restaurant_id', 'category_image', 'lang_category_name', 'category_description');
            $input['sort_order'] = FoodCategory::max('sort_order') + 1;
            FoodCategory::create($input);
            DB::commit();
            $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.food_categories.title')]));
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $request->session()->flash('Error', __('system.messages.operation_rejected'));
            return redirect()->back();
        }
        return redirect()->route('restaurant.food_categories.index');
    }

    public function show(FoodCategory $foodCategory)
    {
    }

    public function checkRestaurantIsValidFoodCategory($food_category_id, $user = null)
    {

        if (empty($user)) {
            $user = auth()->user();
        }

        $user->load(['restaurant.food_categories' => function ($q) use ($food_category_id) {
            $q->where('id', $food_category_id);
        }]);

        if (!isset($user->restaurant) || count($user->restaurant->food_categories) == 0) {
            $back = request()->get('back', route('restaurant.food_categories.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.food_categories.title')]));

            return $back;
        }
    }

    public function edit(FoodCategory $foodCategory)
    {
        if (($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null) {
            return redirect($redirect);
        }
        return view('restaurant.food_categories.edit', ['foodCategory' => $foodCategory]);
    }

    public function update(FoodCategoryRequest $request, FoodCategory $foodCategory)
    {

        if (($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null) {
            return redirect($redirect);
        }
        $input = $request->only('category_name', 'main_menu', 'category_image', 'lang_category_name', 'category_description');
        $foodCategory->fill($input)->save();

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.food_categories.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.food_categories.index'));
    }

    public function destroy(FoodCategory $foodCategory)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidFoodCategory($foodCategory->id)) != null) {
            return redirect($redirect);
        }
        $foodCategory->load('foods');
        $foods = $foodCategory->foods;
        $foodCategory->delete();
        $foods->loadCount('food_categories');
        foreach ($foods as $food) {
            if ($food->food_categories_count == 0) {
                $food->delete();
            }
        }
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.food_categories.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.food_categories.index'));
    }

    public function positionChange()
    {
        $request = request();
        $foodCategory = FoodCategory::where('id', $request->id)->update(['sort_order' => $request->index]);
        return true;
    }


    public static function getCurrentRestaurantAllFoodCategories()
    {
        $user = request()->user();
        $user->load(['restaurant.food_categories' => function ($q) {
            $q->orderBy('category_name', 'asc');
        }]);
        $food_categories = $user->restaurant->food_categories->mapWithKeys(function ($food_category, $key) {
            return [$food_category->id => $food_category->category_name];
        });
        return ['' => __('system.fields.select_Category')] + $food_categories->toarray();
    }

    public static function getCurrentRestaurantAllFoodTypes()
    {
        $user = request()->user();
        $user->load(['restaurant.food_types' => function ($q) {
            $q->orderBy('food_types_name', 'asc');
        }]);
        $food_types = $user->restaurant->food_types->mapWithKeys(function ($food_types, $key) {
            return [$food_types->id => $food_types->food_types_name];
        });

        return ['' => __('system.fields.select_Category')] + $food_types->toarray();
    }

    public static function getCurrentRestaurantAllMainMenu()
    {
        $user = request()->user();
        $user->load(['restaurant.main_menu' => function ($q) {
            $q->orderBy('main_menu_name', 'asc');
        }]);
        $main_menu = $user->restaurant->main_menu->mapWithKeys(function ($mainMenu, $key) {
            return [$mainMenu->id => $mainMenu->main_menu_name];
        });
        return ['' => __('system.fields.select_Main_Menu')] + $main_menu->toarray();
    }
}
