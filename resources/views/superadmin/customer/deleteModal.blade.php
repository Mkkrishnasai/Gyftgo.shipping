<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i>  Delete Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2>Are you sure want to delete the Customer</h2>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete_id">
                <a id="delete"><button class="btn btn-danger">Delete</button></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('customer-scripts')
    {{--    var url = '{{ route('DeleteStaff','id') }}';--}}
    {{--    url = url.replace('id',);--}}
    <script>
        $('#delete').on('click', function (e) {
            console.log($('#delete_id').val());
            e.preventDefault();
            var url = '{{ route('customer.destroy','id') }}';
            url = url.replace('id', $('#delete_id').val())
            $.ajax({
                headers: {
                    'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content')
                },
                method : 'DELETE',
                url : url,
                cache : false,
                beforeSend : function () {
                    $('#delete').prop('disabled', true);
                    $('#delete').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success : function (data) {
                    // window.location.reload();
                    if(data.status ==200){
                        $('#delete').prop('disabled', false);
                        window.location.reload();
                    }else{
                        window.location.reload();
                    }
                }
            });
        });
    </script>
@endpush
<?php
