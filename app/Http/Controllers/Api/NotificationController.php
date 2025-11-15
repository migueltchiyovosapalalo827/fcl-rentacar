<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request, User $user): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $notifications = $user->notifications()->latest('created_at')->paginate($perPage);
        return response()->json($notifications);
    }

    public function store(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'message' => ['required','string'],
            'type' => ['nullable','in:reserva,pagamento,alerta,sistema'],
        ]);
        $notification = $user->notifications()->create([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'sistema',
            'is_read' => false,
            'created_at' => now(),
        ]);
        return response()->json($notification, 201);
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        $notification->update(['is_read' => true]);
        return response()->json($notification);
    }

    public function destroy(Notification $notification): JsonResponse
    {
        $notification->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
