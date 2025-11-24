<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header"><h3 class="text-center">Create Admin Account</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col"><label>First Name</label><input type="text" name="firstName" class="form-control" required></div>
                        <div class="col"><label>Last Name</label><input type="text" name="lastName" class="form-control" required></div>
                    </div>
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label>Username</label><input type="text" name="userName" class="form-control" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <div class="mb-3"><label>DOB</label><input type="date" name="dob" class="form-control" required></div>
                    <button class="btn btn-primary w-100">Create Admin</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>