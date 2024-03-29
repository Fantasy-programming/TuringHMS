<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $code ?></title>
    <?php globalcss() ?>
</head>

<body>
    <div id="error">
        <div class="container text-center">
            <div class="pt-8">
                <h1 class="errors-titles"><?= $code ?></h1>
                <p><?= $message ?></p>
                <a href="/" class="text-blue btn">Back to home</a>
            </div>
        </div>
    </div>
</body>

</html>
