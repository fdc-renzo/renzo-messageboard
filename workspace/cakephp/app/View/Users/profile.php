<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo h($page); ?></h1>

    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $this->Html->url(['controller' => 'Users', 'action' => 'profileView']); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-eye"></i>
            View Profile
        </a>

        <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary ms-1 changeAccModal" data-type="email">
            <i class="bi bi-envelope"></i>
            Change Email
        </a>

        <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary ms-1 changeAccModal" data-type="password">
            <i class="bi bi-lock"></i>
            Reset Password
        </a>
    </div>

</div>

<form id="updateAccount" action="/cakephp/users/profile" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xl-3">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="profile" name="profile" accept=".png, .jpg, .gif, .avif" />
                            <label for="profile"><i class="bi bi-pencil-fill edit-pencil"></i></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background-image: url(<?php echo $userdata['User']['profile_url'] ?? 'https://i.ibb.co/pW9DJrH/blank-twitter-icon.webp'; ?>);">
                            </div>
                        </div>
                    </div>
                    <div class="error-message mt-1 mb-2 fs-11" id="error-profile"></div>
                </div>

            </div>
        </div>


        <div class="col-xl-9">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small mb-1" for="name">Name</label>
                        <input class="form-control" id="name" name="name" type="text" placeholder="Enter your name" value="<?php echo $userdata['User']['name']; ?>">
                        <div class="error-message mt-1 mb-2 fs-11" id="error-name"></div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="birthdate">Birthdate</label>
                            <input class="form-control" id="birthdate" name="birthdate" type="text" value="<?php echo $userdata['User']['birthdate'] ?? '0000-00-00'; ?>">
                            <div class="error-message mt-1 mb-2 fs-11" id="error-birthdate"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="inputLocation">Gender</label>
                            <div class="d-flex mt-1">
                                <div class="d-flex">
                                    <div><input class="gender" name="gender" type="radio" value="Male" <?php echo trim($userdata['User']['gender']) === 'Male' ? 'checked' : ''; ?>></div>
                                    <div class="mx-2">Male</div>
                                </div>
                                <div class="d-flex">
                                    <div><input class="gender" name="gender" type="radio" value="Female" <?php echo trim($userdata['User']['gender']) === 'Female' ? 'checked' : ''; ?>></div>
                                    <div class="mx-2">Female</div>
                                </div>
                            </div>
                            <div class="error-message mt-1 mb-2 fs-11" id="error-gender"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="hubby">Hubby</label>
                        <textarea rows="10" id="hubby" name="hubby" class="form-control"><?php echo trim($userdata['User']['hubby'] ?? 'No input hubby yet.'); ?></textarea>
                        <div class="error-message mt-1 mb-2 fs-11" id="error-hubby"></div>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</form>


<div class="modal fade" id="changeAccModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changeData">
                <div class="modal-body">

                    <div id="changeEmailContainer" class="d-none">
                        <div class="form-group">
                            <label class="small mb-1" for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="text">
                            <div class="error-message mt-1 mb-2 fs-11" id="error-email"></div>
                        </div>
                    </div>

                    <div id="resetPasswordContainer" class="d-none">
                        <div class="form-group">
                            <label class="small mb-1" for="old_password">Old Password</label>
                            <input class="form-control" id="old_password" name="old_password" type="password">
                            <div class="error-message mt-1 mb-2 fs-11" id="error-old_password"></div>
                        </div>

                        <div class="form-group">
                            <label class="small mb-1" for="new_password">New Password</label>
                            <input class="form-control" id="new_password" name="new_password" type="password">
                            <div class="error-message mt-1 mb-2 fs-11" id="error-new_password"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark" id="saveChangeData">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div style="height:500px;"></div>

<script>
    $(document).ready(function() {

        let email = '<?php echo $userdata['User']['email']; ?>';

        // Preview the uploaded image
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile").change(function() {
            readURL(this); // call preview img
        });

        $("#birthdate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '1900:+0'
        });

        $("#updateAccount").on('submit', function(e) {
            e.preventDefault();

            var form = new FormData(this);

            $('.error-message').html(''); // Clear previous msg

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: form,
                enctype: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Account Updated",
                            text: "You can click the button to view you're profile",
                            icon: "success",
                            showCancelButton: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, view it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/cakephp/users/profileView';
                            }
                        });
                    } else if (response.errors) {
                        $.each(response.errors, function(field, message) {
                            $('#error-' + field).html(message);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error: ", {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.changeAccModal', function() {
            const data = $(this).attr('data-type');



            if (data === 'email') {
                $('#resetModalLabel').text('Change Email');
                $('#changeEmailContainer').removeClass('d-none');
                $('#resetPasswordContainer').addClass('d-none');
                $('#email').val(email)
            } else if (data === 'password') {
                $('#resetModalLabel').text('Reset Password');
                $('#resetPasswordContainer').removeClass('d-none');
                $('#changeEmailContainer').addClass('d-none');
            } else {
                console.log('data not found');
            }

            $('#changeAccModal').modal('show');
        });

        $("#changeData").on('submit', function(e) {
            e.preventDefault();
            $('.error-message').html('');

            const dataType = $('#resetModalLabel').text();

            let url = '';
            let postData = {};

            if (dataType === 'Change Email') {
                const emailValue = $('#email').val();
                url = '/cakephp/users/changeEmail';
                postData = {
                    email: emailValue
                };
            } else if (dataType === 'Reset Password') {
                const oldPasswordValue = $('#old_password').val();
                const newPasswordValue = $('#new_password').val();
                url = '/cakephp/users/changePassword';
                postData = {
                    old_password: oldPasswordValue,
                    new_password: newPasswordValue
                };
            } else {
                console.log('No valid action found');
                return;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {

                        if (dataType === 'Change Email') {

                            $('#email').val(response.email);

                            if (email !== $('#email').val()) {
                                toast('success', 'Email changed successfully!');
                                setTimeout(() => {
                                    location.reload();
                                }, 3000);
                            }
                        }

                        if (dataType === 'Reset Password') {
                            $('#old_password').val('');
                            $('#new_password').val('');
                            toast('success', 'Password reset Successfully!')
                        }

                        $('#changeAccModal').modal('hide');


                    } else if (response.errors) {
                        $.each(response.errors, function(field, message) {
                            $('#error-' + field).html(message);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error: ", {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                }
            });
        });

    });
</script>