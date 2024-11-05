<!-- app/View/Users/register.ctp -->

<form id="registrationForm" action="/cakephp/users/register" method="POST">
    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-chat-right-text" viewBox="0 0 16 16">
        <path d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
    </svg>

    <h1 class="h3 mb-3 fw-normal mt-3">Please sign up</h1>

    <div class="error-message mb-2 fs-11" id="error-name"></div>
    <div class="form-floating">

        <input type="text" class="form-control" id="floatingInputName" name="name" placeholder="First Name and Last Name">
        <label for="floatingInputName">Name</label>
    </div>

    <div class="error-message mt-1 mb-2 fs-11" id="error-email"></div>
    <div class="form-floating">
        <input type="email" class="form-control" id="floatingInputEmail" name="email" placeholder="Enter your email">
        <label for="floatingInputEmail">Email</label>
    </div>

    <div class="error-message mt-1 mb-2 fs-11" id="error-password"></div>
    <div class="form-floating">
        <input type="password" class="form-control" id="floatingInputPwd" name="password" placeholder="Enter your Password">
        <label for="floatingInputPwd">Password</label>
    </div>

    <div class="error-message mt-1 mb-2 fs-11" id="error-password_confirm"></div>
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingInputPwdConfirm" name="password_confirm" placeholder="Re-type your password">
        <label for="floatingInputPwdConfirm">Confirm Password</label>
    </div>

    <?php echo $this->Form->button(__('Sign up'), ['type' => 'submit', 'class' => 'w-100 btn btn-lg btn-primary', 'id' => 'signUpButton']); ?>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-2">
            <small>
                Already have an account?
                <a href="<?php echo $this->Html->url(['controller' => 'Users', 'action' => 'login']); ?>" class="mx-1">Login</a>
            </small>
        </div>
    </div>


    <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y') ?></p>

</form>



<script>
    $(document).ready(function() {
        $("#registrationForm").on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $('.error-message').html(''); // clear

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {

                    if (response.status === 'success') {
                        setTimeout(function() {
                            window.location.href = '/cakephp/home/thankyou';
                        }, 1000);
                    } else {
                        if (response.errors) {
                            $.each(response.errors, function(field, messages) {
                                var errorElement = $('#error-' + field);
                                errorElement.html(messages.join(', '));
                            });
                        }
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            });
        });
    });
</script>