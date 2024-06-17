<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    // テーブル名がデフォルトの'products'と異なる場合は、ここで指定
    protected $table = 'products';

    // リレーションの定義
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_name');
    }
}
