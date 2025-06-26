<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;


class TableController extends Controller
{

    // Check if the table is reserved in specific time
    public function checkTable($table, $from, $to)
    {
        $isTableReserved = Reservation::where('table_id', $table->id)
                ->where(function ($query) use ($from, $to) {
                    $query->whereBetween('from_time', [$from, $to])
                        ->orWhereBetween('to_time', [$from, $to])
                        ->orWhere(function ($q) use ($from, $to) {
                            $q->where('from_time', '<=', $from)
                                ->where('to_time', '>=', $to);
                        });
                })
                ->exists();

        return $isTableReserved;
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'guests' => 'required|integer|min:1',
            'from_time' => 'required|date',
            'to_time' => 'required|date|after:from_time',
        ]);

        $guests = $request->guests;
        $from = $request->from_time;
        $to = $request->to_time;

        $availableTables = Table::where('capacity', '>=', $guests)->get();

        foreach ($availableTables as $table) {
            $isTableReserved = $this->checkTable($table, $from, $to);

            if (!$isTableReserved) {
                return response()->json([
                    'available' => true,
                    'table_id' => $table->id,
                    'capacity' => $table->capacity,
                    'message' => 'Welcome! table '.$table->id.' is available'
                ]);
            }
        }

        return response()->json(['available' => false, 'message' => 'Sorry We have not available tables now'], 200);
    }

    public function reserveTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'guests' => 'required|integer|min:1',
            'from_time' => 'required|date',
            'to_time' => 'required|date|after:from_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $guests = $request->guests;
        $from = $request->from_time;
        $to = $request->to_time;

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->name]
        );

        $suitableTables = Table::where('capacity', '>=', $guests)->get();

        foreach ($suitableTables as $table) {
            $isTableReserved = $this->checkTable($table, $from, $to);

            if (!$isTableReserved) {
                $reservation = Reservation::create([
                    'customer_id' => $customer->id,
                    'table_id' => $table->id,
                    'from_time' => $from,
                    'to_time' => $to,
                ]);

                return response()->json([
                    'success' => true,
                    'reservation_id' => $reservation->id,
                    'table_id' => $table->id,
                    'message' => 'Welcome! Your Reservation Added Successfully',
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No available tables for the selected time.'
        ], 200);
    }

}
