@extends('layouts.dashboard')

@section('content')
    <div class="card shadow mb-4">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <form id="permissionForm">

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h4>Customer : </h4>
                </div>
                <div class="col-md-4">
                    <h4>{{ $customer->username }}</h4>
                </div>
                <div class="col-md-4">
                    <input type="hidden" name="id" value="{{ $customer->id }}">
                    <button class="btn btn-primary" type="submit" style="float: right">Set Permission</button>
                </div>
            </div>

            <div class="row" style="margin-top: 20px;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Create</th>
                            <th>Read</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Export</th>
                            <th>Import</th>
                        </tr>
                    </thead>
                    <tr>
                        <th><div class="row"><div class="col-md-6">Outbound Order : </div><div class="col-md-1"></div><input type="checkbox" {{ isset($permissions) && in_array('outbound',$permissions) ? 'checked' : '' }} name="outbound" data-toggle="toggle"></div></th>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('outbound_create',$permissions) ? 'checked' : '' }} disabled name="outbound_create" id="outbound_create" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('outbound_read',$permissions) ? 'checked' : '' }} disabled name="outbound_read" id="outbound_Read" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('outbound_edit',$permissions) ? 'checked' : '' }} disabled name="outbound_edit" id="outbound_Edit" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('outbound_delete',$permissions) ? 'checked' : '' }} disabled name="outbound_delete" id="outbound_delete" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('outbound_export',$permissions) ? 'checked' : '' }} disabled name="outbound_export" id="outbound_export" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('outbound_export',$permissions) ? 'checked' : '' }} disabled name="outbound_export" id="outbound_import" data-toggle="toggle"></td>
                    </tr>
                    <tr>
                        <th><div class="row"><div class="col-md-6">Inbound Order : </div><div class="col-md-1"></div><input type="checkbox" {{ isset($permissions) && in_array('inbound',$permissions) ? 'checked' : '' }} name="inbound" data-toggle="toggle"></div></th>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('inbound_create',$permissions) ? 'checked' : 'disabled' }} disabled name="inbound_create" id="inbound_create" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('inbound_read',$permissions) ? 'checked' : 'disabled' }} disabled name="inbound_read" id="inbound_Read" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('inbound_edit',$permissions) ? 'checked' : 'disabled' }} disabled name="inbound_edit" id="inbound_Edit" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('inbound_delete',$permissions) ? 'checked' : 'disabled' }} disabled name="inbound_delete" id="inbound_delete" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('inbound_export',$permissions) ? 'checked' : 'disabled' }} disabled name="inbound_export" id="inbound_export" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('inbound_import',$permissions) ? 'checked' : 'disabled' }} disabled name="inbound_import" id="inbound_import" data-toggle="toggle"></td>
                    </tr>
                    <tr>
                        <th><div class="row"><div class="col-md-6">Stock Items : </div><div class="col-md-1"></div><input type="checkbox" {{ isset($permissions) && in_array('stock_item',$permissions) ? 'checked' : '' }} name="stock_item" data-toggle="toggle"></div></th>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('stock_item_create',$permissions) ? 'checked' : 'disabled' }} disabled name="stock_item_create" id="stock_item_create" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('stock_item_read',$permissions) ? 'checked' : 'disabled' }} disabled name="stock_item_read" id="stock_item_Read" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('stock_item_edit',$permissions) ? 'checked' : 'disabled' }} disabled name="stock_item_edit" id="stock_item_Edit" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('stock_item_delete',$permissions) ? 'checked' : 'disabled' }} disabled name="stock_item_delete" id="stock_item_delete" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('stock_item_export',$permissions) ? 'checked' : 'disabled' }} disabled name="stock_item_export" id="stock_item_export" data-toggle="toggle"></td>
                        <td><input type="checkbox" {{ isset($permissions) && in_array('stock_item_import',$permissions) ? 'checked' : 'disabled' }} disabled name="stock_item_import" id="stock_item_import" data-toggle="toggle"></td>
                    </tr>
                </table>
                <div class="col-md-6">

                </div>
            </div>
        </div>
        </form>

    </div>
    <div id="loader" class="loading-gif" style="background: url({{asset('assets/loader.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5); background-size: 200px"></div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            if('{{ isset($permissions)&&(!empty($permissions)) }}') {
                var data = '@php echo json_encode($permissions); @endphp';
                console.log(JSON.parse(data));
                $.each(JSON.parse(data), function (i , d) {
                  if($('input[name = '+d+']').prop('checked') === true) {
                      setStatus(d, false);
                  }
                });
            }
        });
        $('input[type="checkbox"]').on('change', function () {
            if($(this).prop('checked') === true)
            {
                setStatus($(this).attr('name'),false);
            }else{
                setStatus($(this).attr('name'),true);
            }
        });

        function setStatus(id,booleanValue) {
            $('#'+id+'_create').attr('disabled', booleanValue);
            $('#'+id+'_Read').attr('disabled', booleanValue);
            $('#'+id+'_Edit').attr('disabled', booleanValue);
            $('#'+id+'_delete').attr('disabled', booleanValue);
            $('#'+id+'_export').attr('disabled', booleanValue);
            $('#'+id+'_import').attr('disabled', booleanValue);
        }

        $(document).on('submit', '#permissionForm',function (e) {
            e.preventDefault();
            var fd = $(this).serialize();
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                method : 'POST',
                url : '{{ route('store_customerPermissions') }}',
                data : fd,
                dataType : 'json',
                cache: false,
                processData  :false,
                beforeSend : function(){
                  $('.loading-gif').css({'display' : 'block'});
                },
                success : function (data) {
                    if(data.status ==200){
                        window.location.reload();
                    }else{
                        $('#loader').hide();
                        console.log(data);
                    }
                }
            });
        });


    </script>
@endsection
