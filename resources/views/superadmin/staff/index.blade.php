@extends('layouts.dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Manage Staff</h6>
            <div class="row" style="float: right">
                <div class="col-md-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#AddModal"><i class="fa fa-plus-square"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="StaffTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


{{--    Add Modal--}}

@include('superadmin.staff.AddModal')

{{--    //edit modal--}}
@include('superadmin.staff.editModal')

{{--    Delete modal--}}
    @include('superadmin.staff.DeleteModal')

@endsection

@section('scripts')
    <script>
        $(document).ready(function (e) {
            var table = $('#StaffTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": '{{ route('StaffTable') }}',
                columns : [
                    { data : 'name' , name : 'name'},
                    { data : 'email', name : 'email'},
                    { data : 'created_at', name : 'created_at'},
                    { data : 'updated_at', name : 'updated_at'},
                    { data : 'actions', name: 'actions'}
                ],
                colReorder: true,
                "order": [[ 3, "desc" ]]
            });
        });

        $('#DeleteModal').on('show.bs.modal', function (e) {
            $('#delete_id').val($(e.relatedTarget).data('id'));
        });


    </script>
    @stack('staff-scripts')
@endsection
