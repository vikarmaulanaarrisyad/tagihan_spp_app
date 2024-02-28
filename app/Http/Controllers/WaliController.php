<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WaliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.wali.index');
    }

    public function data()
    {
        $userLogin = auth()->user();
        $users = User::wali()->with('role')->orderBy('id', 'DESC')->get();

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'foto' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Gagal menyimpan data, silahkan periksa kembali.'], 422);
        }

        $data = $request->except('foto');
        $data['password']  = bcrypt($request->password);
        $data['role_id'] = 3;
        if ($request->hasFile('foto')) return $data['foto'] = upload('user', $request->file('foto'), 'user');

        User::create($data);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
