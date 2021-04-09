<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class ReportsController extends Controller
{
    public function rooms(Request $filters)
    {
        $startDate = Carbon::createFromFormat('d/m/Y H:i:s', $filters->start_date);
        $endDate = Carbon::createFromFormat('d/m/Y H:i:s', $filters->end_date);

        $roomReservations = DB::table("reservation_rooms as rr")
            ->join('rooms as r', 'r.id', '=', 'rr.room_id')
            ->join('reservations as re', 're.id', '=', 'rr.reservation_id')
            ->select([
                DB::raw("COUNT(r.id) as total"),
                'r.name as room'
            ])
            ->where('re.reservation_state_id', '!=', '4')
            ->whereBetween("rr.created_at", [$startDate, $endDate])
            ->groupBy('room')
            ->limit(10);

        $roomTotals = DB::table("reservation_rooms as rr")
            ->join('rooms as r', 'r.id', '=', 'rr.room_id')
            ->join('reservations as re', 're.id', '=', 'rr.reservation_id')
            ->select([
                DB::raw("SUM(rr.total_price * rr.rate_value) as total"),
                'r.name as room'
            ])
            ->where('re.reservation_state_id', '!=', '4')
            ->whereBetween("rr.created_at", [$startDate, $endDate])
            ->groupBy('room');

        if ($filters->room_id >= 1) {
            $roomReservations->where('r.id', '=', $filters->room_id);
            $roomTotals->where('r.id', '=', $filters->room_id);
        }
        if ($filters->room_category_id >= 1) {
            $roomReservations->where('r.room_category_id', '=', $filters->room_category_id);
            $roomTotals->where('r.room_category_id', '=', $filters->room_category_id);
        }

        $roomReservations = $roomReservations->get();
        $roomTotals = $roomTotals->get();

        return $this->successResponse([
            'room_reservations' => $roomReservations,
            'room_totals' => $roomTotals,
        ]);
    }

    public function dayli(Request $filters)
    {
       
      
            $createdAt = Carbon::parse($filters->date);
            $date = $createdAt->format('Y-d-m');
            //$date = str_replace('/', '-', substr($filters->date, 0, 9));
            //echo $date;
            $startDate =  $date . ' 00:00:00';//Carbon::createFromFormat('d-m-Y H:i:s', $date . ' 00:00:00');
            $endDate = $date . ' 23:59:59';// Carbon::createFromFormat('d-m-Y H:i:s', $date . ' 23:59:59');
            //return($startDate);
            $cashRegisterMovements = DB::table('cash_register_movements as crm')
                ->join('cash_register_movement_types as crmt', 'crmt.id', '=', 'crm.cash_register_movement_type_id')
                ->join('currencies as cu', 'cu.id', '=', 'crm.currency_id')
                ->select([
                    DB::raw("SUM(crm.amount) as total"),
                    "crmt.name as type_name",
                    "crmt.id as type_id",
                    "crmt.in_out as in_out",
                    "cu.plural_name as currency_name",
                    "cu.symbol as currency_symbol",
                ])
                ->groupBy(['type_name', 'type_id', 'in_out', 'currency_name', 'currency_symbol'])
                ->where('crmt.id', '!=', 8)
                ->whereBetween('crm.created_at', [$startDate, $endDate]);

            $incomeAndExpenses = DB::table('cash_register_movements as crm')
                ->join('cash_register_movement_types as crmt', 'crmt.id', '=', 'crm.cash_register_movement_type_id')
                ->join('currencies as cu', 'cu.id', '=', 'crm.currency_id')
                ->select([
                    DB::raw("SUM(crm.amount) as total"),
                    "crmt.in_out as in_out",
                    "cu.plural_name as currency_name",
                    "cu.symbol as currency_symbol",
                ])
                ->groupBy(['in_out', 'currency_name', 'currency_symbol'])
                ->where('crmt.id', '!=', 8)
                ->whereBetween('crm.created_at', [$startDate, $endDate]);

            $cashRegisterMovementsByPaymentMethods = DB::table('cash_register_movements as crm')
                ->join('cash_register_movement_types as crmt', 'crmt.id', '=', 'crm.cash_register_movement_type_id')
                ->join('currencies as cu', 'cu.id', '=', 'crm.currency_id')
                ->join('payment_methods as pm', 'pm.id', '=', 'crm.payment_method_id')
                ->select([
                    DB::raw("SUM(crm.amount) as total"),
                    "pm.name as payment_method_name",
                    "crmt.in_out as in_out",
                    "cu.plural_name as currency_name",
                    "cu.symbol as currency_symbol",
                ])
                ->groupBy(['payment_method_name', 'in_out', 'currency_name', 'currency_symbol'])
                ->where('crmt.id', '!=', 8)
                ->whereBetween('crm.created_at', [$startDate, $endDate]);

            $currencyRates = CurrencyRate::where('rate_date', '=', $startDate)->with('currency')->get();

            if ($filters->cash_register_id >= 1) {
                $cashRegisterMovements->where('crm.cash_register_id', '=', $filters->cash_register_id);
                $incomeAndExpenses->where('crm.cash_register_id', '=', $filters->cash_register_id);
                $cashRegisterMovementsByPaymentMethods->where('crm.cash_register_id', '=', $filters->cash_register_id);
            }

            $cashRegisterMovements = $cashRegisterMovements->get();
            $incomeAndExpenses = $incomeAndExpenses->get();
            $cashRegisterMovementsByPaymentMethods = $cashRegisterMovementsByPaymentMethods->get();

            return $this->successResponse([
                'cash_register_movements' => $cashRegisterMovements,
                'income_and_expenses' => $incomeAndExpenses,
                'currency_rates' => $currencyRates,
                'cash_register_movements_by_payment_methods' => $cashRegisterMovementsByPaymentMethods,
            ]);
        
    }

    public function reservations(Request $filters)
    {
        $startDate = Carbon::createFromFormat('d/m/Y H:i:s', $filters->start_date);
        $endDate = Carbon::createFromFormat('d/m/Y H:i:s', $filters->end_date);

        $daysDiff = $startDate->diffInDays($endDate);
        $weeksDiff = $startDate->diffInWeeks($endDate);
        $monthsDiff = $startDate->diffInMonths($endDate);
        $groupBy = 'days';

        $reservations = DB::table("reservations as r")
            ->join('reservation_totals as rt', 'r.id', '=', 'rt.reservation_id')
            ->where('r.reservation_state_id', '!=', '4')
            ->where('rt.total_by', '=', '0')
            ->whereBetween("r.created_at", [$startDate, $endDate]);

        if ($daysDiff <= 15) {
            $reservations->select([
                DB::raw("COUNT(r.id) as reservations"),
                DB::raw("SUM(rt.total) as total"),
                DB::raw("DATE(r.created_at) as label"),
            ])->groupBy('label');
            $groupBy = 'day';
        } else if ($weeksDiff <= 8) {
            $reservations->select([
                DB::raw("COUNT(r.id) as reservations"),
                DB::raw("SUM(rt.total) as total"),
                DB::raw("CONCAT(DATE_ADD(DATE(r.created_at), INTERVAL(1-DAYOFWEEK(r.created_at)) DAY), ' - ', DATE_ADD(DATE(r.created_at), INTERVAL(7-DAYOFWEEK(r.created_at)) DAY)) as label"),
            ])->groupBy('label');
            $groupBy = 'week';
        } else if ($monthsDiff <= 8) {
            $reservations->select([
                DB::raw("COUNT(r.id) as reservations"),
                DB::raw("SUM(rt.total) as total"),
                DB::raw("MONTH(r.created_at) as label"),
            ])->groupBy('label');
            $groupBy = 'month';
        } else {
            $reservations->select([
                DB::raw("COUNT(r.id) as reservations"),
                DB::raw("SUM(rt.total) as total"),
                DB::raw("YEAR(r.created_at) as label"),
            ])->groupBy('label');
            $groupBy = 'year';
        }

        $reservations = $reservations->get();

        return $this->successResponse([
            'reservations' => $reservations,
            'group_by' => $groupBy
        ]);
    }

    public function sales(Request $filters)
    {
        $startDate = Carbon::createFromFormat('d/m/Y H:i:s', $filters->start_date);
        $endDate = Carbon::createFromFormat('d/m/Y H:i:s', $filters->end_date);

        $daysDiff = $startDate->diffInDays($endDate);
        $weeksDiff = $startDate->diffInWeeks($endDate);
        $monthsDiff = $startDate->diffInMonths($endDate);
        $groupBy = 'days';

        $sales = DB::table("sales as s")
            ->join('sale_totals as st', 's.id', '=', 'st.sale_id')
            ->where('s.sale_state_id', '!=', '4')
            ->whereBetween("s.created_at", [$startDate, $endDate]);
            
        $salesPerPaymentMethod = DB::table("sales as s")
        ->join('sale_totals as st', 's.id', '=', 'st.sale_id')
        ->join('sale_payments as sp', 's.id', '=', 'sp.sale_id')
        ->join('payment_methods as p', 'p.id', '=', 'sp.payment_method_id')
        ->where('s.sale_state_id', '!=', '4')
        ->whereBetween("s.created_at", [$startDate, $endDate])
        ->select([
            DB::raw("SUM(st.total) as total"),
            "p.name as payment_method",
        ])->groupBy('payment_method')
        ->get();

        if ($daysDiff <= 15) {
            $sales->select([
                DB::raw("COUNT(s.id) as sales"),
                DB::raw("SUM(st.total) as total"),
                DB::raw("DATE(s.created_at) as label"),
            ])->groupBy('label');
            $groupBy = 'day';
        } else if ($weeksDiff <= 8) {
            $sales->select([
                DB::raw("COUNT(s.id) as sales"),
                DB::raw("SUM(st.total) as total"),
                DB::raw("CONCAT(DATE_ADD(DATE(s.created_at), INTERVAL(1-DAYOFWEEK(s.created_at)) DAY), ' - ', DATE_ADD(DATE(s.created_at), INTERVAL(7-DAYOFWEEK(s.created_at)) DAY)) as label"),
            ])->groupBy('label');
            $groupBy = 'week';
        } else if ($monthsDiff <= 8) {
            $sales->select([
                DB::raw("COUNT(s.id) as sales"),
                DB::raw("SUM(st.total) as total"),
                DB::raw("MONTH(s.created_at) as label"),
            ])->groupBy('label');
            $groupBy = 'month';
        } else {
            $sales->select([
                DB::raw("COUNT(s.id) as sales"),
                DB::raw("SUM(st.total) as total"),
                DB::raw("YEAR(s.created_at) as label"),
            ])->groupBy('label');
            $groupBy = 'year';
        }

        $sales = $sales->get();
        
        $topProducts = DB::table("sales as s")
        ->join('sale_products as sp', 's.id', '=', 'sp.sale_id')
        ->join('products as p', 'p.id', '=', 'sp.product_id')
        ->select([
            DB::raw("COUNT(sp.id) as sales"),
            "p.name as product",
        ])
        ->where('s.sale_state_id', '!=', '4')
        ->whereBetween("s.created_at", [$startDate, $endDate])
        ->groupBy('p.id')
        ->orderBy('product', 'desc')
        ->limit(10)
        ->get();
        
        $topServices = DB::table("sales as s")
        ->join('sale_services as ss', 's.id', '=', 'ss.sale_id')
        ->join('services as ser', 'ser.id', '=', 'ss.service_id')
        ->select([
            DB::raw("COUNT(ss.id) as sales"),
            "ser.name as service",
        ])
        ->where('s.sale_state_id', '!=', '4')
        ->whereBetween("s.created_at", [$startDate, $endDate])
        ->groupBy('ser.id')
        ->orderBy('service', 'desc')
        ->limit(10)
        ->get();

        return $this->successResponse([
            'sales' => $sales,
            'sales_per_payment_method' => $salesPerPaymentMethod,
            'top_products' => $topProducts,
            'top_services' => $topServices,
            'group_by' => $groupBy
        ]);
    }
}
