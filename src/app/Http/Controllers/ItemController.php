<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemController extends Controller
{
    public function index(Request $request) {
        try {
            $parameter = Item::getParameter($request);
            $page = $request->input('page', 'default');

            switch ($page) {
                case 'suggest':
                    $items = Item::searchSuggestItems();
                    break;
                case 'mylist':
                    $items = Item::getFavoriteItems();
                    break;
                default:
                    $items = Item::getItems();
            }

            if (!$items) {
                $items = collect([]);
            }

            return view('index', compact('items', 'parameter'));
        } catch (QueryException $e) {
            Log::error('❌ データベースエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'データの取得に失敗しました。');
        } catch (Exception $e) {
            Log::error('❌ 予期しないエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function searchItem(Request $request) {
        try {
            $keyword = $request->input('keyword');

            if ($keyword) {
                session(['search_keyword' => $keyword]);
            } else {
                session()->forget('search_keyword');
            }
            $items = Item::searchItems($keyword);

            $parameter = Item::getParameter($request);

            return redirect()->back()->with(compact('items', 'parameter'));
        } catch (QueryException $e) {
            Log::error("❌ データベースエラー:", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '検索処理に問題が発生しました。');
        } catch (Exception $e) {
            Log::error("❌ 予期しないエラー:", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function getDetail(Item $item) {
        try {
            $item->loadCount(['favorites', 'comments'])
            ->load(['condition', 'brand', 'favorites', 'comments.user']);

            $favorite = $item->favorites()->where('user_id', Auth::id())->first();
            
            $category = [];
            if (!empty($item->category)) {
                $categories = unserialize($item->category);
                foreach ($categories as $value) {
                    $name = Category::find($value);
                    if ($name) {
                        $category[] = $name->name;
                    }
                }
            }

            return view('detail', compact('item', 'category', 'favorite'));
        } catch (QueryException $e) {
            Log::error("❌ データベースエラー:", ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'データの取得に失敗しました。');
        } catch (Exception $e) {
            Log::error("❌ 予期しないエラー:", ['error' => $e->getMessage()]);
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function postComment(CommentRequest $request, Item $item) {

        $user = Auth::user();

        if (!$user->profile_completed) {
            return redirect('/mypage/profile')->with('error', 'コメントするにはプロフィールを設定してください');
        }

        try {
            DB::beginTransaction();
            Comment::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'comment' => $request->comment,
            ]);
            DB::commit();

            return redirect()->back()->with('result', 'コメントを送信しました');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("❌ コメント投稿エラー: " . $e->getMessage());
            return redirect()->back()->with('error', 'コメントの送信に失敗しました。再度お試しください。');
        }
    }
}
