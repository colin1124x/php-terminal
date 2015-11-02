<?php

class Outputx {
    public $buffer = array();
    public function writeln($line)
    {
        $this->buffer[] = $line;
    }
}

class FlagTest extends PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $flag = new Rde\Flag();
        $str = &$flag->string('str', 'abc', 'test string');
        $int = &$flag->int('int', 999, 'test int');
        $bool = &$flag->bool('bool', false, 'test int');

        $args = $flag->parse(array(
            './cmd',
            'x',
            '--int', '123',
            '--str', 'real word',
            '--bool',
            'y',
        ));

        $this->assertEquals('real word', $str);
        $this->assertEquals(123, $int);
        $this->assertEquals(array('x', 'y'), $args);
    }
    
    public function testHelp()
    {
        $flag = new Rde\Flag($out = new Outputx);
        $flag->string('str', 'string default', 'string describe');
        $flag->int('int', 0, 'int describe');
        $flag->bool('bool', false, 'bool describe');

        $flag->parse(array(
            './cmd',
            '--help',
        ));
        
        $this->assertEquals(
            array(
                "str\t\tstring describe\tdefault: string default",
                "int\t\tint describe\tdefault: 0",
                "bool\t\tbool describe\tdefault: "
            ),
            $out->buffer
        );
    }
}

