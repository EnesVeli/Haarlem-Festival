<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Password Reset</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            <?php include '/app/public/assets/css/main.css'; ?>
        </style>
    </head>
    <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card shadow-sm border-0 mt-5 col-12 col-sm-10 col-md-7 col-lg-5">
                    <div class="card-body p-4">
                        <div class="alert alert-success">Your password has been successesfuly reset.</div>
                        <a class="btn btn-primary" href="/login">Login</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>