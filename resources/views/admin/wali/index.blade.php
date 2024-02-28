@extends('layouts.app')

@section('title', 'Data Wali Murid')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Data Wali Murid</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12 col-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-between items-center">

                        <div class="float-right">
                            <button onclick="addForm(`{{ route('wali.store') }}`)" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                </x-slot>

                <x-table class="table-pengguna">
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @includeIf('admin.wali.form')
    @includeIf('admin.wali.scripts')
    @includeIf('layouts.includes.datatables')
    @includeIf('layouts.includes.select2')
@endsection
