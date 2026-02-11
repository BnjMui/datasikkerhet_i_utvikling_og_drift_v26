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

 function uuid()
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
$testuuid = uuid();
            echo $testuuid;
?>
        </p>
    </body>
</html>

