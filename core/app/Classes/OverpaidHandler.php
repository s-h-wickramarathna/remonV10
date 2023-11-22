<?php

namespace App\Classes;



use Core\MenuManage\Models\Menu;
use Core\Permissions\Models\Permission;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use Application\PaymentManage\Models\OverpaidTransaction;
use Application\PaymentManage\Models\OverPayment;
use Application\PaymentManage\Models\PaymentType;
use Illuminate\Support\Facades\DB;

class OverpaidHandler
{

    /**
     * new transaction
     * every add is a plus
     */
    static function add($location_id, $amount, $type, $from)
    {
        $overpayment = Overpayment::where('location_id', '=', $location_id)->where('reference_id', '=', $type)->first();

        if (count($overpayment) > 0) {
            $overpayment->amount += $amount;
            $overpayment->save();
        } else {
            $overpayment = OverPayment::create([
                "amount" => $amount,
                "location_id" => $location_id,
                "reference_id" => $type,
                "over_paid_date" => date('Y-m-d H:i:s'),
            ]);
        }

        if ($type == 3) {//credit note
            $transaction = OverpaidTransaction::create([
                "amount" => $amount,
                "overpayment_id" => $overpayment->id,
                "return_id" => $from->id,
                "recipt_id" => null,
            ]);
        } else {//cash or cheque
            $transaction = OverpaidTransaction::create([
                "amount" => $amount,
                "overpayment_id" => $overpayment->id,
                "return_id" => null,
                "recipt_id" => $from->id,
            ]);

        }

        return $overpayment;

    }


    /**
     * new transaction
     * every use is a minus
     */
    static function useOverpaid($amount, $from, $location_id, $type = '0')
    {
        $payment_type = PaymentType::find($type);
        $overpayment = Overpayment::where('location_id', '=', $location_id)->where('reference_id', '=', $payment_type->link_with)->first();

        $transaction = OverpaidTransaction::create([
            "amount" => -$amount,
            "overpayment_id" => $overpayment->id,
            "return_id" => null,
            "recipt_id" => $from->id,
        ]);

        $updateThis = DB::table('overpayments')->where('id', $overpayment->id)->decrement('amount', $amount);
        if ($updateThis) {
            return $transaction;
        } else {
            return false;
        }

    }


    /**
     * from transaction
     *
     */
    static function getOverpaidsFromTransaction($location_id, $type)
    {
        $aa = null;

        if ($type) {
            $aa = DB::table('overpayment_transactions')
                ->selectRaw('sum(overpayment_transactions.amount) as sum')
                ->join('overpayments', 'overpayments.id', '=', 'overpayment_transactions.overpayment_id')
                ->where('location_id', $location_id)
                ->where('reference_id', $type)
                ->groupBy('location_id')->get();
        }

        return $aa[0]->sum;

    }

    /**
     * from transaction
     *
     */
    static function getOverpaidOf($location_id, $type)
    {
        $aa = null;

        if ($type) {
            $aa = DB::table('overpayments')
                ->where('location_id', $location_id)
                ->where('reference_id', $type)
                ->groupBy('location_id')->get();
        }

        return $aa[0]->amount;

    }

    /**
     * from transaction
     *
     */
    static function getOverpaids($location_id)
    {
        $aa = null;

        if ($location_id) {

            $aa = DB::table('overpayments')
                 ->where('location_id', $location_id)
                 ->get([DB::raw('SUM(CASE WHEN reference_id = 1 OR reference_id = 4 THEN amount ELSE 0 END) cash_overpaid'),
                     DB::raw('SUM(CASE WHEN reference_id = 2 OR reference_id = 5 THEN amount ELSE 0 END) cheque_overpaid'),
                     DB::raw('SUM(CASE WHEN reference_id = 3 THEN amount ELSE 0 END) return_overpaid'),
                     DB::raw('SUM(CASE WHEN reference_id = 6 OR reference_id = 8 THEN amount ELSE 0 END) online_overpaid'),
                     DB::raw('SUM(CASE WHEN reference_id = 7 OR reference_id = 9 THEN amount ELSE 0 END) cash_deposit_overpaid')]);
 
         }

        return [
            "cash_overpaid" => $aa[0]->cash_overpaid,
            "cheque_overpaid" => $aa[0]->cheque_overpaid,
            "return_overpaid" => $aa[0]->return_overpaid,
            "online_overpaid" => $aa[0]->online_overpaid,
            "cash_deposit_overpaid" => $aa[0]->cash_deposit_overpaid
        ];

    }


}
