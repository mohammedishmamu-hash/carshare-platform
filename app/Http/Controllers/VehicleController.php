<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Load all vehicles with their location and booking count
            $vehicles = Vehicle::with('location')
                ->withCount('bookings')
                ->get();

            // If no vehicles in DB, return empty array gracefully
            if ($vehicles->isEmpty()) {
                return response()->json(['data' => []]);
            }

            // Shape the data we want to return to the frontend
            $data = $vehicles->map(function ($vehicle) {
                return [
                    'id'             => $vehicle->id,
                    'make'           => $vehicle->make,
                    'model'          => $vehicle->model,
                    'year'           => $vehicle->year,
                    'colour'         => $vehicle->colour,
                    'location'       => $vehicle->location->description
                                        ?? 'Location unavailable',
                    'total_bookings' => $vehicle->bookings_count,
                    'is_available'   => $vehicle->isAvailable(),
                ];
            });

            return response()->json(['data' => $data]);

        } catch (\Exception $e) {
            // Log the error and return a clean message to the frontend
            report($e);

            return response()->json([
                'error' => 'Unable to retrieve vehicles. Please try again later.'
            ], 500);
        }
    }
}