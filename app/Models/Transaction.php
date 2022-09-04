<?php
namespace App\Models;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
class Transaction extends Model {

    protected $fillable = [];
    protected $table = "transaction";
    // public function Post(){
    //     return $this->hasOne(Post::class,'post_id','category_id');
    // }
}