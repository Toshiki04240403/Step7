<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model

{
    // テーブル名がデフォルトの'companies'と異なる場合は、ここで指定
    protected $table = 'companies';

    // プライマリキーが'id'でない場合は、ここで指定
    protected $primaryKey = 'company_name';

    protected $keyType = 'string'; // プライマリキーのデータ型が文字列の場合、これを設定

    // リレーションの定義
    public function products()
    {
        return $this->hasMany(Product::class, 'company_id', 'company_name');
    }
}
