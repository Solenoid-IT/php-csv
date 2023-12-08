<?php



namespace Solenoid\CSV;



use \Solenoid\CSV\Config;
use \Solenoid\System\File;
use \Solenoid\System\Stream;



class Reader
{
    private string $file_path;
    private Config $config;

    private Stream $stream;



    # Returns [self]
    public function __construct (string $file_path, Config $config)
    {
        // (Getting the values)
        $this->file_path = $file_path;
        $this->config    = $config;
    }

    # Returns [Reader]
    public static function create (string $file_path, Config $config)
    {
        // Returning the value
        return new Reader( $file_path, $config );
    }



    # Returns [array<string>]
    public function fetch_schema ()
    {
        // (Iterating each line)
        File::select( $this->file_path )->walk
        (
            function ($line) use (&$schema)
            {
                // (Getting the value)
                $schema = str_getcsv( $line, $this->config->column_separator, $this->config->enclosure, $this->config->escape );



                // Returning the value
                return false;
            },
            $this->config->row_separator,
            null
        )
        ;



        // Returning the value
        return $schema;
    }

    # Returns [array<assoc>]
    public function fetch_records (?array $schema = null, ?callable $transform = null)
    {
        // (Setting the value)
        $schema_read = false;

        if ( $schema === null ) $schema_read = true;



        // (Iterating each line)
        File::select( $this->file_path )->walk
        (
            function ($line) use (&$schema_read, $schema, &$records)
            {
                if ( !$schema_read )
                {// Match OK
                    // (Setting the value)
                    $schema_read = true;



                    // Returning the value
                    return true;
                }



                // (Getting the value)
                $row = str_getcsv( $line, $this->config->column_separator, $this->config->enclosure, $this->config->escape );

                if ( $schema )
                {// Value found
                    // (Setting the value)
                    $record = [];

                    foreach ($schema as $i => $k)
                    {// Processing each entry
                        // (Getting the value)
                        $record[ $k ] = $row[ $i ];
                    }
                }
                else
                {// Value not found
                    // (Getting the value)
                    $record = $row;
                }



                // (Appending the value)
                $records[] = $record;
            },
            $this->config->row_separator,
            null
        )
        ;



        // Returning the value
        return $transform ? array_map( $transform, $records) : $records;
    }



    # Returns [self|false]
    public function open ()
    {
        // (Opening the stream)
        $this->stream = Stream::open( $this->file_path );

        if ( $this->stream === false )
        {// (Unable to open the stream)
            // (Setting the value)
            $message = "Unable to open the stream";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        // Returning the value
        return $this;
    }

    # Returns [self|false]
    public function close ()
    {
        if ( !$this->stream->close() )
        {// (Unable to close the stream)
            // (Setting the value)
            $message = "Unable to close the stream";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        // Returning the value
        return $this;
    }



    # Returns [assoc|false]
    public function fetch_record (?array $schema = null, ?callable $transform = null)
    {
        if ( $this->stream->is_ended() )
        {// (Stream is ended)
            // Returning the value
            return false;
        }



        // (Getting the value)
        $line = $this->stream->read_line( 0, $this->config->row_separator );

        if ( $line === false )
        {// (Unable to read the line)
            // Returning the value
            return false;
        }



        // (Getting the value)
        $row = str_getcsv( $line, $this->config->column_separator, $this->config->enclosure, $this->config->escape );

        if ( $schema )
        {// Value found
            // (Setting the value)
            $record = [];

            foreach ($schema as $i => $k)
            {// Processing each entry
                // (Getting the value)
                $record[ $k ] = $row[ $i ];
            }
        }
        else
        {// Value not found
            // (Getting the value)
            $record = $row;
        }



        // Returning the value
        return $transform ? $transform( $record ) : $record;
    }
}



?>