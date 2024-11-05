<!-- app/View/Users/login.ctp -->

<form id="loginForm" action="/cakephp/users/login" method="POST">
    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-chat-right-text" viewBox="0 0 16 16">
        <path d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
    </svg>

    <h1 class="h3 mb-3 fw-normal mt-3">Please sign in</h1>

    <div class="mb-2 fs-11" id="error-invalid"></div>

    <div class="error-message mb-2 fs-11" id="error-email"></div>
    <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email Address">
        <label for="floatingInput">Email Address</label>
    </div>

    <div class="error-message mt-1 mb-2 fs-11" id="error-password"></div>
    <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
        <label for="floatingPassword">Password</label>
    </div>

    <?php echo $this->Form->button(__('Sign in'), ['class' => 'w-100 btn btn-lg btn-primary mt-3', 'id' => 'signInButton']); ?>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-2">
            <small>
                Don't have an account?
                <a href="<?php echo $this->Html->url(['controller' => 'Users', 'action' => 'register']); ?>" class="mx-1">Register</a>
            </small>
        </div>
    </div>


    <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y') ?></p>

</form>


<script>
    $(document).ready(function() {
        $("#loginForm").on('submit', function(e) {
            e.preventDefault();

            $('.error-message').html(''); // Clear previous error messages

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(), // Serialize the form data
                dataType: 'json', // Expect a JSON response
                success: function(response) {

                    //  console.log(response)
                    if (response.status === "success") {
                        // Redirect on successful login
                        window.location.href = "/cakephp/home";

                        // } else if (response.status === "error") {

                        //     $('#error-invalid').text(response.message)

                        //     setTimeout(function() {
                        //         $('#error-invalid').fadeOut();
                        //     }, 3000);

                    } else {
                        if (response.errors) {
                            $.each(response.errors, function(field, message) {
                                $('#error-' + field).html(message);

                            });
                        }

                        if (response.message) {
                            $('#error-invalid').text(response.message).fadeIn();

                            setTimeout(function() {
                                $('#error-invalid').fadeOut();
                            }, 3000);
                        }
                    }
                },
                error: function(xhr, status, error) {

                    console.log(error)
                    console.log("AJAX Error: ", {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                }
            });
            return false; // Prevent default form submission
        });
    });
</script>