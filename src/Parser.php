<?php



namespace Solenoid\CSV;



class Parser
{
    # Returns [array<assoc>]
    public static function parse (string $content, string $line_separator = "\n", string $column_separator = ';', string $enclosure = '"', string $escape = "\\")
    {
        // (Getting the value)
        $lines = explode( $line_separator, $content );



        // (Setting the values)
        $schema  = [];
        $records = [];



        // (Setting the value)
        $count = 0;

        foreach ( $lines as $line )
        {// Processing each entry
            // (Getting the value)
            #$values = explode( $column_separator, $line );
            $values = str_getcsv( $line, $column_separator, $enclosure, $escape );

            if ( count($values) === 1 && strlen( $values[0] ) === 0 ) continue;



            // (Incrementing the value)
            $count += 1;

            if ( $count === 1 )
            {// (Line contains a schema)
                // (Getting the value)
                $schema = $values;
            }
            else
            {// (Line contains a record)
                // (Setting the value)
                $record = [];

                foreach ( $values as $k => $v )
                {// Processing each entry
                    # debug
                    $record['count'] = $count;



                    // (Getting the value)
                    $record[ $schema[$k] ] = $v;
                }

                

                // (Appending the value)
                $records[] = $record;
            }
        }



        // Returning the value
        return $records;
    }
}



?>