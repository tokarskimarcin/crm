<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    /**
     * @param bool $onlyLoggedUser
     * @return null/Collection
     * This method returns info about playlist items
     */
    public static function getPlaylistItemsInfo($onlyLoggedUser = false) {
        $items = null;
        if($onlyLoggedUser) {
            $items = ModelConvItems::select(
                'model_conv_items.id as id',
                'file_name',
                'model_conv_items.name as name',
                'model_conv_items.trainer as trainer',
                'gift',
                'client',
                'model_category_id',
                'user_id',
                'model_conv_items.status as status',
                'first_name',
                'last_name'
            )
                ->join('users', 'model_conv_items.user_id', '=', 'users.id')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        }
        else {
            $items = ModelConvItems::select(
                'model_conv_items.id as id',
                'file_name',
                'model_conv_items.name as name',
                'model_conv_items.trainer as trainer',
                'gift',
                'client',
                'model_category_id',
                'user_id',
                'model_conv_items.status as status',
                'first_name',
                'last_name'
            )
                ->join('users', 'model_conv_items.user_id', '=', 'users.id')
                ->get();
        }



        return $items;
    }
}
