<?php



use \Solenoid\CSV\CSV;




// (Getting the value)
$ts = time();



// (Setting the value)
$records =
[
    [
        'sender'   => 'john.doe@gmail.com',
        'receiver' => 'livia.johnson@hotmail.ca',

        'message'  => "Welcome \"Livia\" to our portal !\n\nThis week we talk about our company policies;",
        'datetime' => date( 'c', $ts )
    ],

    [
        'sender'   => 'livia.johnson@hotmail.ca',
        'receiver' => 'john.doe@gmail.com',

        'message'  => "Thanks John for the hiring.",
        'datetime' => date( 'c', $ts + 2 * 3600 )
    ]
]
;



// (Getting the value)
$file_content = ( new CSV( array_keys( $records[0] ), $records ) )->build();



// (Writing to the file)
file_put_contents( '/csv/path/file-hd.csv', $file_content );



?>