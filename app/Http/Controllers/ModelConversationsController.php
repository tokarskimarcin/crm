<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\ModelConvCategories;
use App\ModelConvItems;
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

        return view('model_conversations.model_conversations_playlist')
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
        $toAdd = $request->toAdd;

        if($toAdd == 0) { //case when we are only adding new picture

            //przy zmianie zdiecia, trzeba usunać stare - trzeba dodać to i przypisanie do danej kategori tego nowego zdiecia
            $id = $request->id;
            $picture = $request->file('picture');
//            $picture_ext = $picture->getClientOriginalExtension();
            $picture_name = $picture->getClientOriginalName();

            $picture->storeAs('public',$picture_name);

        }
        else { //case when we are adding new category

            //trzeba dodać ograniczenie na wielkosc zdiecia i rozszerzenie.
            $name = $request->name;
            $picture = $request->file('picture');
            $picture_name = null;
            if($picture) {
                $picture_name = $picture->getClientOriginalName();
                $picture->storeAs('public',$picture_name);
            }
            else {
                $picture_name = 'chmury1.jpeg';
            }

            $status = $request->status;
            $subcategory = $request->subcategory;

            $category = new ModelConvCategories();
            $category->subcategory_id = $subcategory;
            $category->name = $name;
            $category->img = $picture_name;
            $category->status = $status;
            $category->save();
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
//        dd('2');
        $toAdd = $request->toAdd;

        if($toAdd == 0) { //we are editing

        }
        else { //we are adding new item
            $name = $request->name;
            $status = $request->status;
            $trainer = $request->trainer;
            $gift = $request->gift;
            $client = $request->client;
            $category = $request->category_id;

            $sound = $request->file('sound');
            $sound_name = $sound->getClientOriginalName();
            $sound->storeAs('public',$sound_name);

            $newItem = new ModelConvItems();
            $newItem->file_name = $sound_name;
            $newItem->name = $name;
            $newItem->trainer = $trainer;
            $newItem->gift = $gift;
            $newItem->client = $client;
            $newItem->model_category_id = $category;
            $user_id = Auth::user()->id;
            $newItem->status = $status;
            try {
                $newItem->save();
            }
            catch(\Exception $error) {
                new ActivityRecorder($error, 1, 6);
            }

        }
        return Redirect::back();
    }
}
