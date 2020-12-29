<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $reservationsPerRooms = DB::table('reservations as r')
        ->join('reservation_rooms as rr', 'rr.reservation_id', '=', 'r.id')
        ->leftJoin('clients as c', 'c.id', '=', 'r.client_id')
        ->leftJoin('people as p', 'p.id', '=', 'c.people_id')
        ->select([
            "rr.room_id as room_id",
            "r.start_date as start_date",
            "r.end_date as end_date",
            "p.full_name as people",
            "r.reservation_state_id",
            "r.id as reservation_id",
        ])
        ->whereIn('r.reservation_state_id',[1,2])
        ->get();

        return $this->successResponse([
            'reservations_per_rooms' => $reservationsPerRooms
        ]);
    }
}
