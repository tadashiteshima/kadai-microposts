<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加

class FavoritesController extends Controller
{
    /**
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        // 認証済みユーザ（閲覧者）が、 idのユーザをフォローする
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * ユーザをアンフォローするアクション。
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済みユーザ（閲覧者）が、 idのユーザをアンフォローする
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    

    /**
     * ユーザのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function favorites_users($id)
    {
        // idの値でユーザを検索して取得
        $microposts = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $microposts->loadRelationshipCounts();

        // ユーザのフォロワー一覧を取得
        $favorites_users = $microposts->favorites_users()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('microposts.favorites_users', [
            'user' => $microposts,
            'users' => $favorites_users,
        ]);
    }
}
