<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ModelConversationsController extends Controller
{
    public function modelConversationMenuGet() {

        //Mockup of categories
        $categories = [
          [
              'name' => 'Trudne przypadki',
              'url' => 'zdjecie.jpg'
          ],
            [
                'name' => 'Trudne sprawy',
                'url' => 'zdjecie2.jpg'
            ],
            [
                'name' => 'Trudne sprawy2',
                'url' => 'zdjecie2.jpg'
            ],
            [
                'name' => 'Trudne sprawy3',
                'url' => 'zdjecie2.jpg'
            ]
        ];

        return view('model_conversations.model_conversations_menu')
            ->with('categories', $categories);
    }
}
