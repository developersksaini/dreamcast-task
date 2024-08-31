<div class="card">
    <div class="card-header">
        Add Users
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data" id="userForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-lg-12">
                            <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name"
                                aria-label="Name">
                        </div>
                        <div class="col-md-6 col-lg-6 mt-2">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" placeholder="Enter email"
                                aria-label="Email" name="email">
                        </div>
                        <div class="col-md-6 col-lg-6 mt-2">
                            <label for="phone" class="form-label">Phone<span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" placeholder="Enter phone"
                                aria-label="Phone" name="phone">
                        </div>
                        <div class="col-md-6 col-lg-12 mt-2">
                            <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" rows="3" placeholder="Enter description" aria-label="Description" name="description"></textarea>
                        </div>
                        <div class="col-md-6 col-lg-6 mt-2">
                            <label for="role" class="form-label">Role<span class="text-danger">*</span></label>
                            <select class="form-select" aria-label="Role" name="role" id="role">
                                <option selected value="">Select Role</option>
                                @forelse($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                @empty
                                @endforelse
                              </select>
                        </div>
                        <div class="col-md-6 col-lg-6 mt-2">
                            <label for="image" class="form-label">Profile Image<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image"
                                aria-label="Profile Image" name="image">
                        </div>
                        <div class="col-md-6 col-lg-12 mt-2">
                            <button type="submit" class="btn btn-success"> Submit</button>
                            <button type="button" onclick="closeForm()" class="btn btn-warning"> Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>