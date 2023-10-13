<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $role = $user->getRoles()->first();

        if ($role === 'admin') {
            $files = File::all();
        } elseif ($role === 'moderator') {
            $files = File::all();
        } else {
            $files = $user->files;
        }

        foreach ($files as $file) {
            $users = $file->user;
            $modifiedUsers = [];

            foreach ($users as $user) {
                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
                $modifiedUsers[] = $userData;
            }

            $file->user = $modifiedUsers;
        }

        return response()->json([
            'files' => $files,
        ]);
    }

    public function uploadFile(Request $request, FileService $fileService)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,gif',
        ]);

        $userId = auth()->user()->id;
        $fileService->uploadFile($request, $userId);

        return response()->json([
            'message' => 'Success create file'
        ], 200);
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $file = File::find($id);

        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        $adminUserIds = $adminUsers->pluck('id')->toArray();

        if (!$file) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        $userRole = $user->getRoles()->first();

        if ($userRole === 'admin') {
            $file->delete();
            return response()->json([
                'message' => 'File deleted successfully'
            ], 200);
        } elseif ($userRole === 'moderator') {
            $fileOwnerId = $file->user->pluck('id')->toArray();

            if ($adminUserIds[0] !== $fileOwnerId[0]) {
                $file->delete();
                return response()->json([
                    'message' => 'File deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You do not have permission to delete this file'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'You do not have permission to delete files'
            ], 403);
        }
    }
}
