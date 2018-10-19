<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\ModelConvCategories;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ModelConversationsController extends Controller
{
    public function modelConversationMenuGet() {

        //Mockup of categories
        $categories = ModelConvCategories::OnlyActive()->where('subcategory_id', '=', 0)->get();

        return view('model_conversations.model_conversations_categories')
            ->with('categories', $categories);
    }

    public function categoryGet($id) {
        $categories = ModelConvCategories::OnlyActive()->where('subcategory_id', '=', $id)->get();

        return view('model_conversations.model_conversations_category')
            ->with('categories', $categories);
    }

    public function modelConversationsManagementGet() {
        $categories = ModelConvCategories::all();

        return view('model_conversations.model_conversations_management')->with('categories', $categories);
    }

    public function modelConversationsPlaylistGet() {

        return view('model_conversations.model_conversations_playlist');
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

            //przy zmianie zdiecia, trzeba usunać stare - trzeba dodać to
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
}
