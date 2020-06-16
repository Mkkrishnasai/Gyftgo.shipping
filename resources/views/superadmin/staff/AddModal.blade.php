<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-angle-double-down"></i>  Add Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="staffForm" method="POST" enctype="multipart/form-data">
                    <div class="col-md-12" style="margin: 10px;">
                        <input type="text" name="name"  id="staffname" class="form-control ShouldNotEmpty" placeholder="Full Name">
                    </div>
                    <div class="col-md-12" style="margin: 10px;">
                        <input type="text" name="email" id="staffemail" data-pattern="^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$" class="form-control ShouldNotEmpty ShouldMatchPattern" placeholder="Email" autocomplete="off">
                    </div>
                    <div class="col-md-12" style="margin: 10px;">
                        <input type="password" name="password" id="staffpassword" class="form-control ShouldNotEmpty" placeholder="password" autocomplete="off">
                    </div>
                    <div class="col-md-12" style="margin: 10px;">
                        <select class="form-control" id="roles" name="role_id">

                        </select>
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

@push('staff-scripts')
    <script>
        let validateShouldNotEmpty = () => {
            $(".validation-message").remove();
            let valid = true;
            $('#staffForm .ShouldNotEmpty').each((ind, val) => {
                if ($(val).attr('name') && ( !$(val).val() || $(val).val() === '')) {
                    valid = false;
                    $(val).attr('class','form-control ShouldNotEmpty is-invalid');
                    $(val).parent().append('<p class="validation-message" style="color: red;font-size: .7rem; margin-top: 10px;">Please fill in this field.</p>');
                    $(val).focus();
                    $(".collapse").removeClass('show');
                    $(val).parent().parents('.collapse').addClass('show');
                    return false;
                }
            });
            return valid;
        };

        let validateShouldMatchPattern = () => {
            let valid = true;
            $('#staffForm .ShouldMatchPattern').each((ind, val) => {
                if ($(val).attr('data-pattern')) {
                    let regExp = new RegExp($(val).data('pattern'));
                    let isValid = regExp.test($(val).val().trim())
                    if (!isValid) {
                        valid = false;
                        $(val).attr('class','form-control ShouldNotEmpty is-invalid');
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


        $('#AddModal').on('show.bs.modal', function (e) {
            $('#staffForm')[0].reset();
            getRoles();
        });

        function getRoles() {
            $.ajax({
                method: 'GET',
                url : '{{ route('getRoles') }}',
                cache : false,
                success : function (data) {
                    var _html = '';
                    $.each(data, function (i, data) {
                        _html += '<option value="'+data.id+'">'+data.name+'</option>';
                    })
                    $('#roles').html(_html);
                }
            })
        }

        $(document).on('submit', '#staffForm', function (e) {
            e.preventDefault();
            var fd = $(this).serialize();
            if(validateShouldNotEmpty() && validateShouldMatchPattern()){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    method : 'POST',
                    url : '{{ route('AddStaff') }}',
                    data : fd,
                    cache : false,
                    processData : false,
                    dataType : 'json',
                    beforeSend : function() {
                        $('#btnSubmit').prop('disabled', true);
                        $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success : function (data) {
                        $('#btnSubmit').prop('disabled', false);
                        if(data.status == 200){
                            $('#message').html('<h5 style="color: green">'+data.message+'</h5>');
                            $('#btnSubmit').html('<i class="fa fa-check-circle" aria-hidden="true"></i>');
                            window.location.reload();
                        }else{
                            $('#btnSubmit').html('Save Changes');
                            $.each(data.errors , function (i , d) {
                                $('#staffForm input[name="'+i+'"]').attr('class','form-control ShouldNotEmpty is-invalid');
                                $('#staffForm input[name="'+i+'"]').parent().append('<p class="validation-message" style="color: red;font-size: .7rem;">'+d+'</p>');
                            });
                        }
                    }
                });
            }
        });

        $('input').on('input',function () {
            $(this).parent().find('.validation-message').remove();
        });
    </script>
@endpush
