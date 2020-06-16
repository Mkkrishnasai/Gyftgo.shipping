@extends('layouts.dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('superadmin.customer.table')
    @include('superadmin.customer.deleteModal')
@endsection

@section('scripts')
    <script>
        $('#DeleteModal').on('show.bs.modal', function (e) {
            $('#delete_id').val($(e.relatedTarget).data('id'));
        });
    </script>
@stack('customer-scripts')
@endsection
