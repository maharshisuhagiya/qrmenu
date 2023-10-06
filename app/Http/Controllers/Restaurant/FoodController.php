<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\FoodRequest;
use App\Models\Food;
use App\Repositories\Restaurant\FoodRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    public function index()
    {
        $request = request();
        $user = auth()->user();

        $params = $request->only('par_page', 'sort', 'direction', 'filter', 'food_category_id');
        $params['restaurant_id'] = $user->restaurant_id;
        $foods = (new FoodRepository())->getUserRestaurantFoods($params);
        return view('restaurant.foods.index', ['foods' => $foods]);
    }

    public function create()
    {
        return view('restaurant.foods.create');
    }

    public function store(FoodRequest $request)
    {
        DB::beginTransaction();
        $input = $request->all();
        if (isset($input['gallery_image'])) {
            $input['gallery_images'] = moveFile($input['gallery_image'], 'food_image');
        }
        createQniqueSessionAndDestoryOld('unique', 1);
        $categories = $request->categories;
        $food_types = $request->food_types;

        $food = Food::create($input);
        $inserts = [];
        foreach ($categories as $category) {
            $max = DB::table('food_food_category')->where('food_category_id', $category)->max('sort_order') + 1;
            $inserts[] = ['food_category_id' => $category, 'food_id' => $food->id, 'sort_order' => $max];
        }
        DB::table('food_food_category')->insert($inserts);

        $inserts = [];
        if($food_types)
        {
            foreach ($food_types as $food_type) {
                $inserts[] = ['food_types_id' => $food_type, 'food_id' => $food->id];
            }
            DB::table('food_food_types')->insert($inserts);
        }

        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.foods.title')]));

        DB::commit();
        return redirect()->route('restaurant.foods.index');
    }

    public function show(food $food)
    {
        if (($redirect = $this->checkRestaurantIsValidFood($food)) != null) {

            return redirect($redirect);
        }
        return view('restaurant.foods.view', ['food' => $food]);
    }

    public function edit(food $food)
    {
        if (($redirect = $this->checkRestaurantIsValidFood($food)) != null) {
            return redirect($redirect);
        }
        return view('restaurant.foods.edit', ['food' => $food]);
    }

    public function checkRestaurantIsValidFood($food)
    {
        $user = auth()->user();
        $params['restaurant_id'] = $user->restaurant_id;
        $params['id'] = $food->id;

        $food = (new FoodRepository())->getUserRestaurantFood($params);
        if (empty($food)) {
            $back = request()->get('back', route('restaurant.foods.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.foods.title')]));

            return $back;
        }
    }

    public function update(FoodRequest $request, food $food)
    {

        if (($redirect = $this->checkRestaurantIsValidFood($food)) != null) {
            return redirect($redirect);
        }

        $categories = $request->categories;
        $food_types = $request->food_types;

        $data = $request->only('restaurant_id','calories','allergy', 'food_category_id', 'name', 'description', 'price', 'preparation_time', 'is_featured', 'is_available', 'is_out_of_sold', 'ingredient', 'food_image', 'label_image', 'lang_name', 'lang_description', 'gallery_image','key','val');
        if (isset($data['gallery_image'])) {

            $data['gallery_images'] = moveFile($data['gallery_image'], 'food_image');
        }
        createQniqueSessionAndDestoryOld('unique', 1);
        $addData = array_diff($categories, $food->categories_ids);
        $deleted = array_diff($food->categories_ids, $categories);

        $food->fill($data)->save();
        $ids = DB::table('food_food_category')->where('food_id', $food->id)->whereIn('food_category_id', $deleted)->delete();
        $inserts = [];
        foreach ($addData as $category) {
            $inserts[] = ['food_category_id' => $category, 'food_id' => $food->id];
        }
        DB::table('food_food_category')->insert($inserts);

        if($food_types)
        {
            $addData = array_diff($food_types, $food->food_types_ids);
            $deleted = array_diff($food->food_types_ids, $food_types);
            $ids = DB::table('food_food_types')->where('food_id', $food->id)->whereIn('food_types_id', $deleted)->delete();
            $inserts = [];
            foreach ($addData as $category) {
                $inserts[] = ['food_types_id' => $category, 'food_id' => $food->id];
            }
            DB::table('food_food_types')->insert($inserts);
        }
        else
        {
            $ids = DB::table('food_food_types')->where('food_id', $food->id)->delete();
        }

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.foods.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.foods.index'));
    }

    public function destroy(food $food)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidFood($food)) != null) {
            return redirect($redirect);
        }

        $food->delete();

        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.foods.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.foods.index'));
    }

    public function positionChange()
    {
        $request = request();

        DB::table('food_food_category')->where('food_id', $request->food_id)->where('food_category_id', $request->category)->update(['sort_order' => $request->index]);
        return true;
    }

    public function uploadImage()
    {
        $request = request();
        $file = $request->file('file');
        $unique = $request->unique;
        $upload_name = uploadFile($file, $unique);
        $name =  basename($upload_name);
        $newFileName = substr($name, 0, (strrpos($name, ".")));
        return ['data' => ['name' => $name, "id" => $newFileName, 'upload_name' => $upload_name]];
    }
}
