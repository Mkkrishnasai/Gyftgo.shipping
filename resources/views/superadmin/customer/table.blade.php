<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Customers</h6>
        <div class="row" style="float: right">
            <div class="col-md-2">
                <a href="{{ route('customer.create') }}"><button class="btn btn-primary"><i class="fa fa-plus-square"></i></button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="cTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Username</th>
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

@push('customer-scripts')
    <script>
        $(document).ready(function (e) {
            var table = $('#cTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": '{{ route('customer_datatable') }}',
                columns : [
                    { data : 'username' , name : 'username'},
                    { data : 'email', name : 'email'},
                    { data : 'created_at', name : 'created_at'},
                    { data : 'updated_at', name : 'updated_at'},
                    { data : 'actions', name: 'actions'}
                ],
                colReorder: true,
                "order": [[ 3, "desc" ]]
            });
        });
    </script>
@endpush
