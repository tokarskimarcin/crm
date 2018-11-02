<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelConvItems extends Model
{
    protected $table = 'model_conv_items';
    public $timestamps = false;
    protected $guarded = [];

    //status 0 nieaktywne, status 1 aktywne
    //temp 0 - permanentne rozmowy, temp 1 - tymczasowe rozmowy

    public static function scopeOnlyActive($query) {
       return $query->where('status', '=', 1);
    }

    /**
     * @param $id
     * This method deletes items with its references
     */
    public static function deleteWithReferences($id) {
        ModelConvItems::find($id)->delete();
        $playlist_items = ModelConvPlaylistItem::where('item_id', '=', $id)->get();

        //This part adjust order as deleting files
        foreach($playlist_items as $item) {
            $playlist_id = $item->playlist_id;
            $item->delete();
            ModelConvPlaylistItem::updateOrder($playlist_id);
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * This method changes status of category
     */
    public static function changeStatus($id) {
        $item = ModelConvItems::find($id);
        //Error if there is no category
        if(!isset($item)) {
            throw new \Exception('Nie można znaleść podanej rozmowy');
        }

        if($item->status == 1) {
            return ModelConvItems::find($id)->update(['status' => '0']);
        }
        else {
            return ModelConvItems::find($id)->update(['status' => '1']);
        }
    }
}
