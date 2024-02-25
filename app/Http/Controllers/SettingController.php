<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('setting.index', compact('setting'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'nama_sekolah' => 'required',
            'email_sekolah' => 'required',
            'telpon_sekolah' => 'required',
            'alamat_sekolah' => 'required',

        ];

        if ($request->has('pills') && $request->pills == 'logo') {
            $rules = [
                'logo_sekolah' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Gagal menyimpan data, silahkan periksa kembali'], 422);
        }

        $data = $request->except('logo_sekolah');

        if ($request->hasFile('logo_sekolah')) {
            if (Storage::disk('public')->exists($setting->logo_sekolah)) {
                Storage::disk('public')->delete($setting->logo_sekolah);
            }
            $data['logo_sekolah'] = upload('setting', $request->file('logo_sekolah'), 'setting');
        }

        $setting->update($data);

        return back()->with([
            'message' => 'Pengaturan berhasil diperbarui',
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
