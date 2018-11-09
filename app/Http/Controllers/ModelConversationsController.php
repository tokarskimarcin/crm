<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\Department_info;
use App\Department_types;
use App\Exceptions\model_conv\WrongExtensionException;
use App\Exceptions\TooLongNameException;
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

    private $adminPanelAccessArr = [3, 13, 15]; //Array of privilaged user types
    private $superUserDepartmentType = 6; //see all stuff from other departments
    private $privilagedUser = [5122, 3935, 8137]; //array of privilaged user_id

    public function modelConversationMenuGet() {
        $user = Auth::user();
        $user_type_id = $user->user_type_id;
        $user_department_type = Department_info::getUserDepartmentType($user->id)->id_dep_type;

        $categories = null;

        if($user_department_type == $this->superUserDepartmentType) { //dkj
                $categories = ModelConvCategories::OnlyActive()
                    ->where('subcategory_id', '=', 0)
                    ->get();
        }
        else { //sees only categories from its own department_type and its own
            $categories = ModelConvCategories::OnlyActive()
                ->where('subcategory_id', '=', 0)
                ->where(function ($query) use($user_department_type) {
                    $query->where('department_type_id', '=', $user_department_type)
                        ->orwhere('status', '=', -1);
                })
                ->get();
        }

        return view('model_conversations.model_conversations_categories')
            ->with('categories', $categories)
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user_type_id);
    }

    public function categoryGet($id) {
        $user = Auth::user();
        $user_type_id = $user->user_type_id;
        $user_department_type = Department_info::getUserDepartmentType($user->id)->id_dep_type;

        $categories = ModelConvCategories::OnlyActive()->where('subcategory_id', '=', $id)->get();

        $playlists = null;

        if($user_department_type == $this->superUserDepartmentType) {
            if(in_array($user_type_id, $this->adminPanelAccessArr)) { //this see privilaged user (all available users playlists)
                $playlists = ModelConvPlaylist::all();
            }
            else {
                $playlists = ModelConvPlaylist::where('user_id', '=', Auth::user()->id)->get();
            }
        }
        else {
            $playlists = ModelConvPlaylist::where('user_id', '=', Auth::user()->id)->get();
        }


        $playlistItems = ModelConvPlaylistItem::select(
            'model_conv_playlist.name as name',
            'model_conv_playlist.user_id as user_id',
            'model_conv_playlist.id as id',
            'model_conv_playlist_items.item_id as item_id'
        )
            ->join('model_conv_playlist', 'model_conv_playlist_items.playlist_id', '=', 'model_conv_playlist.id')
            ->get();

        $items = ModelConvItems::where('model_category_id', '=', $id)->OnlyActive()->get();
        $items->map(function($item) use($playlistItems) {
             $allItemsInPlaylists = $playlistItems->where('item_id', '=',$item->id);
             $item->playlists = $allItemsInPlaylists;
            return $item;
        });

        return view('model_conversations.model_conversations_category')
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user_type_id)
            ->with('categories', $categories)
            ->with('items', $items)
            ->with('playlists', $playlists);
    }

    /**
     * @return mixed
     * This method returns data for management panel.
     */
    public function modelConversationsManagementGet() {
        $user = Auth::user();
        $user_type_id = $user->user_type_id;
        $user_department_type = Department_info::getUserDepartmentType($user->id)->id_dep_type;

        $availableDepartmentTypes = Department_types::whereIn('id', [1,2,6])->get();
        $showAvailableDepartmentTypes = false;
        if($user_department_type == $this->superUserDepartmentType) {
            if(in_array($user_type_id, $this->adminPanelAccessArr)) {
                $showAvailableDepartmentTypes = true;
            }
        }

        $items = null;

        if($user_department_type == $this->superUserDepartmentType) {
            if (in_array($user_type_id, $this->adminPanelAccessArr)) {
                $categories = ModelConvCategories::whereNotIn('status', [-1])->get(); //all categories without this with status -1 (permanent)
            }
            else {
                $categories = ModelConvCategories::whereNotIn('status', [-1])->where('department_type_id', '=', $user_department_type)->get(); //all categories from user department type without this with status -1 (permanent)
            }
        }
        else {
            $categories = ModelConvCategories::whereNotIn('status', [-1])->where('department_type_id', '=', $user_department_type)->get();  //all categories from user department type without this with status -1 (permanent)
        }

        $playlists = null;

        if(in_array($user_type_id, $this->adminPanelAccessArr)) { //this see privilaged user (all available users playlists)
            if($user_department_type == $this->superUserDepartmentType) { //
                $playlists = ModelConvPlaylist::getPlaylistInfo(false);
                $items = ModelConvItems::getPlaylistItemsInfo(false);
            }
            else {
                $playlists = ModelConvPlaylist::getPlaylistInfo(false, $user_department_type);
                $items = ModelConvItems::getPlaylistItemsInfo(false, $user_department_type);
            }
        }
        else if(in_array($user->id, $this->privilagedUser)) {
            $playlists = ModelConvPlaylist::getPlaylistInfo(true);
            $items = ModelConvItems::getPlaylistItemsInfo(false, $user_department_type);
        }
        else { //this see regular user (only his own playlist)
            $playlists = ModelConvPlaylist::getPlaylistInfo(true);
            $items = ModelConvItems::getPlaylistItemsInfo(true);
        }

        $playlistItems = ModelConvPlaylistItem::all();

        $returnShowAvailable = $showAvailableDepartmentTypes ? 'true' : 'false';

        return view('model_conversations.model_conversations_management')
            ->with('categories', $categories)
            ->with('user', $user_type_id)
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('items', $items)
            ->with('playlists', $playlists)
            ->with('playlistItems', $playlistItems)
            ->with('superUserDepartmentType', $this->superUserDepartmentType)
            ->with('showAvailableDepartmentTypes', $returnShowAvailable)
            ->with('availableDepartmentTypes', $availableDepartmentTypes)
            ->with('privilagedUserArr', $this->privilagedUser);

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
            $playlist_item->playlist_order = $order;
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

        $playlist_last_order_item = ModelConvPlaylistItem::where('playlist_id', '=', $playlist_id)->orderBy('playlist_order')->get();
        $lastItem = null;
        if($playlist_last_order_item) {
            $lastItem = $playlist_last_order_item->last();
        }
        if($lastItem) {
            return $lastItem->playlist_order + 1;
        }
        else { //first element in playlist
            return 1;
        }

    }

    public function modelConversationsPlaylistGet() {
        $user = Auth::user();
        $user_type_id = $user->user_type_id;
        $user_department_type = Department_info::getUserDepartmentType($user->id)->id_dep_type;

        $playlistCategories = ModelConvPlaylist::where('user_id', '=', $user->id)->get(); //only logged user's playlists

        return view('model_conversations.model_conversations_playlist_categories')
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('playlistCategories', $playlistCategories)
            ->with('user', $user_type_id);
    }

    public function playlistGet($id) {
        $user = Auth::user();
        $user_type_id = $user->user_type_id;
        $user_department_type = Department_info::getUserDepartmentType($user->id)->id_dep_type;

        $playlistObject = ModelConvPlaylist::find($id);

        $playlist = ModelConvPlaylist::select(
            'model_conv_playlist.name as playlist_name',
            'model_conv_items.name as conv_name',
            'model_conv_items.file_name',
            'model_conv_playlist.img as playlist_img',
            'model_conv_playlist.id as id',
            'playlist_order',
            'trainer',
            'gift',
            'client'
        )
            ->join('model_conv_playlist_items', 'model_conv_playlist.id', '=', 'model_conv_playlist_items.playlist_id')
            ->join('model_conv_items', 'model_conv_playlist_items.item_id', '=', 'model_conv_items.id')
            ->where('model_conv_items.status', '=', 1)
            ->where('model_conv_playlist.id', '=', $id)
            ->orderBy('playlist_order')
            ->get();

        return view('model_conversations.model_conversations_playlist')
            ->with('playlist', $playlist)
            ->with('playlistObject', $playlistObject)
            ->with('adminPanelAccessArr', $this->adminPanelAccessArr)
            ->with('user', $user_type_id);
    }

    public function modelConversationsPlaylistPost(Request $request) {
        $toAdd = $request->toAdd;

        $picture = $request->file('picture');
        $picture_name = null;
        $id = $request->id;
        $acceptedExtensions = ['jpeg', 'jpg'];


        if($toAdd == 1) {
            if(isset($picture)) { //user send picture
                $clientOriginalName = str_replace(' ','_',$picture->getClientOriginalName());
                $clientOriginalNameLength = strlen($clientOriginalName);
                $acceptedLength = $clientOriginalNameLength < 235 ? true : false; //255 - 20
                $fileExtension = strtolower($picture->getClientOriginalExtension());

                if(in_array($fileExtension, $acceptedExtensions)) {
                    if($acceptedLength) {
                        $picture_name = 'playlist_' . date('Y-m-d') . '_' . $clientOriginalName;
                        try {
                            $picture->storeAs('public',$picture_name);
                            new ActivityRecorder(array_merge(['T' => 'Dodanie zdjecia'], ['Nazwa' => $picture_name]), 250, 1);
                        }
                        catch(\Exception $error) {
                            dd($error);
                        }
                    }
                    else {
                        throw new TooLongNameException('Za długa nazwa przesyłanego pliku. Maksymalna długość nazwy pliku to 235 znaków');
                    }
                }
                else {
                    throw new WrongExtensionException('Niedozwolone rozszerzenie zdjęcia, możliwe rozszerzenia: jpeg, jpg');
                }

            }
            else { //user didn't send picture, we assing default one.
                $rnd = rand(1,5);
                $picture_name = 'playlist_default_' . $rnd . '.jpeg';
            }
        }
        else {
            if(isset($picture)) { //user send picture
                $clientOriginalName = str_replace(' ','_',$picture->getClientOriginalName());
                $clientOriginalNameLength = strlen($clientOriginalName);
                $acceptedLength = $clientOriginalNameLength < 235 ? true : false; //255 - 20
                $fileExtension = strtolower($picture->getClientOriginalExtension());

                if(in_array($fileExtension, $acceptedExtensions)) {
                    if($acceptedLength) {
                        $picture_name = 'playlist_' . date('Y-m-d') . '_' . $clientOriginalName;
                        try {
                            $picture->storeAs('public',$picture_name);
                            new ActivityRecorder(array_merge(['T' => 'Dodanie zdjecia'], ['Nazwa' => $picture_name]), 250, 1);
                        }
                        catch(\Exception $error) {
                            dd($error);
                        }

                    }
                    else {
                        throw new TooLongNameException('Za długa nazwa przesyłanego pliku. Maksymalna długość nazwy pliku to 235 znaków');
                    }
                }
                else {
                    throw new WrongExtensionException('Niedozwolone rozszerzenie zdjęcia, możliwe rozszerzenia: jpeg, jpg');
                }
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

    /**
     * @param $id
     * This method removes playlist with references
     */
    public function managementPlaylistDelete($id) {
       return ModelConvPlaylist::deleteWithReferences($id);
    }

    /**
     * @param $id
     * This method removes playlist item
     */
    public function managementPlaylistItemsDelete($id) {
        return ModelConvPlaylistItem::smartDelete($id);
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

        $user = Auth::user();
        $user_type_id = $user->user_type_id;
        $user_department_type = Department_info::getUserDepartmentType($user->id)->id_dep_type;

        $toAdd = $request->toAdd; //This varible defines whether user edit category or add new one 1 - add, 0 - edit
        $acceptedExtensions = ['jpeg', 'jpg'];

            //przy zmianie zdiecia, trzeba usunać stare - trzeba dodać to i przypisanie do danej kategori tego nowego zdiecia
            $id = $request->id;
            $picture = $request->file('picture');
            $picture_name = null;

            if($toAdd == 1) {
                if(isset($picture)) { //user send picture
                    $clientOriginalName = str_replace(' ','_',$picture->getClientOriginalName());
                    $fileExtension = strtolower($picture->getClientOriginalExtension());
                    $clientOriginalNameLength = strlen($clientOriginalName);
                    $acceptedLength = $clientOriginalNameLength < 235 ? true : false; //255 - 20

                    if(in_array($fileExtension, $acceptedExtensions)) {
                        if($acceptedLength) {
                            $picture_name = 'category_' . date('Y-m-d') . '_' . $clientOriginalName;
                            try {
                                $picture->storeAs('public',$picture_name);
                                new ActivityRecorder(array_merge(['T' => 'Dodanie zdjęcia'], ['nazwa' => $picture_name]), 250,1);
                            }
                            catch(\Exception $error) {
                                dd($error);
                            }
                        }
                        else {
                            throw new TooLongNameException('Za długa nazwa przesyłanego pliku. Maksymalna długość nazwy pliku to 235 znaków');
                        }

                    }
                    else {
                        throw new WrongExtensionException('Niedozwolone rozszerzenie zdjęcia, możliwe rozszerzenia: jpeg, jpg');
                    }

                }
                else { //user didn't send picture, we assing default one.
                    $rnd = rand(1,5);
                    $picture_name = 'category_default_' . $rnd . '.jpeg';
                }
            }
            else {
                if(isset($picture)) { //user send picture
                    $clientOriginalName = str_replace(' ','_',$picture->getClientOriginalName());
                    $fileExtension = strtolower($picture->getClientOriginalExtension());
                    $clientOriginalNameLength = strlen($clientOriginalName);
                    $acceptedLength = $clientOriginalNameLength < 235 ? true : false; //255 - 20

                    if(in_array($fileExtension, $acceptedExtensions)) {
                        if($acceptedLength) {
                            $picture_name = 'category_' . date('Y-m-d') . '_' . $clientOriginalName;
                            try {
                                $picture->storeAs('public',$picture_name);
                                new ActivityRecorder(array_merge(['T' => 'Dodanie zdjęcia'], ['Nazwa' => $picture_name]), 250,1);
                            }
                            catch(\Exception $error) {
                                dd($error);
                            }
                        }
                        else {
                            throw new TooLongNameException('Za długa nazwa przesyłanego pliku. Maksymalna długość nazwy pliku to 235 znaków');
                        }

                    }
                    else {
                        throw new WrongExtensionException('Niedozwolone rozszerzenie zdjęcia, możliwe rozszerzenia: jpeg, jpg');
                    }

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

            $department_type_request = null;
            if($request->has('department_type_id')) {
                $department_type_request = $request->department_type_id;
            }
            else {
                $department_type_request = $user_department_type;
            }
            $category->department_type_id = $department_type_request;

            try {
                $category->save();
                if($toAdd == 1) {
                    new ActivityRecorder(array_merge(['T' => 'Dodanie kategorii'], $category->toArray()),250,1);
                }
                else {
                    new ActivityRecorder(array_merge(['T' => 'Edycja kategorii'], $category->toArray()),250,2);
                }
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
            ModelConvItems::deleteWithReferences($id);
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }

    }

    /**
     * @param Request $request
     * @return mixed
     * This method enables to edit or add new item
     */
    public function itemsPost(Request $request) {
        $toAdd = $request->toAdd;
        $acceptedExtensions = ['wav', 'mp3', 'ogg'];

        //we are adding new item
        $name = $request->name;
        $status = $request->status;
        $trainer = $request->trainer;
        $gift = $request->gift;
        $client = $request->client;
        $category = $request->category_id;
        $temp = $request->temp;

        $sound = $request->file('sound');
        $sound_name = null;
        if(isset($sound)) {
            $clientOriginalName = str_replace(' ','_',$sound->getClientOriginalName());
            $fileExtension = strtolower($sound->getClientOriginalExtension());
            $clientOriginalNameLength = strlen($clientOriginalName);
            $acceptedLength = $clientOriginalNameLength < 240 ? true : false; //255 - 15

            if(in_array($fileExtension, $acceptedExtensions)) {
                if($acceptedLength) {
                    $sound_name = 'item_' . date('Y-m-d') . '_' . $clientOriginalName;
                    try {
                        $sound->storeAs('public',$sound_name);
                        new ActivityRecorder(array_merge(['T' => 'Dodanie nowej rozmowy'], ['nazwa' =>$sound_name]), 250,1);
                    }
                    catch(\Exception $error) {
                        dd($error);
                    }
                }
                else {
                    throw new TooLongNameException('Za długa nazwa przesyłanego pliku. Maksymalna długość nazwy pliku to 240 znaków');
                }
            }
            else {
                throw new WrongExtensionException('Niedozwolone rozszerzenie pliku, możliwe rozserzenia: wav, mp3, ogg');
            }
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
        $newItem->temp = $temp;
        $newItem->created_at = date('Y-m-d');
        try {
            $newItem->save();
            if($toAdd == 1) { //creating new
                new ActivityRecorder(array_merge(['T' => 'Dodanie rozmowy'], $newItem->toArray()),250,1);
            }
            else {
                new ActivityRecorder(array_merge(['T' => 'Edycja rozmowy'], $newItem->toArray()),250,2);
            }
        }
        catch(\Exception $error) {
            new ActivityRecorder($error, 1, 6);
        }

        return Redirect::back();
    }

    public function modelConversationsManagementChangeOrder(Request $request){
        $selectedTr = $request->selectedTr;
        $selectedOrder = $request->selectedOrder;
        $selectedPlaylistId = $request->selectedPlaylistId;

        $variable = 0;
        $modelConvPlaylistItem = ModelConvPlaylistItem::where('playlist_id', $selectedPlaylistId)
            ->where('playlist_order', '>=', $selectedTr > $selectedOrder ? $selectedOrder : $selectedTr)
            ->where('playlist_order', '<=',  $selectedTr > $selectedOrder ? $selectedTr : $selectedOrder)
            ->get();
        if($selectedTr > $selectedOrder){
            $variable = 1;
        }else if($selectedTr < $selectedOrder){
            $variable = -1;
        }
        $selectedItem = $modelConvPlaylistItem->where('playlist_order', $selectedTr)->first();

        foreach ($modelConvPlaylistItem->where('playlist_order', $selectedTr > $selectedOrder ? '<' : '>', $selectedTr) as $item){
            $item->playlist_order = $item->playlist_order + $variable;
            $item->save();
        }
        $selectedItem->playlist_order = intval($selectedOrder);
        $selectedItem->save();
    }
}
