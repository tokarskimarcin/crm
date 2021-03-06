<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    /**
     * @param bool $onlyLoggedUser
     * @param bool/int $onlyOwnDepartmentId = id_dep_type of user
     * @return null/Collection
     * This method return info about playlist(user name, last name, playlist id, playlist name, img)
     */
    public static function getPlaylistInfo($onlyLoggedUser, $onlyOwnDepartmentId = false) {
        $info = null;
        if($onlyLoggedUser) {
            $info = ModelConvPlaylist::select(
                'first_name',
                'last_name',
                'model_conv_playlist.id',
                'model_conv_playlist.name',
                'users.id as user_id',
                'img'
            )
                ->join('users', 'model_conv_playlist.user_id', '=', 'users.id')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        }
        else {
            $info = ModelConvPlaylist::select(
                'first_name',
                'last_name',
                'model_conv_playlist.id',
                'model_conv_playlist.name',
                'users.id as user_id',
                'id_dep_type',
                'img'
            )
                ->join('users', 'model_conv_playlist.user_id', '=', 'users.id')
                ->join('department_info', 'users.department_info_id', '=', 'department_info.id');

            if($onlyOwnDepartmentId) {
                $info = $info->where('id_dep_type', '=', $onlyOwnDepartmentId)->get();
            }
            else {
                $info = $info->get();
            }
        }

        return $info;
    }
}
