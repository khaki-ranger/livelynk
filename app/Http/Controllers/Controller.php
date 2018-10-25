<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // 全Controllerに共通して利用する処理を以下にまとめる

    // リーダーのIDを取得
    public function getReaderID()
    {
        $user = Auth::user();
        return $reader_id = DB::table('communities')
            ->where('id', $user->community_id)->value('user_id');
    }

    // user のroleを取得 roleの文字列で返す
    // Auth::user()->role で取得可能なので、未使用
    // 他のuser で取得が必要な場合に使えるが基本いらない
    public function getUserRole($community_user_id)
    {
        return DB::table('communities_users_statuses')
            ->leftJoin('roles', 'communities_users_statuses.role_id', '=', 'roles.id')
            ->where([
                ['communities_users_statuses.id', $community_user_id],
        ])->pluck('role')->first();
    }

    // getのパラメーター path がDBに存在する communityのpathか判定
    // true なら該当する commyunity のtable record を返す
    public function GetCommunityFromPath($request_path)
    {
        // アクセスしてきた際のpathを取得し異常な値は撥ねる
        if (!preg_match("/^[a-zA-Z0-9]+$/", $request_path)) {
            return false;
        }
        // 半角英数の path ならDB見に行って match したコミュニティを返す
        $community = DB::table('communities')->where('url_path', $request_path)->first();
        if (!$community) {
            return false;
        }
        return $community;
    }

    public function roleNameToIdChange($role_name)
    {
        return DB::table('roles')
            ->where('role', $role_name)
        ->pluck('id')->first();
    }

}
