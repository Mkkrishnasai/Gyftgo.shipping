<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-angle-double-down"></i>  Edit Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="EditStaffForm" method="POST" enctype="multipart/form-data">
                    <div class="col-md-12" style="margin: 10px;">
                        <input type="hidden" name="id" id="user_id">
                        <input type="text" name="name" id="name" class="form-control ShouldNotEmpty" placeholder="Full Name">
                    </div>
                    <div class="col-md-12" style="margin: 10px;">
                        <input type="text" name="email" id="email" data-pattern="^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$" class="form-control ShouldNotEmpty" placeholder="Email" autocomplete="off">
                    </div>
                    <div class="col-md-12" style="margin: 10px;">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password (Leave empty if no need to update)" autocomplete="off">
                    </div>
                    <div class="col-md-12" style="margin: 10px;">
                        <select class="form-control" id="Editroles" name="role_id">

                        </select>
                    </div>
                    <div class="text-center" style="margin: 10px;" id="emessage">

                    </div>
                    <div class="text-center">
                        <button type="submit" id="ebtnSubmit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('staff-scripts')
    <script>
        $('#EditModal').on('show.bs.modal', function (e) {
            $('#EditStaffForm')[0].reset();
            let data = getData($(e.relatedTarget).data('id'));
        });

        let evalidateShouldNotEmpty = () => {
            $(".validation-message").remove();
            let valid = true;
            $('#EditStaffForm .ShouldNotEmpty').each((ind, val) => {
                if ($(val).attr('name') && ( !$(val).val() || $(val).val() === '')) {
                    valid = false;
                    $(val).parent().append('<p class="validation-message" style="color: red;font-size: .7rem; margin-top: 10px;">Please fill in this field.</p>');
                    $(val).focus();
                    $(".collapse").removeClass('show');
                    $(val).parent().parents('.collapse').addClass('show');
                    return false;
                }
            });
            return valid;
        };

        let evalidateShouldMatchPattern = () => {
            let valid = true;
            $('#EditStaffForm .ShouldMatchPattern').each((ind, val) => {
                if ($(val).attr('data-pattern')) {
                    let regExp = new RegExp($(val).data('pattern'));
                    let isValid = regExp.test($(val).val().trim())
                    if (!isValid) {
                        valid = false;
                        $(val).parent().append('<p class="validation-message" style="color: red;font-size: .7rem;">Please enter a valid format.</p>');
                        $(val).focus();
                        $(".collapse").removeClass('show');
                        $(val).parent().parents('.collapse').addClass('show');
                        return false;
                    }
                }
            });
            return valid;
        };

        $(document).on('submit', '#EditStaffForm', function (e) {
            e.preventDefault();
            var fd = $(this).serialize();
            console.log(fd);
            if(evalidateShouldNotEmpty() && evalidateShouldMatchPattern())
            {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    method : 'POST',
                    url : '{{ route('UpdateStaff') }}',
                    dataType: 'json',
                    data : fd,
                    cache : false,
                    processData: false,
                    beforeSend : function () {
                        $('#ebtnSubmit').prop('disabled', true);
                        $('#ebtnSubmit').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success : function (data) {
                        $('#ebtnSubmit').prop('disabled', false);

                        if(data.status == 200) {
                            $('#emessage').html('<h5 style="color: green">'+data.message+'</h5>');
                            $('#ebtnSubmit').html('<i class="fa fa-check-circle" aria-hidden="true"></i>');
                            window.location.reload();
                        }else{
                            $('#ebtnSubmit').html('Save Changes');
                            $.each(data.errors , function (i , d) {
                                $('#EditStaffForm input[name="'+i+'"]').attr('class','form-control ShouldNotEmpty is-invalid');
                                $('#EditStaffForm input[name="'+i+'"]').parent().append('<p class="validation-message" style="color: red;font-size: .7rem;">'+d+'</p>');
                            });
                        }

                    }
                });
            }
        });

        function getData(id) {
            var user = {};
            $.ajax({
                method : 'GET',
                url : '{{ route('getStaffData') }}',
                cache : false,
                data : {'id' : id},
                success : function (response) {
                    $('#user_id').val(response.id);
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#password').val('');
                    var _html = '';
                    $.each(response.roles, function (i, data) {
                        _html += '<option value="'+data.id+'" '+((response.role_id == data.id) ? 'selected' : '')+'>'+data.name+'</option>';
                    });
                    $('#Editroles').html(_html);
                }
            });
        }
    </script>
@endpush
