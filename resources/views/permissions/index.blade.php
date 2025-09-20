@extends('layouts.app')

@section('content')
<div class="container">


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Role Management</h2>
            </div>
            <div class="pull-right">
                @can('permission-create')
                <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.create') }}"><i class="fa fa-plus"></i>
                    Create New Role</a>
                @endcan

                <button type="button" class="btn btn-success btn-sm mb-2" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Create New Permission Change
                </button>

            </div>
        </div>
    </div>

    @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession

    <table class="table table-bordered">
        <tr>
            <th width="100px">No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($permissions as $key => $permission)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $permission->name }}</td>
            <td>
                <a class="btn btn-info btn-sm" href="{{ route('permissions.show', $permission->id) }}"><i
                        class="fa-solid fa-list"></i> Show</a>
                @can('role-edit')
                {{--  <a class="btn btn-primary btn-sm" href="{{ route('permissions.edit', $permission->id) }}"><i
                    class="fa-solid fa-pen-to-square"></i> Edit</a> --}}

                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                    data-bs-target="#exampleModal-{{ $permission->id }}">
                    Edit
                </button>
                @endcan
                @can('role-delete')
                <form method="POST" action="{{ route('permissions.destroy', $permission->id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i>
                        Delete</button>
                </form>
                @endcan
            </td>
        </tr>

        <!-- Modal for Editing -->
        <div class="modal fade" id="exampleModal-{{ $permission->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Permission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        <input type="text" name="name" class="form-control" placeholder="Name"
                                            value="{{ $permission->name }}">
                                        <input type="hidden" name="guard_name" class="form-control" placeholder="Web"
                                            value="web">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

    </table>


    <p class="text-center text-primary"><small>Client care pro</small></p>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                                <input type="hidden" name="guard_name" class="form-control" placeholder="Web"
                                    value="web">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection