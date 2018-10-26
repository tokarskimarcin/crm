<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelConvPlaylist extends Model
{
    //
    protected $table = 'model_conv_playlist';
    public $timestamps = false;

    public static function deleteWithReferences($id) {
        ModelConvPlaylistItem::where('playlist_id', '=', $id)->delete();
        ModelConvPlaylist::find($id)->delete();
    }
}
