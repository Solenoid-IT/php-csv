<?php



namespace Solenoid\CSV;



use \Solenoid\CSV\Config;
use \Solenoid\System\File;
use \Solenoid\System\Stream;



class Writer
{
    private string $file_path;
    private Config $config;



    # Returns [self]
    public function __construct (string $file_path, Config $config)
    {
        // (Getting the values)
        $this->file_path = $file_path;
        $this->config    = $config;
    }

    # Returns [Writer]
    public static function create (string $file_path, Config $config)
    {
        // Returning the value
        return new Writer( $file_path, $config );
    }



    # Returns [bool] | Throws [Exception]
    public function set_schema (array $schema)
    {
        // (Getting the value)
        $file = File::select( $this->file_path );



        // (Opening the stream)
        $stream = Stream::open( $file, 'w' );

        if ( $stream === false )
        {// (Unable to open the stream)
            // (Setting the value)
            $message = "Unable to open the stream";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        if ( fputcsv( $stream->resource, $schema, $this->config->column_separator, $this->config->enclosure, $this->config->escape, $this->config->row_separator ) === false )
        {// (Unable to write the content to the file)
            // (Setting the value)
            $message = "Unable to write the content to the file";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        if ( !$stream->close() )
        {// (Unable to close the stream)
            // (Setting the value)
            $message = "Unable to close the stream";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        // Returning the value
        return true;
    }

    # Returns [bool] | Throws [Exception]
    public function push_record (array $record)
    {
        // (Getting the value)
        $file = File::select( $this->file_path );



        // (Opening the stream)
        $stream = Stream::open( $file, 'a' );

        if ( $stream === false )
        {// (Unable to open the stream)
            // (Setting the value)
            $message = "Unable to open the stream";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        if ( fputcsv( $stream->resource, array_values( $record ), $this->config->column_separator, $this->config->enclosure, $this->config->escape, $this->config->row_separator ) === false )
        {// (Unable to write the content to the file)
            // (Setting the value)
            $message = "Unable to write the content to the file";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        if ( !$stream->close() )
        {// (Unable to close the stream)
            // (Setting the value)
            $message = "Unable to close the stream";

            // Throwing an exception
            throw new \Exception($message);

            // Returning the value
            return false;
        }



        // Returning the value
        return true;
    }



    # Returns [bool] | Throws [Exception]
    public function push_records (array $records)
    {
        foreach ($records as $record)
        {// Processing each entry
            if ( !$this->push_record( $record ) )
            {// (Unable to push the record)
                // (Setting the value)
                $message = "Unable to push the record";

                // Throwing an exception
                throw new \Exception($message);

                // Returning the value
                return false;
            }
        }



        // Returning the value
        return true;
    }
}



?>