<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxWalletsDetails extends Model
{

    protected $table       = 'tx_wallets_details';
    protected $primaryKey  = 'id';
    protected $guarded     = ['id'];

    use HasFactory;


    protected $fillable = ['amount','type','description','wallet_id','createdby','updatedby'];

}
