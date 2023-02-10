<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TxWallet;
use App\Models\TxWalletsDetails;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WalletsController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Set search request data by user/customer
        $where_user =  $request->input('nasabah');

        //get data for history saldo balance
        $data = TxWallet::with('detail_wallet','user')
        ->when($where_user, function($query,$where_user){
            return $query->whereHas('user', function($query2) use($where_user){
                $query2->where("name",$where_user);
            });
        })->get();

        // return data history saldo balance 
        if($data){
            return response()->json([
                'message' => 'Succesfully Get History Saldo Balance',
                'data'    => $data
            ]);
        }else{
            return response()->json([
                'message' => 'Failed Get Data History Saldo Balance',
                'data'    => $data
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Validate the deposit amount
        $request->validate([
            'amount' => 'required|numeric',
        ]);
        
        $userId = auth()->user()->id;

        // Set the user wallet
        $wallet = TxWallet::where('createdby', $userId)->first();
        if(!$wallet){
            // If the wallet does not exist, create
            $wallet = new TxWallet();
            $wallet->createdby = $userId;
            $wallet->updatedby = $userId;
            $wallet->amount = $request->input('amount');
            $wallet->save();

        }

        // Update amount balance
        $wallet->amount += $request->input('amount');
        $wallet->save();

        // Insert into history wallet
        $wallet_detail = [];
        $wallet_detail['createdby'] = $userId;
        $wallet_detail['updatedby'] = $userId;
        $wallet_detail['wallet_id'] = $wallet->id;
        $wallet_detail['type'] = 'top_up';
        $wallet_detail['description'] = $request->input('description');
        $wallet_detail['amount'] = $request->input('amount');

        TxWalletsDetails::create($wallet_detail);

        // Return update amount
        return response()->json([
            'amount' => $wallet->amount,
            'type' => 'top_up',
            'description' => $request->input('description'),
        ]);
    }

    public function update_wallet(Request $request)
    {

         //Validate the deposit amount
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $userId = auth()->user()->id;

         // Set the user wallet
         $wallet = TxWallet::where('createdby', $userId)->first();
         if(!$wallet){
             // If the wallet does not exist, create
             $wallet = new TxWallet();
             $wallet->createdby = $userId;
             $wallet->updatedby = $userId;
             $wallet->amount = $request->input('amount');
             $wallet->save();
 
         }
 
         $amount = $request->input('amount');

         // Update amount balance
         $wallet->amount -= $amount;
         $wallet->save();


        // Insert into history wallet
        $wallet_detail = [];
        $wallet_detail['createdby'] = $userId;
        $wallet_detail['updatedby'] = $userId;
        $wallet_detail['wallet_id'] = $wallet->id;
        $wallet_detail['type'] = 'withdraw';
        $wallet_detail['description'] = $request->input('description');
        $wallet_detail['amount'] = $request->input('amount');

        TxWalletsDetails::create($wallet_detail);

         // Return update amount
         return response()->json([
            'amount' => $wallet->amount,
            'type' => 'withdraw',
            'description' => $request->input('description'),
        ]);

    }


        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
