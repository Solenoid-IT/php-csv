<?php



namespace Solenoid\CSV;



class CSV
{
    const TYPE_DATA = 'data';
    const TYPE_HD   = 'header+data';



    public array $header;
    public array $records;



    # Returns [string]
    private static function encode (string $value, string $column_separator, string $enclosure, string $escape)
    {
        // (Getting the value)
        $encoded_value = $value;



        // (Getting the value)
        $wrap_value = preg_match( '/\s/', $encoded_value ) === 1 || strpos( $value, $column_separator ) !== false || strpos( $value, $enclosure ) !== false;



        // (Getting the value)
        $encoded_value = str_replace( [ $escape, "\n", "\r", "\t", $enclosure ], [ "{$escape}$escape", "{$escape}n", "{$escape}r", "{$escape}t", "$escape{$enclosure}" ], $encoded_value );



        if ( $wrap_value )
        {// Value is true
            // (Getting the value)
            $encoded_value = $enclosure . $encoded_value . $enclosure;
        }



        // Returning the value
        return $encoded_value;
    }

    # Returns [string]
    private static function decode (string $value, string $column_separator, string $enclosure, string $escape)
    {
        // (Getting the value)
        $decoded_value = $value;



        // (Getting the value)
        $decoded_value = str_replace( [ "{$escape}$escape", "{$escape}n", "{$escape}r", "{$escape}t", "$escape{$enclosure}" ], [ $escape, "\n", "\r", "\t", $enclosure ], $decoded_value );



        // Returning the value
        return $decoded_value;
    }



    # Returns [self]
    public function __construct (array $header = [], array $records = [])
    {
        // (Getting the values)
        $this->header  = $header;
        $this->records = $records;
    }



    # Returns [string]
    public function build (string $line_separator = "\n", string $column_separator = ';', string $enclosure = '"', string $escape = "\\")
    {
        // (Setting the value)
        $content = [];

        if ( $this->header )
        {// Value is not empty
            // (Appending the value)
            $content[] = implode( $column_separator, array_map( function ($column_name) use ($column_separator, $enclosure, $escape) { return self::encode( $column_name, $column_separator, $enclosure, $escape ); }, $this->header ) );

            foreach ( $this->records as $record )
            {// Processing each entry
                // (Appending the value)
                $content[] = implode( $column_separator, array_map( function ($column_value) use ($column_separator, $enclosure, $escape) { return self::encode( $column_value, $column_separator, $enclosure, $escape ); }, array_values( $record ) ) );
            }
        }
        else
        {// Value is empty
            foreach ( $this->records as $record )
            {// Processing each entry
                // (Appending the value)
                $content[] = implode( $column_separator, array_map( function ($column_value) use ($column_separator, $enclosure, $escape) { return self::encode( $column_value, $column_separator, $enclosure, $escape ); }, array_values( $record ) ) );
            }
        }



        // Returning the value
        return implode( $line_separator, $content);
    }



    # Returns [self]
    public static function parse (string $content, string $type = self::TYPE_DATA, string $line_separator = "\n", string $column_separator = ';', string $enclosure = '"', string $escape = "\\")
    {
        // (Getting the value)
        $lines = explode( $line_separator, $content );



        // (Setting the values)
        $header  = [];
        $records = [];



        // (Setting the value)
        $count = 0;

        foreach ( $lines as $line )
        {// Processing each entry
            if ( strlen( $line ) === 0 ) continue;



            // (Getting the value)
            $values = array_map( function ($value) use ($column_separator, $enclosure, $escape) { return self::decode( $value, $column_separator, $enclosure, $escape ); }, str_getcsv( $line, $column_separator, $enclosure, $escape ) );

            #if ( count( $values ) === 1 && strlen( $values[0] ) === 0 ) continue;



            // (Incrementing the value)
            $count += 1;

            switch ( $type )
            {
                case self::TYPE_DATA:
                    // (Appending the value)
                    $records[] = $values;
                break;

                case self::TYPE_HD:
                    if ( $count === 1 )
                    {// (Line contains a schema)
                        // (Getting the value)
                        $header = $values;
                    }
                    else
                    {// (Line contains a record)
                        // (Setting the value)
                        $record = [];

                        foreach ( $values as $k => $v )
                        {// Processing each entry
                            // (Getting the value)
                            $record[ $header[$k] ] = $v;
                        }

                        

                        // (Appending the value)
                        $records[] = $record;
                    }
                break;
            }
        }



        // Returning the value
        return new CSV( $header, $records );
    }



    # Returns [string|false]
    public static function detect_eol (string $content, array $eols = [ "\n", "\r\n" ])
    {
        // (Setting the values)
        $max          = 0;
        $detected_eol = false;

        foreach ( $eols as $eol )
        {// Processing each entry
            // (Getting the value)
            $n = substr_count( $content, $eol );

            if ( $n >= $max )
            {// Match OK
                // (Getting the values)
                $max          = $n;
                $detected_eol = $eol;
            }
        }



        // Returning the value
        return $detected_eol;
    }

    # Returns [string|false]
    public static function detect_separator (string $content, string $eol = "\n", array $separators = [ ';', ',', '|', "\t" ])
    {
        // (Setting the value)
        $num_samples = 2;



        // (Setting the value)
        $results = [];

        foreach ( explode( $eol, $content ) as $line )
        {// Processing each entry
            if ( strlen( $line ) === 0 ) continue;



            // (Setting the value)
            $result = [];

            foreach ( $separators as $separator )
            {// Processing each entry
                // (Getting the value)
                $result[ $separator ] = substr_count( $line, $separator );
            }



            // (Appending the value)
            $results[] = $result;



            if ( count( $results ) === $num_samples )
            {// Match OK
                // Breaking the iteration
                break;
            }
        }



        if ( count( $results ) < $num_samples )
        {// (There are not enough samples)
            // Returning the value
            return false;
        }



        foreach ( $separators as $separator )
        {// Processing each entry
            for ( $i = 0; $i < count( $results ) - 1; $i++ )
            {// Iterating each index
                if ( $results[ $i ][$separator] === $results[ $i + 1 ][$separator] )
                {// Match OK
                    // Returning the value
                    return $separator;
                }
            }
        }



        // Returning the value
        return false;
    }
}



?>