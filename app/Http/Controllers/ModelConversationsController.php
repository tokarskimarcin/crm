<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\ModelConvCategories;
use App\ModelConvItems;
use App\ModelConvPlaylist;
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

        $items = ModelConvItems::where('model_category_id', '=', $id)->OnlyActive()->get();

        return view('model_conversations.model_conversations_category')
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user)
            ->with('categories', $categories)
            ->with('items', $items);
    }

    public function modelConversationsManagementGet() {
        $user = Auth::user()->user_type_id;
        if(in_array($user, $this->adminPanelAccessArr)) { //Only approved user types can acces this management panel
            $items = ModelConvItems::all();
            $categories = ModelConvCategories::all();

            return view('model_conversations.model_conversations_management')
                ->with('categories', $categories)
                ->with('user', $user)
                ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
                ->with('items', $items);
        }
        else {
           return Redirect::back();
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

        $playlist = ModelConvPlaylist::select(
            'model_conv_playlist.name as playlist_name',
            'model_conv_items.name as conv_name',
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
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user);
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
     * This method permanently delete category
     */
    public function categoryDelete($id) {
        //Dodatkowo usunac wszystkie zależności!!
        try {
            ModelConvCategories::find($id)->delete();
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
                    $picture_name = 'category_' . $picture->getClientOriginalName() . '_' . date('Y-m-d');
                    $picture->storeAs('public',$picture_name);
                }
                else { //user didn't send picture, we assing default one.
                    $rnd = rand(1,5);
                    $picture_name = 'category_default_' . $rnd . '.jpeg';
                }
            }
            else {
                if(isset($picture)) { //user send picture
                    $picture_name = 'category_' . $picture->getClientOriginalName() . '_' . date('Y-m-d');
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
            $sound_name = 'category_' . $sound->getClientOriginalName() . '_' . date('Y-m-d');
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
