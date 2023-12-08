<?php



namespace Solenoid\CSV;



class Config
{
    public string $row_separator;
    public string $column_separator;

    public string $enclosure;
    public string $escape;



    # Returns [self]
    public function __construct (string $row_separator = PHP_EOL, string $column_separator = ',', string $enclosure = '"', string $escape = "\\")
    {
        // (Getting the values)
        $this->row_separator    = $row_separator;
        $this->column_separator = $column_separator;

        $this->enclosure        = $enclosure;
        $this->escape           = $escape;
    }

    # Returns [Config]
    public static function create (string $row_separator = PHP_EOL, string $column_separator = ',', string $enclosure = '"', string $escape = "\\")
    {
        // Returning the value
        return new Config( $row_separator, $column_separator, $enclosure, $escape );
    }
}



?>