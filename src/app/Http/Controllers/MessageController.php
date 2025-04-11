<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function sendMessage(MessageRequest $messageRequest, ProfileRequest $profileRequest, $transactionId) {
        try {
            $user = User::findOrFail(Auth::id());
            $userId = $user->id;

            $validatedData = array_merge($messageRequest->validated(), $profileRequest->validated());

            $image_url = null;
            if ($profileRequest->hasFile('image_url')) {
                try {
                    $image_url = Item::getImageUrl($profileRequest->file('image_url'));
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("❌ 画像アップロードに失敗しました: " . $e->getMessage());
                    return redirect()->back()->with('error', '画像のアップロードに失敗しました。再度お試しください。');
                }
            }

            DB::beginTransaction();

            try{
                Message::create([
                    'transaction_id' => $transactionId,
                    'sender_id' => $userId,
                    'message' => $validatedData['message'],
                    'image_url' => $image_url,
                ]);
            } catch (QueryException $e) {
                DB::rollBack();
                Log::error("❌ メッセージ登録エラー: " . $e->getMessage());
                return redirect()->back()->with('error', 'メッセージの登録に失敗しました。再度お試しください。');
            }
            
            DB::commit();

            return redirect()->back()->with('result', 'メッセージを送信しました');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ メッセージ投稿エラー: " . $e->getMessage());
            return redirect()->back()->with('error', 'メッセージの送信に失敗しました。再度お試しください。');
        }
    }
}
