<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        <h1>Hei på deg</h1>
        <p>
            parameteret er 2
        </p>
        <p>
<?php
include "repository.php";

            $repository = new Repository();

            echo $repository->getUserById(1);
?>
        </p>
    </body>
</html>

