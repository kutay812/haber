<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view');
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = User::query()
            ->with('roles')
            ->when($request->filled('search'), function($q) use ($request) {
                $searchTerm = $request->search;
                $q->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            })
            ->orderBy('created_at', 'desc');

        $users = $query->paginate(10)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Rol id'lerini isimlere çevirerek atama!
        $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        $user->assignRole($roleNames);

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit(User $user)
    {
        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Super Admin kullanıcılarını düzenleme yetkiniz yok.');
        }

        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Super Admin kullanıcılarını düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        // Rol id'lerini isimlere çevirerek güncelle!
        $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        $user->syncRoles($roleNames);

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            return back()->with('error', 'Super Admin kullanıcıları silinemez.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search');
            
            if (empty($search)) {
                return response()->json(['items' => []], 200);
            }

            $items = User::query()
                ->with('roles')
                ->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'text' => $user->name,
                        'category' => $user->email
                    ];
                });

            return response()->json(['items' => $items], 200);

        } catch (\Exception $e) {
            \Log::error('Kullanıcı arama hatası: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Arama işlemi sırasında bir hata oluştu'
            ], 500);
        }
    }
}
