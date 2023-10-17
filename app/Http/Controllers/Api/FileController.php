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

    public function index(Request $request)
    {
        $user = Auth::user();
        $user = User::with('files')->find($user->id);
        $role = $user->getRoles()->first();
        $files = [];

        if ($role === 'admin' || $role === 'moderator') {
            $files = File::all();
        } else {
            $files = $user->files->map(function ($file) {
                $file->makeHidden('pivot');
                $file->makeHidden('user');
                return $file;
            });
        }

        $modifiedFiles = $files->map(function ($file) {
            if (isset($file->user)) {
                $file->user = $file->user->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            }

            return $file;
        });

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);


        if (!empty($modifiedFiles)) {
            return response()->json([
                'files' => $modifiedFiles,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'message' => 'No files',
            ]);
        }
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
