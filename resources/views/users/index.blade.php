@extends('layouts.app')
@section('content-title', 'Data User')
@section('content')

    <div class="card">

        <div class="card-header justify-content-between">
            <h4 class="card-title mt-2">Data User</h4>
            <div class="d-flex justify-content-end">
                <x-users.form-user />
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <!-- <x-alert :errors="$errors" /> -->
                <table class="table table-bordered table-striped w-100" id="table2">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Email</th>
                            <th>Nama User</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <x-users.form-user :id="$item->id" />
                                        <a href="{{ route('users.destroy', $item->id) }}" data-confirm-delete="true"
                                            class="btn btn-danger ml-2">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection