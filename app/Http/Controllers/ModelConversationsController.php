<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\ModelConvCategories;
use App\ModelConvItems;
use App\ModelConvPlaylist;
use App\ModelConvPlaylistItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ModelConversationsController extends Controller
{

    private $adminPanelAccessArr = [3]; //Array of user_type_id who can access admin panel

    public function modelConversationMenuGet() {

        //Mockup of categories
        $user = Auth::user()->user_type_id;
        $categories = ModelConvCategories::OnlyActive()->where('subcategory_id', '=', 0)->get();

        return view('model_conversations.model_conversations_categories')
            ->with('categories', $categories)
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user);
    }

    public function categoryGet($id) {
        $user = Auth::user()->user_type_id;
        $categories = ModelConvCategories::OnlyActive()->where('subcategory_id', '=', $id)->get();
        $playlists = ModelConvPlaylist::all();
        $playlistItems = ModelConvPlaylistItem::select(
            'model_conv_playlist.name as name',
            'model_conv_playlist.id as id',
            'model_conv_playlist_items.item_id as item_id'
        )->join('model_conv_playlist', 'model_conv_playlist_items.playlist_id', '=', 'model_conv_playlist.id')
        ->get();

        $items = ModelConvItems::where('model_category_id', '=', $id)->OnlyActive()->get();
        $items->map(function($item) use($playlistItems) {
             $allItemsInPlaylists = $playlistItems->where('item_id', '=',$item->id)->pluck('name')->toArray();
             $item->playlists = $allItemsInPlaylists;
            return $item;
        });

        return view('model_conversations.model_conversations_category')
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user)
            ->with('categories', $categories)
            ->with('items', $items)
            ->with('playlists', $playlists);
    }

    public function modelConversationsManagementGet() {
        $user = Auth::user()->user_type_id;
        if(in_array($user, $this->adminPanelAccessArr)) { //Only approved user types can acces this management panel
            $items = ModelConvItems::all();
            $categories = ModelConvCategories::all();
            $playlists = ModelConvPlaylist::select(
                'first_name',
                'last_name',
                'model_conv_playlist.id',
                'model_conv_playlist.name',
                'users.id as user_id', 'img'
            )
                ->join('users', 'model_conv_playlist.user_id', '=', 'users.id')
                ->get();

            $playlistItems = ModelConvPlaylistItem::all();

            return view('model_conversations.model_conversations_management')
                ->with('categories', $categories)
                ->with('user', $user)
                ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
                ->with('items', $items)
                ->with('playlists', $playlists)
                ->with('playlistItems', $playlistItems);
        }
        else {
           return Redirect::back();
        }

    }

    /**
     * @param Request $request
     * @return mixed
     * This method assigns item to playlist
     */
    public function modelConversationCategoryChangePlaylist(Request $request) {
        $itemId = $request->id;
        $newPlaylist = $request->playlist;
        $allPlaylistItems = ModelConvPlaylistItem::where('playlist_id', '=', $newPlaylist)->get();
        $flag = true;
        foreach($allPlaylistItems as $item) {
            if($item->id == $itemId || $newPlaylist == 0) { //this item exist in this playlist
                $flag = false;
            }
        }

        if($flag) {
            $playlist_item = new ModelConvPlaylistItem();
            $playlist_item->playlist_id = $newPlaylist;
            $playlist_item->item_id = $itemId;
            $order = $this->generatateOrder($newPlaylist);
            $playlist_item->order = $order;
            $playlist_item->save();
        }

        return Redirect::back();
    }

    /**
     * @param $playlist_id
     * @return int
     * This method return last order number
     */
    private function generatateOrder($playlist_id) {

        $playlist_last_order_item = ModelConvPlaylistItem::where('playlist_id', '=', $playlist_id)->orderBy('order')->get();
//        dd($playlist_last_order_item->last());
        $lastItem = null;
        if($playlist_last_order_item) {
            $lastItem = $playlist_last_order_item->last();
        }
        if($lastItem) {
            return $lastItem->order + 1;
        }
        else { //first element in playlist
            return 1;
        }

    }

    public function modelConversationsPlaylistGet() {
        $user = Auth::user()->user_type_id;

        $playlistCategories = ModelConvPlaylist::all();

        return view('model_conversations.model_conversations_playlist_categories')
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('playlistCategories', $playlistCategories)
            ->with('user', $user);
    }

    public function playlistGet($id) {
        $user = Auth::user()->user_type_id;

        $playlistObject = ModelConvPlaylist::find($id);

        $playlist = ModelConvPlaylist::select(
            'model_conv_playlist.name as playlist_name',
            'model_conv_items.name as conv_name',
            'model_conv_items.file_name',
            'model_conv_playlist.img as playlist_img',
            'model_conv_playlist.id as id',
            'order',
            'trainer',
            'gift',
            'client'
        )
            ->join('model_conv_playlist_items', 'model_conv_playlist.id', '=', 'model_conv_playlist_items.playlist_id')
            ->join('model_conv_items', 'model_conv_playlist_items.item_id', '=', 'model_conv_items.id')
            ->where('model_conv_items.status', '=', 1)
            ->where('model_conv_playlist.id', '=', $id)
            ->orderBy('order')
            ->get();

//        dd($playlist);

        return view('model_conversations.model_conversations_playlist')
            ->with('playlist', $playlist)
            ->with('playlistObject', $playlistObject)
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user);
    }

    public function modelConversationsPlaylistPost(Request $request) {
        $toAdd = $request->toAdd;

        $picture = $request->file('picture');
        $picture_name = null;
        $id = $request->id;

        if($toAdd == 1) {
            if(isset($picture)) { //user send picture
                $picture_name = 'playlist_' . date('Y-m-d') . '_' . $picture->getClientOriginalName();
                $picture->storeAs('public',$picture_name);
            }
            else { //user didn't send picture, we assing default one.
                $rnd = rand(1,5);
                $picture_name = 'playlist_default_' . $rnd . '.jpeg';
            }
        }
        else {
            if(isset($picture)) { //user send picture
                $picture_name = 'playlist_' . date('Y-m-d') . '_' . $picture->getClientOriginalName();
                $picture->storeAs('public',$picture_name);
            }
        }

        $name = $request->name;
        $playlist = null;
        if($toAdd == 1) {
            $playlist = new ModelConvPlaylist();
        }
        else {
            $playlist = ModelConvPlaylist::find($id);
        }


        if($toAdd == 0) {
            if(isset($picture)) {
                $playlist->img = $picture_name;
            }
        }
        else {
            $playlist->img = $picture_name;
        }

        $playlist->name = $name;
        $playlist->user_id = Auth::user()->id;
        try {
            $playlist->save();
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }

        return Redirect::back();
    }


    /**
     * @param $id
     * @return mixed
     * THis ajax returns info about playlist items for management
     */
    public function managementPlaylistGet($id) {
        $info = ModelConvPlaylistItem::getPlaylistInfo($id);
        return $info;
    }

    public function managementPlaylistDelete($id) {
       return ModelConvPlaylist::safeDelete($id);
    }

    /**
     * @param $id
     * This method changes category status.
     */
    public function categoryPut($id) {
        try {
            ModelConvCategories::changeStatus($id);
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }
    }

    /**
     * @param $id
     * This method permanently delete category and remove its references
     */
    public function categoryDelete($id) {
        try {
            ModelConvCategories::deleteWithReferences($id);
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }

    }

    /**
     * @param Request $request
     * @return mixed
     * This method changes picture of category or adds new category
     */
    public function categoryPost(Request $request) {
        $toAdd = $request->toAdd; //This varible defines whether user edit category or add new one 1 - add, 0 - edit

            //przy zmianie zdiecia, trzeba usunać stare - trzeba dodać to i przypisanie do danej kategori tego nowego zdiecia
            $id = $request->id;
            $picture = $request->file('picture');
            $picture_name = null;

            if($toAdd == 1) {
                if(isset($picture)) { //user send picture
                    $picture_name = 'category_' . date('Y-m-d') . '_' . $picture->getClientOriginalName();
                    $picture->storeAs('public',$picture_name);
                }
                else { //user didn't send picture, we assing default one.
                    $rnd = rand(1,5);
                    $picture_name = 'category_default_' . $rnd . '.jpeg';
                }
            }
            else {
                if(isset($picture)) { //user send picture
                    $picture_name = 'category_' . date('Y-m-d') . '_' . $picture->getClientOriginalName();
                    $picture->storeAs('public',$picture_name);
                }
            }

            //trzeba dodać ograniczenie na wielkosc zdiecia i rozszerzenie.
            $name = $request->name;

            $status = $request->status;
            $subcategory = $request->subcategory;

            if($toAdd == 1) {
                $category = new ModelConvCategories();
            }
            else {
                $category = ModelConvCategories::find($id);
            }

            $category->subcategory_id = $subcategory;
            $category->name = $name;
            if($toAdd == 0) {
                if(isset($picture)) {
                    $category->img = $picture_name;
                }
            }
            else {
                $category->img = $picture_name;
            }

            $category->status = $status;
            try {
                $category->save();
            }
            catch(\Exception $error) {
                new ActivityRecorder($error, 1, 6);
            }
        return Redirect::back();
    }

    /**
     * @param $id
     * This method changes item status
     */
    public function itemsPut($id) {
        try {
            ModelConvItems::changeStatus($id);
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }
    }

    /**
     * @param $id
     * This method permanently delete item
     */
    public function itemsDelete($id) {
        //DOdatkowo usunąć wszystkie zależności!
        try {
            ModelConvItems::find($id)->delete();
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }

    }

    public function itemsPost(Request $request) {
        $toAdd = $request->toAdd;

        //we are adding new item
        $name = $request->name;
        $status = $request->status;
        $trainer = $request->trainer;
        $gift = $request->gift;
        $client = $request->client;
        $category = $request->category_id;

        $sound = $request->file('sound');
        $sound_name = null;
        if(isset($sound)) {
            $sound_name = 'category_' . date('Y-m-d') . '_' . $sound->getClientOriginalName();
            $sound->storeAs('public',$sound_name);
        }

        $newItem = null;
        if($toAdd == 1) { //creating new
            $newItem = new ModelConvItems();
        }
        else {
            $newItem = ModelConvItems::find($request->id);
        }

        if($toAdd == 0) { //editing
            if(isset($sound)) { //There is new file
                $newItem->file_name = $sound_name;
            }
        }
        else {
            $newItem->file_name = $sound_name;
        }

        $newItem->name = $name;
        $newItem->trainer = $trainer;
        $newItem->gift = $gift;
        $newItem->client = $client;
        $newItem->model_category_id = $category;
        $user_id = Auth::user()->id;
        $newItem->user_id = $user_id;
        $newItem->status = $status;
        try {
            $newItem->save();
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }

        return Redirect::back();
    }
}
