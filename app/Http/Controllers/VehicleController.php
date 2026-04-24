<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function index(): JsonResponse
    {
        try {
        $vehicles = Vehicle::getFleetData();
        return response()->json(['data' => $vehicles]);

        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'error' => 'Unable to retrieve vehicles. Please try again later.'
            ], 500);
        }
    }
}