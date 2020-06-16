@extends('layouts.dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Manage Roles/Departments</h6>
            <div class="row" style="float: right">
                <div class="col-md-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#AddModal"><i class="fa fa-plus-square"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="RoleTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Role/Department Name</th>
                        <th>Description</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-angle-double-down"></i>  Add Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <form id="roleForm" method="POST" enctype="multipart/form-data">
                       <div class="col-md-12" style="margin: 10px;">
                           <input type="text" name="name" class="form-control ShouldNotEmpty" placeholder="Role Name">
                       </div>
                       <div class="col-md-12" style="margin: 10px;">
                           <input type="text" name="description" class="form-control ShouldNotEmpty" placeholder="Description">
                       </div>
                       <div class="text-center" style="margin: 10px;" id="message">

                       </div>
                       <div class="text-center">
                           <button type="submit" id="btnSubmit" class="btn btn-primary">Save changes</button>
                       </div>
                   </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i>  Delete Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Are You Sure to delete the role !!</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a id="del"><button type="button" class="btn btn-danger">DELETE</button></a>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>

        let validateShouldNotEmpty = () => {
            $(".validation-message").remove();
            let valid = true;
            $('.ShouldNotEmpty').each((ind, val) => {
                if ($(val).attr('name') && ( !$(val).val() || $(val).val() === '')) {
                    valid = false;
                    $(val).parent().append('<p class="validation-message" style="color: red;font-size: .7rem;">Please fill in this field.</p>');
                    $(val).focus();
                    $(".collapse").removeClass('show');
                    $(val).parent().parents('.collapse').addClass('show');
                    return false;
                }
            });
            return valid;
        };

        // let validateShouldMatchPattern = () => {
        //     let valid = true;
        //     $('.ShouldMatchPattern').each((ind, val) => {
        //         if ($(val).attr('data-pattern')) {
        //             let regExp = new RegExp($(val).data('pattern'));
        //             let isValid = regExp.test($(val).val().trim())
        //             if (!isValid) {
        //                 valid = false;
        //                 $(val).parent().append('<p class="validation-message" style="color: red;font-size: .7rem;">Please enter a valid format.</p>');
        //                 $(val).focus();
        //                 $(".collapse").removeClass('show');
        //                 $(val).parent().parents('.collapse').addClass('show');
        //                 return false;
        //             }
        //         }
        //     });
        //     return valid;
        // };

        $(document).ready(function () {
            var table = $('#RoleTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": '{{ route('Roletable') }}',
                columns : [
                    { data : 'name' , name : 'name'},
                    { data : 'description', name : 'description'},
                    { data : 'created_at', name : 'created_at'},
                    { data : 'updated_at', name : 'updated_at'},
                    { data : 'actions', name: 'actions'}
                ],
                colReorder: true,
                "order": [[ 3, "desc" ]]
            });
        });

        $('#DeleteModal').on('show.bs.modal', function (e) {
            $(this).find('#del').attr('href',$(e.relatedTarget).data('href'));
        });

        $(document).on('submit', '#roleForm', function (e) {
            e.preventDefault();
            var fd = $(this).serialize();
            if(validateShouldNotEmpty())
            {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    method : 'POST',
                    url  : '{{ route('AddRole') }}',
                    data : fd,
                    cache : false,
                    processData: false,
                    dataType : 'json',
                    beforeSend : function() {
                          $('#btnSubmit').prop('disabled', true);
                          $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success : function (data) {
                        $('#btnSubmit').prop('disabled', false);
                        $('#message').html('<h5 style="color: green">'+data.message+'</h5>');
                        $('#btnSubmit').html('<i class="fa fa-check-circle" aria-hidden="true"></i>');
                        window.location.reload();
                    },
                });
            }
        });
    </script>
@endsection
