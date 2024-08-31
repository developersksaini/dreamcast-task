<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dreamcast Task | Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- toastify --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Sweet Alert css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
</head>

<body>
    <section>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dreamcast Task</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    </ul>
                </div>
            </div>
        </nav>
    </section>

    <div class="container">
        <section class="m-4">
            <div class="row">
                <div class="col-12" id="formDiv">

                </div>
            </div>
        </section>

        <section class="m-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <span>Users List</span>
                                <button id="addUserBtn" class="btn btn-primary" onclick="displayAddForm()">Add
                                    User</button>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped table-responsive" id="datatable"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Role</th>
                                                <th class="text-center">Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <form action="" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    {{-- toastify --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <!-- Sweet Alerts js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>

    <script>
        var base_url = '{{ url('/') }}';
        var userDataTable;
        userDataTable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: '{{ route('users.get-data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'image',
                    name: 'image',
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [{
                targets: 5, // index of the 'image' column
                className: 'text-center' // apply 'text-center' class to the cells of this column
            }]
        });

        function successToastPopup(message, gravity = 'top', position = 'right') {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: gravity, // `top` or `bottom`
                position: position, // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
            }).showToast();
        }

        function errorToastPopup(message, gravity = 'top', position = 'right') {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: gravity, // `top` or `bottom`
                position: position, // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(to right, #FF5F6D, #FFC371)",
                },
            }).showToast();
        }

        function displayAddForm() {
            $.ajax({
                url: `${base_url}/users/create`,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        $("#formDiv").html(response.html).fadeIn('slow');
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        function displayEditForm(encodedId) {
            $.ajax({
                url: `${base_url}/users/${encodedId}/edit`,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        $("#formDiv").html(response.html).fadeIn('slow');
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        function closeForm() {
            $("#formDiv").html("").fadeIn('slow');
        }

        // Use event delegation for dynamically loaded add forms
        $(document).on('submit', '#userForm', function(e) {
            e.preventDefault(); // Prevent default form submission
            $(this).attr('disabled', true);
            // Create a new FormData object
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'), // Replace with your Laravel route
                type: 'POST',
                data: formData, // Serialize form data
                contentType: false, // Prevent jQuery from automatically transforming the data into a query string
                processData: false, // Prevent jQuery from automatically processing the data
                success: function(response) {
                    if (response.status == true) {
                        successToastPopup(response.message);
                        closeForm();
                        userDataTable.draw();
                    } else if (response.status == 422) {
                        $.each(response.errors, function(key, value) {
                            errorToastPopup(value);
                        });
                    } else {
                        errorToastPopup(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle error response
                },
                complete: function() {
                    // Enable the submit button after form submission completes (whether success or error)
                    $(this).attr('disabled', false);
                }
            });
        });

        function confirmDelete(e) {
            let url = e.getAttribute('data-href');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this record!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('deleteForm');
                    let formData = $(form).serialize();
                    $.ajax({
                        url: url, // Replace with your Laravel route
                        type: 'DELETE',
                        data: formData, // Serialize form data
                        success: function(response) {
                            if (response.status == true) {
                                successToastPopup(response.message);
                                userDataTable.draw();
                            } else if (response.status == 422) {
                                $.each(response.errors, function(key, value) {
                                    errorToastPopup(value);
                                });
                            } else {
                                errorToastPopup(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error response
                        },
                        complete: function() {
                            // Enable the submit button after form submission completes (whether success or error)
                            $(this).attr('disabled', false);
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
