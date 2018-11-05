<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelConvPlaylistItem extends Model
{
    protected $table = 'model_conv_playlist_items';
    public $timestamps = false;

    protected $fillable = ['playlist_order'];

    public static function smartDelete($id) {
        //Nalezy dodac funkcje uaktualniającą kolejność playlisty
        $playlist = ModelConvPlaylistItem::find($id);
        $playlist_id = $playlist->playlist_id;
        $playlist->delete();
        ModelConvPlaylistItem::updateOrder($playlist_id);
    }

    /**
     * @param $playlist_id
     * This method adjust order of playlist
     */
    public static function updateOrder($playlist_id) {
        $playlist_items = ModelConvPlaylistItem::where('playlist_id', '=', $playlist_id)->orderBy('playlist_order')->get();

        $i = 1;
        foreach($playlist_items as $item) {
            $item->update(['playlist_order' => $i]);
            $i++;
        }
    }

    /**
     * @param $id
     * This method returns collection of playlist items
     */
    public static function getPlaylistInfo($id) {
        $info = ModelConvPlaylistItem::select(
            'model_conv_items.id as item_id',
            'model_conv_items.file_name as item_file_name',
            'model_conv_items.name as item_name',
            'model_conv_items.trainer as item_trainer',
            'model_conv_items.gift as item_gift',
            'model_conv_items.client as item_client',
            'playlist_order',
            'model_conv_playlist_items.id as id'
        )
            ->join('model_conv_items', 'model_conv_playlist_items.item_id', '=', 'model_conv_items.id')
            ->join('model_conv_playlist', 'model_conv_playlist.id', '=', 'model_conv_playlist_items.playlist_id')
            ->where('model_conv_playlist.id', '=', $id)
            ->where('model_conv_items.status', '=', 1) //only active
            ->orderBy('playlist_order')
            ->get();

        return $info;
    }
}
