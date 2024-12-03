<?php



use \Solenoid\CSV\CSV;



// (Getting the value)
$file_content = file_get_contents( '/csv/path/file-hd.csv' );



// (Getting the value)
$eol = CSV::detect_eol( $file_content );

if ( $eol === false )
{// (Unable to detect the EOL)
    // Printing the value
    echo "Unable to detect the EOL\n";

    // Closing the process
    exit;
}



// (Getting the value)
$separator = CSV::detect_separator( $file_content, $eol );

if ( $separator === false )
{// (Unable to detect the column separator)
    // Printing the value
    echo "Unable to detect the column separator\n";

    // Closing the process
    exit;
}



// (Parsing the CSV)
$csv = CSV::parse( $file_content, CSV::TYPE_HD, $eol, $separator );



// Printing the value
print_r
(
    [
        'header'  => $csv->header,
        'records' => $csv->records
    ]
)
;



?>