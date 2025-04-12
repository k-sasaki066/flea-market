<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\EditMessageRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MessageController extends Controller
{
    public function getTransaction($transactionId) {
        $transaction = Transaction::with(['buyer', 'seller', 'purchase.item'])
        ->findOrFail($transactionId);
        $user = Auth::user();

        $showReviewModal = false;

        if ($user['id'] === $transaction['seller_id'] && 
            $transaction['buyer_rated'] && 
            !$transaction['seller_rated']) {
            $showReviewModal = true;
        }

        $otherUser = $transaction['buyer']['id'] == $user['id'] ? $transaction['seller'] : $transaction['buyer'];

        $items = Transaction::getTransactionItemsWithUnreadCount($user['id']);
        $otherItems = $items->filter(function ($item) use ($transactionId) {
            return $item->id != $transactionId;
        });

        $messages = Message::withTrashed()
        ->where('transaction_id', $transactionId)
        ->with('sender', 'image')
        ->orderBy('created_at', 'asc')
        ->get();

        Message::markAsRead($transactionId, $user);

        return view('transaction', compact('transaction', 'otherUser', 'user', 'otherItems', 'messages', 'showReviewModal'));
    }

    public function sendMessage(MessageRequest $messageRequest, ProfileRequest $profileRequest, Transaction $transaction) {
        DB::beginTransaction();
        try {
            $user = User::findOrFail(Auth::id());
            $userId = $user->id;

            if ($transaction->buyer_rated && $userId === $transaction->seller_id) {
                return redirect()->back()->with('error', '購入者の評価が完了しているため、これ以上のメッセージ送信はできません。');
            }

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

            try{
                $message = Message::create([
                    'transaction_id' => $transaction['id'],
                    'sender_id' => $userId,
                    'message' => $messageRequest['message'],
                ]);
                if($image_url) {
                    Image::create([
                        'message_id' => $message['id'],
                        'image_url' => $image_url,
                    ]);
                }
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

    public function update(EditMessageRequest $request, $messageId)
    {
        try {
            $message = Message::findOrFail($messageId);
            DB::beginTransaction();
            $message->update([
                'message' => $request->message_send,
            ]);
            DB::commit();

            return redirect()->back()->with('message_updated', true)->with('result', 'メッセージを更新しました');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ メッセージ更新エラー: " . $e->getMessage());
            return redirect()->back()->with('error', 'メッセージの送信に失敗しました。再度お試しください。');
        }
    }

    public function destroy(Message $message)
    {
        try {
            $userId = Auth::id();
            if ($message->sender_id !== $userId) {
                abort(403);
            }
            DB::beginTransaction();

            $message->delete();

            DB::commit();

            return redirect()->back()->with('result', 'メッセージを削除しました');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ メッセージ削除エラー: " . $e->getMessage());
            return redirect()->back()->with('error', 'メッセージの削除に失敗しました。再度お試しください。');
        }
    }
}
