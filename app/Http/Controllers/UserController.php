<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request, $id) {
        $request->validate([
            'id' => 'required',
            'user_amount' => 'required',
            'purchase' => 'required'
        ]);

        try{
            DB::beginTransaction();

            $user = User::lockForUpdate()
                            ->where('id', $id)
                            ->where('user_amount', $request->user_amount)
                            ->first();

            $user->user_amount -= $request->purchase;

            $user->save();

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Failed update user amount !'
            ], 401);
        }

        return response()->json([
            'message' => 'User amount has been updated.'
        ], 200);
    }
}
