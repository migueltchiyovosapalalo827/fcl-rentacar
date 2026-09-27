<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintenanceReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $reports = MaintenanceReport::with(['car', 'technician', 'reservation'])->latest()->paginate($perPage);

        return response()->json($reports);
    }

    public function show(MaintenanceReport $maintenanceReport): JsonResponse
    {
        $maintenanceReport->load(['car', 'technician', 'reservation']);

        return response()->json($maintenanceReport);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'technician_id' => ['required', 'exists:users,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'description' => ['required', 'string'],
            'status' => ['nullable', 'in:em_analise,em_reparacao,concluido'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $report = MaintenanceReport::create($data);

        return response()->json($report, 201);
    }

    public function update(Request $request, MaintenanceReport $maintenanceReport): JsonResponse
    {
        $data = $request->validate([
            'car_id' => ['sometimes', 'exists:cars,id'],
            'technician_id' => ['sometimes', 'exists:users,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', 'in:em_analise,em_reparacao,concluido'],
            'cost' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $maintenanceReport->update($data);

        return response()->json($maintenanceReport);
    }

    public function destroy(MaintenanceReport $maintenanceReport): JsonResponse
    {
        $maintenanceReport->delete();

        return response()->json(null, 204);
    }
}
