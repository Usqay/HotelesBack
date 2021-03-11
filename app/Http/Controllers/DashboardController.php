<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;

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

    public function data(){

        $labels = [];
        $data = [];

        $disponible = Room::where('room_status_id','1')->count();
        $ocupada = Room::where('room_status_id','2')->count();
        $mantenimiento = Room::where('room_status_id','3')->count();

        $datos = DB::select("
            SELECT date(r.start_date) as start_date, SUM(rr.total_price) as total
            FROM reservations r
            INNER JOIN reservation_rooms rr
                ON rr.reservation_id = r.id
            WHERE r.reservation_state_id IN (2,3)
                AND DATEDIFF(CURRENT_DATE(), r.start_date) < 8
            GROUP BY r.start_date
        ");

        foreach($datos as $dato)
        {
            $labels[] = $dato->start_date;
            $data[] = $dato->total;
        }

        $line = [
            'labels' => $labels,
            'data' => $data,

        ];

        return compact('line','mantenimiento','disponible','ocupada','mantenimiento');


    }
}
