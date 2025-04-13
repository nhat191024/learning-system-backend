<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ClassNotification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassNotificationController extends Controller
{
    public function getClassById($class_id)
    {
        $classNotification = ClassNotification::where('class_id', $class_id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (!$classNotification) {
            return response()->json([
                'message' => 'Class notification not found',
            ], 404);
        }

        $classNotification = $classNotification->map(function ($notification) {
            return [
                'id' => $notification->id,
                'class_id' => $notification->class_id,
                'content' => $notification->content,
                'created_at' => $notification->created_at->format('j M Y'),
            ];
        });

        return response()->json($classNotification, 200);
    }
}
