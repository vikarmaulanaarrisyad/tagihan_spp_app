<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function index()
    {
        return view('management_user.index');
    }

    public function data()
    {
        $userLogin = auth()->user();
        $users = User::admin()->with('role')->get();

        return datatables($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) use ($userLogin) {
                $aksi = '';

                if ($userLogin->role->name == 'admin') {
                    $aksi = '
                    <button class="btn btn-sm btn-primary" onclick="editData(`' . route('users.show', $user->id) . '`)"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteData(`' . route('users.destroy', $user->id) . '`,`' . $user->name . '`)"><i class="fas fa-trash"></i></button>
                ';
                } elseif ($user->role->name == 'operator') {
                    $aksi = '
                     <button class="btn btn-sm btn-danger" onclick="deleteData(`' . route('users.destroy', $user->id) . '`,`' . $user->name . '`)"><i class="fas fa-trash"></i></button>
                    ';
                } else {
                    $aksi = 'wali aksi';
                }

                return $aksi;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @throws
     * @return
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'foto' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Gagal menyimpan data, silahkan periksa kembali.'], 422);
        }

        $data = $request->except('foto');
        $data['password']  = bcrypt($request->password);
        $data['role_id'] = $request->role;
        if ($request->hasFile('foto')) return $data['foto'] = upload('user', $request->file('foto'), 'user');

        User::create($data);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * A description of the show function.
     *
     * @param datatype $id description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

        return response()->json(['data' => $user]);
    }

    /**
     * Update a user's information based on the given request and user ID.
     *
     * @param Request $request The request data
     * @param mixed $id The user ID
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Gagal menyimpan data, silahkan periksa kembali.'], 422);
        }

        $data = $request->except('foto', 'password');
        $data['role_id'] = $request->role;

        if ($request->hasFile('foto')) {
            if (Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = upload('user', $request->file('foto'), 'user');
        }

        if ($request->has('password') && $request->password != "") return $data['password'] = bcrypt($request->password);

        $user->update($data);

        return response()->json(['message' => 'Data berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        User::findOrfail($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function profil()
    {
        $profil = auth()->user();
        return view('admin.user.profil', compact('profil'));
    }

    /**
     * Update the user profile.
     *
     * @param Request $request
     * @throws \Exception description of exception
     * @return JsonResponse
     */
    public function updateProfil(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::findOrfail($userId);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email
        ];

        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $data['passwrord'] = bcrypt($request->password);
                } else {
                    return response()->json(['message' => 'Konfirmasi password tidak sesuai'], 422);
                }
            } else {
                return response()->json(['message' => 'Password lama tidak sesuai'], 422);
            }
        }

        if ($request->hasFile('foto')) {
            if (Storage::disk('public')->exists($request->foto)) {
                Storage::disk('public')->delete($request->foto);
            }

            $data['foto'] = upload('user', $request->file('foto'), 'user');
        }

        $user->update($data);

        return response()->json(['message' => 'Data profil berhasil diperbaharui'], 200);
    }

    /**
     * Searches for a role based on a keyword.
     *
     * @return Role[]
     */
    public function searchRole()
    {
        $keyword = request()->get('q');
        $roles = Role::where('name', "LIKE", "%$keyword%")->get();
        return $roles;
    }
}
