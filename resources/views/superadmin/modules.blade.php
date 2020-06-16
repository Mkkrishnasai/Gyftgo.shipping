@extends('layouts.dashboard')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <label>Role :-</label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="{{ isset($role) ? $role->name : ''}}" readonly>
                </div>
            </div>
            <form id="modulesForm">
                @csrf
                <input type="hidden" name="id" value="{{ isset($role) ? $role->id : ''}}">
            <div class="container" style="margin-top: 50px; ">
                <div class="row">
                    <div class="col-lg-6" style="border-right-width: thin; border-right-style: solid">
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-lg-6">
                                <h4>Outbound order</h4>
                            </div>
                            <div class="col-lg-6">
                                <input type="checkbox" {{ (isset($modules) && in_array('outbound_order',$modules)) ? 'checked' : '' }} name="outbound_order" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-lg-6">
                                <h4>Inbound Order</h4>
                            </div>
                            <div class="col-lg-6">
                                <input type="checkbox" {{ (isset($modules) && in_array('inbound_order',$modules)) ? 'checked' : '' }} name="inbound_order" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center" style="margin-top: 40px;">
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <div id="loader" class="loading-gif" style="background: url({{asset('assets/loader.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5); background-size: 200px"></div>
@endsection

@section('scripts')
    <script>
        $(document).on('submit','#modulesForm', function (e) {
            e.preventDefault();
            var fd = $(this).serialize();
            $.ajax({
                method : 'POST',
                url : '{{ route('storeModules') }}',
                cache : false,
                processData  :false,
                dataType : 'json',
                data : fd,
                beforeSend : function() {
                      $('.loading-gif').show();
                },
                success : function (data) {
                    if(data.status == 200) {
                        window.location = '{{ route('manage_roles') }}';
                    }else{
                        $('.loading-gif').hide();
                        console.log(data);
                    }
                }
            });
        });
    </script>
@endsection
