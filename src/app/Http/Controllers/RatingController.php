<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Models\Transaction;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\RatingNotificationMail;
use Exception;

class RatingController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $transaction->load(['buyer', 'seller', 'purchase.item']);
        $userId = Auth::id();
        try {
            $validator = Validator::make($request->all(),[
                'rating' => 'required|integer|min:1|max:5',
            ], [
                'rating.required' => '評価を選択してください',
                'rating.integer' => '評価は数字で入力してください',
                'rating.min' => '最低評価は1です',
                'rating.max' => '最高評価は5です',
            ]);

            if ($validator->fails()) {
                return redirect( '/transaction/' .$transaction['id'] .'#review')
                ->withErrors($validator)
                ->withInput();
            }

            if (!in_array($userId, [$transaction->buyer_id, $transaction->seller_id])) {
                return redirect('/')->with('error', 'この取引に関与していないため、評価できません。');
            }

            DB::beginTransaction();
            if ($transaction->buyer_id === $userId) {
                $rating = Rating::create([
                    'transaction_id' => $transaction['id'],
                    'rater_id' => $userId,
                    'rated_user_id' => $transaction['seller_id'],
                    'rating' => $request->rating,
                ]);

                Transaction::where('id', $transaction['id'])
                    ->update(['buyer_rated' => true]);
                
                Mail::to($transaction->seller->email)->send(new RatingNotificationMail($rating, $transaction));

            } elseif ($transaction->seller_id === $userId) {
                Rating::create([
                    'transaction_id' => $transaction['id'],
                    'rater_id' => $userId,
                    'rated_user_id' => $transaction['buyer_id'],
                    'rating' => $request->rating,
                ]);

                $transaction->seller_rated = true;

                if ($transaction->buyer_rated) {
                    $transaction->status = 'completed';
                }

                $transaction->save();
            }
            DB::commit();

            return redirect('/')->with('result', '評価を送信しました');
        } catch (\Exception $e){
            DB::rollBack();
            Log::error("❌ 評価送信エラー: " . $e->getMessage());

            return redirect('/transaction/' . $transaction['id'])->with('error', '評価の送信中にエラーが発生しました。再度お試しください。');
        }
    }
}
