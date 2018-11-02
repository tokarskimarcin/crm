<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelConvPlaylist extends Model
{
    //
    protected $table = 'model_conv_playlist';
    public $timestamps = false;

    /**
     * @param $id
     * This method deletes playlist with its references
     */
    public static function deleteWithReferences($id) {
        ModelConvPlaylistItem::where('playlist_id', '=', $id)->delete();
        ModelConvPlaylist::find($id)->delete();
    }
}
