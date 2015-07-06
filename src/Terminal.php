<?php namespace Rde;

class Terminal
{
    public static function stdout($text, $color = "\e[m", $new_line = true)
    {
        self::writeTo(STDOUT, $text, $color, $new_line);
    }

    public static function stderr($text, $color = "\e[m", $new_line = true)
    {
        self::writeTo(STDERR, $text, $color, $new_line);
    }

    protected static function writeTo($resource, $text, $color, $new_line)
    {
        fwrite($resource, "{$color}{$text}\e[m".($new_line?"\n":''));
    }
}
