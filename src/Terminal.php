<?php namespace Rde;

class Terminal
{
    const COLOR_NONE = "\e[m";

    public static function stdin($len = 1024)
    {
        return fread(STDIN, (int)$len);
    }

    public static function stdout($text, $color = self::COLOR_NONE, $new_line = true)
    {
        return self::writeTo(STDOUT, $text, $color, $new_line);
    }

    public static function stderr($text, $color = self::COLOR_NONE, $new_line = true)
    {
        return self::writeTo(STDERR, $text, $color, $new_line);
    }

    protected static function writeTo($resource, $text, $color, $new_line)
    {
        return fwrite($resource, "{$color}{$text}".self::COLOR_NONE.($new_line?"\n":''));
    }
}
