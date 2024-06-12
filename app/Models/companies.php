<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Article extends Model
{
    public function getList() {
        // articlesテーブルからデータを取得
        $articles = DB::table('companies')->get();

        return $articles;
    }
}

class Company extends Model
{
    use HasFactory;
    
    // Productモデルとのリレーションを定義
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}