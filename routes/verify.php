<?php

use App\Helper;

if ($router->method == 'post') {
    if (!isset($session->tries)) {
        $session->tries = 1;
    } elseif (!isset($session->time)) {
        $session->time = time() - 1;
    } elseif (time() > $session->time) {
        $session->tries = 1;
        $session->time = time() + 60;
    }

    if ($session->tries > 3) {
        $session->error = 'Maximum attempts reached. Please try again after ' . ($session->time - time()) . ' s.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        Helper::abort();
    }

    $stmt = $db->prepare('SELECT * FROM users WHERE id = ? AND code = ? AND verified_at IS NULL');
    $stmt->execute([
        $session->user,
        $session->code,
    ]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {
        Helper::log('Date: ' . date('Y-m-d H:i:s') . ' | IP: ' . $_SERVER['REMOTE_ADDR'] . ' | User: ' . $session->user . ' | Code: ' . $session->code . ' | Result: Success');

        try {
            $db->prepare('UPDATE users SET verified_at = ?, code = NULL WHERE id = ?')->execute([
                date('Y-m-d H:i:s'),
                $session->user,
            ]);

            unset($session->error);
            unset($session->tries);
            unset($session->time);
            unset($session->code);
            unset($session->phone);
            $session->success = 'Your account is verified. Welcome to SMSBump!';
            header("Location: $config->url/");
            Helper::abort();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    } else {
        Helper::log('Date: ' . date('Y-m-d H:i:s') . ' | IP: ' . $_SERVER['REMOTE_ADDR'] . ' | User: ' . $session->user . ' | Code: ' . $session->code . ' | Result: Error');

        $session->tries++;
        $session->time = time() + 60;

        if ($session->tries > 3) {
            $session->error = 'Maximum attempts reached. Please try again after ' . ($session->time - time()) . ' s.';
        }

        $session->errors['code'] = 'Invalid SMS code';

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        Helper::abort(422);
    }
} else {
    if (!$session->user || !$session->phone) {
        header("Location: $config->url/");
        Helper::abort();
    }

    $invalidCode = null;

    if (isset($session->errors['code'])) {
        $invalidCode = 'is-invalid';
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
                <?php } ?>

                <h1 class="text-center">Verification Form</h1>
                <p class="text-center mt-3">Please verify your account</p>
                <hr class="mt-3 mb-5">

                <form method="POST" action="verify" id="verify-form">
                    <div class="form-group">
                        <label for="code" class="font-weight-bold <?php echo $invalidCode; ?>">SMS Code</label>
                        <span class="invalid-feedback mb-2" role="alert"><?php echo $session->errors['code'] ?? null ?></span>
                        <input id="code" type="tel" class="form-control <?php echo $invalidCode; ?>" name="code" value="<?php echo $session->code; ?>" inputmode="tel" required>
                    </div>

                    <?php if ($session->error) { ?>
                    <div class="alert alert-danger" role="alert"><?php echo $session->error; ?></div>
                    <?php } ?>

                    <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary rounded">Verify</button>
                    </div>
                </form>

                <?php if ($session->tries <= 3) { ?>
                <form method="POST" action="resend" id="resend-form">
                    <input id="phone" type="hidden" name="phone" value="<?php echo $session->phone; ?>">
                    <p class="text-center mt-3">Didn't receive verification code? <button type="submit" class="btn btn-link p-0 align-top">Send new SMS code</button></p>
                </form>
                <?php } ?>
            </main>
        </body>
    </html>

    <?php

    unset($session->errors);
    unset($session->error);
    unset($session->success);
}

?>
