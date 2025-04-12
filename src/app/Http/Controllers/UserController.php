<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Brand;
use App\Models\Transaction;
use App\Models\Message;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function getProfile() {
        try {
            $user = Auth::user();
            return view('profile', compact('user'));
        } catch (\Exception $e) {
            Log::error("❌ プロフィールページの取得中にエラー発生", ['error' => $e->getMessage()]);
            return redirect('/mypage')->with('error', 'プロフィール情報の取得に失敗しました。');
        }
    }

    public function postProfile(AddressRequest $addressRequest, ProfileRequest $profileRequest) {
        try {
            $user = User::findOrFail(Auth::id());

            $validatedData = array_merge($addressRequest->validated(), $profileRequest->validated());

            try {
                $image_url = $profileRequest->file('image_url') ? Item::getImageUrl($profileRequest->file('image_url')) : $user->image_url;
            } catch (\Exception $e) {
                Log::error("❌ 画像アップロードエラー: " . $e->getMessage());
                $image_url = $user->image_url;
                return redirect()->back()->with('error', '画像のアップロードに失敗しました。再度お試しください。');
            }

            DB::beginTransaction();
            $user->update([
                'nickname' => $validatedData['nickname'],
                'post_cord' => $validatedData['post_cord'],
                'address' => $validatedData['address'],
                'building' => $addressRequest['building'],
                'image_url' => $image_url,
                'profile_completed' => true,
            ]);
            DB::commit();

            return redirect('/mypage')->with('result', 'プロフィールが更新されました');
        } catch (ModelNotFoundException $e) {
            Log::error("❌ ユーザーが見つかりません: " . $e->getMessage());
            return redirect('/mypage')->with('error', 'ユーザー情報の取得に失敗しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ プロフィール更新エラー: " . $e->getMessage());
            return redirect('/mypage')->with('error', 'プロフィールの更新に失敗しました。');
        }
    }

    public function getMypage(Request $request) {
        try {
            $userId = Auth::id();
            $user = User::select(['nickname', 'image_url'])->findOrFail($userId);
            $parameter = Item::getParameter($request);
            $page = $request->input('page', 'default');
            $unreadCount = Transaction::getUnreadMessageCount($userId);

            switch ($page) {
                case 'sell':
                    $items = Item::getExhibitedItems($userId);
                    break;
                case 'buy':
                    $items = Item::getPurchasedItems($userId);
                    break;
                case 'transaction':
                    $items = Transaction::getTransactionItemsWithUnreadCount($userId);
                    break;
                default:
                    $items = collect([]);
            }

            return view('mypage', compact('user', 'items', 'parameter', 'unreadCount'));
        } catch (ModelNotFoundException $e) {
            Log::error("❌ ユーザーが見つかりません: " . $e->getMessage());
            return redirect('/')->with('error', 'ユーザー情報の取得に失敗しました。');
        } catch (\Exception $e) {
            Log::error('❌ 予期しないエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function getSell() {
        try {
            $categories = Category::getCategories();
            $conditions = Condition::getConditions();
            $brands = Brand::all();

            if ($categories->isEmpty()) {
                Log::warning('カテゴリー情報が空です');
            }
            if ($conditions->isEmpty()) {
                Log::warning('商品の状態情報が空です');
            }
            if ($brands->isEmpty()) {
                Log::warning('ブランド情報が空です');
            }

            return view('sell', compact('categories','conditions', 'brands'));
        } catch (QueryException $e) {
            Log::error('❌ データベースエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'データの取得に失敗しました。');
        } catch (\Exception $e) {
            Log::error('❌ 予期しないエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function postSell(ExhibitionRequest $request) {
        try{
            $user = Auth::user();
            $userId = $user->id;

            if (!$user->profile_completed) {
                return redirect('/mypage/profile')->with('error', '商品を出品するにはプロフィールを設定してください');
            }

            DB::beginTransaction();

            $image_url = null;
            if ($request->hasFile('image_url')) {
                try {
                    $image_url = Item::getImageUrl($request->file('image_url'));
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("❌ 画像アップロードに失敗しました: " . $e->getMessage());
                    return redirect()->back()->with('error', '画像のアップロードに失敗しました。再度お試しください。');
                }
            }

            $brand = null;
            if (!empty($request->brand_name)) {
                $brandName = trim($request->brand_name);
                try {
                    $brand = Brand::firstOrCreate(['name' => $brandName]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("❌ ブランド作成エラー: " . $e->getMessage());
                    return redirect()->back()->with('error', 'ブランドの登録に失敗しました。再度お試しください。');
                }
            }

            try{
                Item::create([
                    'user_id' => $userId,
                    'condition_id' => $request->condition_id,
                    'brand_id' => $brand ? $brand->id : null,
                    'name' => $request->name,
                    'image_url' => $image_url,
                    'category' => serialize($request->category),
                    'description' => $request->description,
                    'price' => $request->price,
                    'status' => 1,
                ]);
            } catch (QueryException $e) {
                DB::rollBack();
                Log::error("❌ 商品登録エラー: " . $e->getMessage());
                return redirect()->back()->with('error', '商品の登録に失敗しました。再度お試しください。');
            }
            DB::commit();

            return redirect('/mypage?page=sell')->with('result', '商品を出品しました');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ 予期しないエラー発生: " . $e->getMessage());
            return redirect()->back()->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function getBrandName(Request $request) {
        $query = $request->query('query');

        $brands = Brand::query();

        if (!empty($query)) {
            $brands->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($query) . "%"]);
        }

        return response()->json($brands->get());
    }

    public function getTransaction($transactionId) {
        $transaction = Transaction::with(['buyer', 'seller', 'purchase.item'])
        ->findOrFail($transactionId);

        $user = Auth::user();
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

        return view('transaction', compact('transaction', 'otherUser', 'user', 'otherItems', 'messages'));
    }
}
