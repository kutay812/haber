<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('auth'); // veya uygun admin yetki middleware'i
        $this->roleService = $roleService;
    }

    public function get_roles(): JsonResponse
    {
        $roles = $this->roleService->getAllRoles();
        return response()->json(['roles' => $roles], 200);
    }

    public function update_user_role(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|string|exists:roles,name'
        ]);
        try {
            $user = $this->roleService->assignRoleToUser($request->user_id, $request->role);
            return response()->json([
                'message' => 'Role successfully assigned to user',
                'user' => $user->name,
                'role' => $request->role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while assigning role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function remove_user_role(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|string|exists:roles,name'
        ]);
        try {
            $user = $this->roleService->removeRoleFromUser($request->user_id, $request->role);
            return response()->json([
                'message' => 'Role successfully removed from user',
                'user' => $user->name,
                'role' => $request->role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while removing role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get_user_roles(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
        try {
            $data = $this->roleService->getUserRoles($request->user_id);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while getting user roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
