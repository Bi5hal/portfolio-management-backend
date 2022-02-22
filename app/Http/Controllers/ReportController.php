<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stocksReport(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user_id)->get();
        $total_units = 0;
        $sold_units = 0;
        $buy_units = 0;
        $total_investment = 0;
        $sold_amount = 0;
        $current_amount = 0;
        $profit = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === "buy") {
                $total_investment += ($transaction->quantity * $transaction->unit_price);
                $buy_units += $transaction->quantity;
            }

            if ($transaction->transaction_type === "sell") {
                $sold_amount += ($transaction->quantity * $transaction->unit_price);
                $sold_units += $transaction->quantity;
            }
        }
        $total_units = $buy_units-$sold_units;

        $profit = $sold_amount - $total_investment;


        $data = [
            'total_units' => $total_units,
            'total_investment' => $total_investment,
            'sold_amount' => $sold_amount,
            'profit' => $profit,
        ];
        return response()->json(['type' => 'success', 'message' => 'Transactions loaded successfully.', 'errors' => null, 'data' => $data]);

    }

    public function stockReport(Request $request)
    {
        $transactions = Transaction::with('stock')->where('stock_id', $request->stock_id)->where('user_id', $request->user_id)->get();
        $total_units = 0;
        $sold_units = 0;
        $buy_units = 0;
        $total_investment = 0;
        $sold_amount = 0;
        $current_amount = 0;
        $profit = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === "buy") {
                $total_investment += ($transaction->quantity * $transaction->unit_price);
                $buy_units += $transaction->quantity;
            }

            if ($transaction->transaction_type === "sell") {
                $sold_amount += ($transaction->quantity * $transaction->unit_price);
                $sold_units += $transaction->quantity;
            }
        }
        $total_units = $buy_units-$sold_units;

        $profit = $sold_amount - $total_investment;


        $data = [
            'total_units' => $total_units,
            'total_investment' => $total_investment,
            'sold_amount' => $sold_amount,
            'profit' => $profit,
            'transactions' => $transactions
        ];
        return response()->json(['type' => 'success', 'message' => 'Transactions loaded successfully.', 'errors' => null, 'data' => $data]);


    }
}
