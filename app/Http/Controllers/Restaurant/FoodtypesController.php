<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Support\Str;
use App\Models\FoodTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\File;
use App\Http\Requests\Restaurant\FoodTypesRequest;
use App\Models\Food;
use App\Repositories\Restaurant\FoodTypesRepository;
use Illuminate\Http\UploadedFile;

class FoodtypesController extends Controller
{
    public function index()
    {
        $request = request();
        $user = auth()->user();
        $params = $request->only('par_page', 'sort', 'direction', 'filter', 'restaurant_id');
        $params['restaurant_id'] = $params['restaurant_id'] ?? $user->restaurant_id;
        $foodType = (new FoodTypesRepository)->getRestaurantFoodCategories($params);
        return view('restaurant.food_types.index', ['foodTypes' => $foodType]);
    }

    public function create()
    {
        return view('restaurant.food_types.create');
    }

    public function store(FoodTypesRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->only('food_types_name', 'restaurant', 'restaurant_id', 'food_types_image', 'lang_food_types_name');
            FoodTypes::create($input);
            DB::commit();
            $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.food_types.title')]));
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $request->session()->flash('Error', __('system.messages.operation_rejected'));
            return redirect()->back();
        }
        return redirect()->route('restaurant.food_types.index');
    }

    public function show(FoodTypes $foodType)
    {
    }

    public function checkRestaurantIsValidFoodTypes($food_types_id, $user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $user->load(['restaurant.food_types' => function ($q) use ($food_types_id) {
            $q->where('id', $food_types_id);
        }]);

        if (!isset($user->restaurant) || count($user->restaurant->food_categories) == 0) {
            $back = request()->get('back', route('restaurant.food_types.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.food_types.title')]));

            return $back;
        }
    }

    public function edit(FoodTypes $foodType)
    {
        if (($redirect = $this->checkRestaurantIsValidFoodTypes($foodType->id)) != null) {
            return redirect($redirect);
        }
        return view('restaurant.food_types.edit', ['foodTypes' => $foodType]);
    }

    public function update(FoodTypesRequest $request, FoodTypes $foodType)
    {
        if (($redirect = $this->checkRestaurantIsValidFoodTypes($foodType->id)) != null) {
            return redirect($redirect);
        }
        $input = $request->only('food_types_name', 'food_types_image', 'lang_food_types_name');
        $foodType->fill($input)->save();

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.food_types.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.food_types.index'));
    }

    public function destroy(FoodTypes $foodType)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidFoodTypes($foodType->id)) != null) {
            return redirect($redirect);
        }
        $foodType->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.food_types.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.food_types.index'));
    }

    public function positionChange()
    {
        $request = request();
        $foodType = FoodTypes::where('id', $request->id)->update(['sort_order' => $request->index]);
        return true;
    }


    public static function getCurrentRestaurantAllFoodCategories()
    {
        $user = request()->user();
        $user->load(['restaurant.food_types' => function ($q) {
            $q->orderBy('food_types_name', 'asc');
        }]);
        $food_categories = $user->restaurant->food_categories->mapWithKeys(function ($food_category, $key) {
            return [$food_category->id => $food_category->food_types_name];
        });
        return ['' => __('system.fields.select_Category')] + $food_categories->toarray();
    }
}
