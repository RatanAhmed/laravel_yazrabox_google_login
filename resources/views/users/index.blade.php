<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users</title>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />

</head>

<body>
    <div class='container my-5'>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="">
                    <h4>{{ __('Users') }}</h4>
                </div>
                <div >
                    <a href="{{ url('/home') }}" class="btn btn-sm btn-primary"><i class='fa-solid fa-arrow-left'></i> Home</a>
                    <button class='btn btn-sm btn-success pull-right my-2' id='create-btn' data-id='".$row->id."'
                        data-bs-toggle='modal' data-bs-target='#createEditModal'><i class='fa-solid fa-plus-circle'></i> Create
                        User</button>
                </div>
            </div>

            <div class="card-body">
                <!-- Table -->
                <table id='user-table' class='datatable'>
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Name</td>
                            <td>Email</td>
                            <td>Created</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @include('users.modals.create-edit')
    </div>

    <!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        // CSRF Token
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
   $(document).ready(function(){

       // Initialize
       var userTable = $('#user-table').DataTable({
             processing: true,
             serverSide: true,
             order: [0,'desc'],
             ajax: "{{ route('getData') }}",
             columns: [
                 { data: 'id' },
                 { data: 'name' },
                 { data: 'email' },
                 { data: 'created_at' },
                 { data: 'action', orderable: false },
             ]
       });

       //update modal title
       $('#create-btn').on('click',function(){
            $('#createEditModal').find('.modal-title').text('Create User');
            $('#id').val('');
            $('#name').val('');
            $('#email').val('');
            $('#password').val('');
            $('#password-confirm').val('');
            if($('#password-div').hasClass("d-none")){
                $('#password-div').removeClass("d-none");
            }
            if($('#password-confirm-div').hasClass("d-none")){
                $('#password-confirm-div').removeClass("d-none");
            }
       });

       // Update record
       $('#user-table').on('click','.createEdit',function(){
            
            var id = $(this).data('id');

            $('#id').val(id);

            // AJAX request
            $.ajax({
                url: 'users/'+id+'/edit',
                type: 'get',
                data: {_token: CSRF_TOKEN,id: id},
                dataType: 'json',
                success: function(response){
                    
                    if(response.type == 1){
                        $('#createEditModal').find('.modal-title').text('Update User');
                        $('#password-div').addClass("d-none");
                        $('#password-confirm-div').addClass("d-none");
                        
                        $('#name').val(response.name);
                        $('#email').val(response.email);
                        
                        
                    }else{
                        Swal.fire("Something went wrong!!", response.message, "warning");
                    }
                },
            });

       });

       // Save user 
       $('#btn_save').click(function(){
            var id = $('#id').val();

            var name = $('#name').val().trim();
            var email = $('#email').val().trim();
            var password = $('#password').val().trim();
            var confirm_password = $('#password-confirm').val().trim();

            if( password != confirm_password){
                Swal.fire("Password Not Matched!!", "", "warning");
            }

            if(id != '' && name !='' && email != ''){

                 // AJAX request
                 $.ajax({
                     url: 'users/'+id,
                     type: 'put',
                     data: {_token: CSRF_TOKEN,id: id,name: name, email: email },
                     dataType: 'json',
                     success: function(response){
                         if(response){
                            Swal.fire(response.message, "", "success");
                                $('#id').val('');
                                $('#name').val('');
                                $('#email').val('');
                                $('#password').val('');
                                $('#password-confirm').val('');
                                
                                // Reload DataTable
                                $('#user-table').DataTable().ajax.reload(null, false);

                                // Close modal
                                $('#createEditModal').modal('toggle');
                                $('#password-div').removeClass("d-none");
                                $('#password-confirm-div').removeClass("d-none");
                         }else{
                            Swal.fire(response.message, "", "error");
                         }
                     },
                     error: function (response) {
                        $('#createEditModal').modal('toggle');
                        Swal.fire('Something went wrong!', response.message , "error");
                    },
                 });

            }else{
                $.ajax({
                     url: 'users',
                     type: 'POST',
                     data: {_token: CSRF_TOKEN, name : name, email : email, password : password, confirm_password : confirm_password },
                     dataType: 'json',
                     success: function(response){
                         if(response.type == 1){
                            // Empty and reset the values
                            $('#name','#email', '#password','#password-confirm').val('');

                            // Close modal
                            $('#createEditModal').modal('toggle');

                            // Reload DataTable
                            $('#user-table').DataTable().ajax.reload();
                            Swal.fire("Success", response.message, "success");

                         }else{
                            Swal.fire("Something went wrong!!", response.message, "error");
                         }
                     },
                     error: function (request, error) {
                        $('#createEditModal').modal('toggle');
                        Swal.fire('Something went wrong!', error, "error");
                    },
                 });
                
            }
       });

       // Delete record
       $('#user-table').on('click','.deleteUser',function(){
            var id = $(this).data('id');

            Swal.fire({
                    title: "Are you sure to delete?",
                    confirmButtonText: "Yes",
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'users/'+id,
                            type: 'delete',
                            data: {_token: CSRF_TOKEN,id: id},
                            success: function(response){
                                if(response.type == 1){
                                    Swal.fire("Success",response.message, "success");
                                    // Reload DataTable
                                    userTable.ajax.reload(null, false);
                                }else{
                                    Swal.fire("Something went wrong!!.", response.message, "info");
                                }
                            }
                        });
                        
                    } else if (result.isDenied) {
                        Swal.fire("Cancelled", "", "info");
                    }
                });

       });

   });

    </script>
</body>

</html>