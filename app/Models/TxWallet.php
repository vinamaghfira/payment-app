<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxWallet extends Model
{

    protected $table       = 'tx_wallets';
    protected $primaryKey  = 'id';
    protected $guarded     = ['id'];

    use HasFactory;


    protected $fillable = ['amount','createdby','updatedby'];


    public function detail_wallet(){
        return $this->hasMany(TxWalletsDetails::class, 'wallet_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'createdby');
    }


}
