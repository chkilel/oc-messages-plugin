<?php namespace Autumn\Messages\Api;

use Auth;
use View;
use Cms\Classes\Page;
use Illuminate\Routing\Controller;
use Autumn\Messages\Models\ConversationUser;

class MessagesController extends Controller
{

    public function count()
    {
        if ($user = Auth::getUser()) {

            $newMessages = ConversationUser::where('user_id', $user->id)
                ->whereRaw('last_viewed < updated_at')
                ->count();

            return $newMessages;
        }
    }

    public function recent()
    {
        if ($user = Auth::getUser()) {
            $userMessages = ConversationUser::where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            $messagePage = Page::withComponent('userConversations')->first();
            $messagePage = Page::url($messagePage->baseFileName);

            return View::make('autumn.messages::notifications', [
                'userMessages' => $userMessages,
                'messagePage'  => $messagePage
            ]);
        }
    }

}