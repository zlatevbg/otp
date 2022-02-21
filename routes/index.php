<?php

if ($router->method == 'get') {
    $invalidEmail = null;
    $invalidPhone = null;
    $invalidPassword = null;

    if (isset($session->errors['email'])) {
        $invalidEmail = 'is-invalid';
    }

    if (isset($session->errors['phone'])) {
        $invalidPhone = 'is-invalid';
    }

    if (isset($session->errors['password'])) {
        $invalidPassword = 'is-invalid';
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>OTP verification using an SMS message</title>
            <link rel="icon" type="image/x-icon" href="favicon.ico">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <main class="container my-5">
                <?php if ($session->success) { ?>
                <div class="alert alert-success" role="alert"><?php echo $session->success; ?></div>
                <?php } else { ?>

                <h1 class="text-center">Regitsration Form</h1>
                <p class="text-center mt-3">Please fill in all fields below</p>
                <hr class="mt-3 mb-5">

                <form method="POST" action="register" id="register-form">
                    <div class="form-group">
                        <label for="email" class="font-weight-bold <?php echo $invalidEmail; ?>">Email</label>
                        <span class="invalid-feedback mb-2" role="alert"><?php echo $session->errors['email'] ?? null ?></span>
                        <input id="email" type="email" class="form-control <?php echo $invalidEmail; ?>" name="email" value="<?php echo $session->email; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="font-weight-bold <?php echo $invalidPhone; ?>">Phone</label>
                        <span class="invalid-feedback mb-2" role="alert"><?php echo $session->errors['phone'] ?? null ?></span>
                        <input id="phone" type="tel" class="form-control <?php echo $invalidPhone; ?>" name="phone" value="<?php echo $session->phone; ?>" inputmode="tel" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="font-weight-bold <?php echo $invalidPassword; ?>">Password</label>
                        <span class="invalid-feedback mb-2" role="alert"><?php echo $session->errors['password'] ?? null ?></span>
                        <input id="password" type="password" class="form-control <?php echo $invalidPassword; ?>" name="password" value="<?php echo $session->password; ?>" required>
                    </div>

                    <?php if ($session->error) { ?>
                    <div class="alert alert-warning" role="alert"><?php echo $session->error; ?></div>
                    <?php } ?>

                    <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary rounded">Register</button>
                    </div>
                </form>

                <?php } ?>
            </main>
        </body>
    </html>

    <?php

    unset($session->error);
    unset($session->errors);
    unset($session->success);
}

?>
