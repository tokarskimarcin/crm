<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotels';
    public $timestamps = false;

    //This method remove hotel permanently with all its references
    public static function removeHotelPermanently($id) {
        ClientRouteInfo::where('hotel_id', '=', $id)->update(['hotel_id' => null]);
        HotelsContacts::where('hotel_id', '=', $id)->delete();
        Hotel::find($id)->delete();
    }
}
