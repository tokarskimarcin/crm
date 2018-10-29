<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelConvCategories extends Model
{
    protected $table = 'model_conv_categories';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * @param $query
     * This scope method indicates only active categories (with status 1)
     */
    public function scopeOnlyActive($query) {
        $query->where('status', '=', 1);
    }

    /**
     * @param $id
     * This method deletes playlist with its references
     */
    public static function deleteWithReferences($id) {
        ModelConvCategories::find($id)->delete();
        ModelConvItems::where('model_category_id', '=', $id)->update(['model_category_id' => null]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * This method changes status of category
     */
    public static function changeStatus($id) {
          $category = ModelConvCategories::find($id);
          //Error if there is no category
        if(!isset($category)) {
            throw new \Exception('Nie można znaleść podanej kategorii');
        }

        if($category->status == 1) {
           return ModelConvCategories::find($id)->update(['status' => '0']);
        }
        else {
           return ModelConvCategories::find($id)->update(['status' => '1']);
        }
    }
}
