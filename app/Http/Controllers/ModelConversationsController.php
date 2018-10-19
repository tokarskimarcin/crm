<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\ModelConvCategories;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelConversationsController extends Controller
{
    public function modelConversationMenuGet() {

        //Mockup of categories
        $categories = ModelConvCategories::OnlyActive()->get();

        return view('model_conversations.model_conversations_categories')
            ->with('categories', $categories);
    }

    public function categoryGet($id) {


        return view('model_conversations.model_conversations_category');
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

    public function categoryPost(Request $request) {
        $toAdd = $request->toAdd;

        if($toAdd == 0) { //case when we are only adding new picture
            $id = $request->id;
            $picture = $request->file('picture');
//            $picture_ext = $picture->getClientOriginalExtension();
            $picture_name = $picture->getClientOriginalName();

            $picture->storeAs('public',$picture_name);

        }
        else { //case when we are adding new category

        }
    }
}
