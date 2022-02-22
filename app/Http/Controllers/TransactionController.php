<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $keyword = $request['search'];
        if ($request['search']) {
            $transactions = Transaction::with('stock')->get();
            $transactions = CollectionHelper::paginate($transactions->filter(function ($item) use ($keyword) {
                return false !== stripos($item, $keyword);
            }), $request['perPage']);
        } else {
            $transactions = Transaction::with('stock')->get();
            $transactions = CollectionHelper::paginate($transactions->values(), $request['perPage']);
        }
        return response()->json(['type' => 'success', 'message' => 'Transactions loaded successfully.', 'errors' => null, 'data' => $transactions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'quantity' => 'required',
            'transaction_date' => 'required',
            'unit_price' => 'required',
            'transaction_type' => 'required',
            'stock_id' => 'required'
        ]);
        $data = $request->all();
        $transaction = Transaction::create($data);
        return response()->json(['type' => 'success', 'message' => 'Transaction added successfully.', 'errors' => null, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transactions = Transaction::find($id);
        return response()->json(['type' => 'success', 'message' => 'Item category detail fetched successfully.', 'errors' => null, 'data' => $transactions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        $data = $request->validate([
            'quantity' => 'required',
            'transaction_date' => 'required',
            'unit_price' => 'required',
            'transaction_type' => 'required',
            'stock_id' => 'required'
        ]);

        $data = $request->all();
        $transaction = Transaction::find($id);
        $transaction->update($data);
        $transaction = Transaction::find($id);
        return response()->json(['type' => 'success', 'message' => 'Transaction updated successfully.', 'errors' => null, 'data' => $transaction]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete($transaction);
        return response()->json(['type' => 'success', 'message' => 'Transaction deleted successfully.', 'errors' => null, 'data' => $transaction]);
    }
}
