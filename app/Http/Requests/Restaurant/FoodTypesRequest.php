<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class FoodTypesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $request = request();
        $langs = getAllCurrentRestaruentLanguages();
        $rules = [
            'food_types_image' => ['nullable', 'max:50000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'food_types_name' => ['required', 'string', 'max:255', 'min:2'],

            // 'restaurant_id' => ['required'],
            'restaurant_id' => ['required', 'unique:food_types,restaurant_id,null,id,food_types_name,' . $request->food_types_name],
        ];


        $request->request->add(['restaurant' => array_filter($request->restaurant ?? [])]);
        // dd($request->all());
        if (isset($this->food_type)) {
            $old_id = $this->food_type->id;
            unset($rules['restaurant.*']);
            unset($rules['restaurant']);
            $rules['restaurant_id'] = ['required', 'unique:food_types,restaurant_id,' . $old_id . ',id,food_types_name,' . $request->food_types_name];
            if (count($langs) > 0) {
                $rules['lang_food_types_name.*'] = ['required',];
                $rules['restaurant_ids.*'] = ['required'];

                foreach ($langs as $key => $lang) {
                    $rules['lang_food_types_name.' . $key] = ['string', 'max:255', 'min:2'];
                    $rules['restaurant_ids.' . $key] = ["unique:food_types,restaurant_id,$old_id,id,lang_food_types_name->$key," .  str_replace("%", "%%", $request->lang_food_types_name[$key])];
                }
            }
        } elseif (count($langs) > 0) {
            $rules['lang_food_types_name.*'] = ['required',];
            $rules['restaurant_ids.*'] = ['required'];

            foreach ($langs as $key => $lang) {
                $rules['lang_food_types_name.' . $key] = ['string', 'max:255', 'min:2'];
                // dd("unique:food_types,restaurant_id,null,id,lang_food_types_name->$key," . $request->lang_food_types_name[$key]);
                $rules['restaurant_ids.' . $key] = ["unique:food_types,restaurant_id,null,id,lang_food_types_name->$key," . str_replace("%", "%%", $request->lang_food_types_name[$key])];
            }
        }
        // DB::enableQueryLog();
        return $rules;
    }

    public function messages()
    {

        $langs = getAllCurrentRestaruentLanguages();
        $request = request();
        $lbl_food_types_image = strtolower(__('system.fields.food_types_image'));
        $lbl_food_types_name = strtolower(__('system.fields.food_types_name'));
        $messages = [
            "food_types_image.max" => __('validation.gt.file', ['attribute' => $lbl_food_types_image, 'value' => 50000]),
            "food_types_image.image" => __('validation.enum', ['attribute' => $lbl_food_types_image]),
            "food_types_image.mimes" => __('validation.enum', ['attribute' => $lbl_food_types_image]),

            "food_types_name.required" => __('validation.required', ['attribute' => $lbl_food_types_name]),
            "food_types_name.string" => __('validation.custom.invalid', ['attribute' => $lbl_food_types_name]),
            "food_types_name.max" => __('validation.custom.invalid', ['attribute' => $lbl_food_types_name]),

            "restaurant_id.required" => __('validation.custom.select_required', ['attribute' => 'restaurant']),
            "restaurant_id.unique" => __('validation.unique', ['name' => $request->food_types_name, 'attribute' => $lbl_food_types_name]),
        ];
        if (count($langs) > 0) {

            foreach ($langs as $key => $lang) {
                $messages["lang_food_types_name.$key.string"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["lang_food_types_name.$key.max"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["lang_food_types_name.$key.min"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["restaurant_ids.$key.unique"]  = __('validation.unique', ['name' => $request->lang_food_types_name[$key], 'attribute' => $lbl_food_types_name . " " . strtolower($lang)]);
            }
        }
        return $messages;
    }
}
