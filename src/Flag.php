<?php namespace Rde;

class Flag
{
    protected $cmd_name;
    protected $output;
    protected $map = array();
    protected $args = array();

    public function __construct($output = null)
    {
        if (null !== $output && ! is_callable(array($output, 'writeln'))) {
            throw new IvalidArgumentException("建構參數 1 必須是有實作 writeln(string) 方法的物件");
        }
        $this->output = $output;
    }

    public function &string($name, $default = null, $describe = null)
    {
        return $this->set('%s', $name, (string)$default, $describe);
    }

    public function stringVar(&$var, $name, $default = null, $describe = null)
    {
        $var = $default;
        $this->string($name, $default, $describe);
        $this->map[$name][2] = &$var;
    }

    public function &int($name, $default = null, $describe = null)
    {
        return $this->set('%d', $name, (int)$default, $describe);
    }

    public function intVar(&$var, $name, $default = null, $describe = null)
    {
        $var = $default;
        $this->int($name, $default, $describe);
        $this->map[$name][2] = &$var;
    }

    public function &bool($name, $default = null, $describe = null)
    {
        return $this->set('bool', $name, (bool)$default, $describe);
    }

    public function boolVar(&$var, $name, $default = null, $describe = null)
    {
        $var = $default;
        $this->bool($name, $default, $describe);
        $this->map[$name][2] = &$var;
    }

    protected function &set($format, $name, $default, $describe)
    {
        if (isset($this->map[$name])) {
            throw new \InvalidArgumentException("重複宣告 {$name}");
        }

        $this->map[$name] = array($format, $describe, $default);

        return $this->map[$name][2];
    }

    public function parse(array $argv)
    {
        $name = null;
        $parser = null;
        $pointer = null;
        $this->cmd_name = $argv[0];

        for ($i = 1, $len = count($argv); $i < $len; $i++) {
            $opt = $argv[$i];
            if (isset($parser)) {
                $pointer = call_user_func($parser, $opt, $this->map[$name][0]);
                unset($pointer, $parser, $name);
                continue;
            }

            if (0 === strpos($opt, '--')) {
                $name = substr($opt, 2);
                
                if (false !== strpos($name, '=')) {
                    list($name, $var) = preg_split('/=/', $name, 2);

                    if ( ! isset($this->map[$name]) || 'help' == $name) {
                        $this->usage();
                        return;
                    }
                    $this->map[$name][2] = self::parseBySscanf($var, $this->map[$name][0]);
                    unset($name, $var);
                    continue;
                }

                if ( ! isset($this->map[$name]) || 'help' == $name) {
                    $this->usage();
                    return;
                }
                
                switch ($this->map[$name][0]) {
                    case '%s':
                    case '%d':
                        $parser = 'self::parseBySscanf';
                        $pointer = &$this->map[$name][2];
                        break;

                    case 'bool':
                        $this->map[$name][2] = true;
                        break;

                    default:
                        $this->usage();
                }
                continue;
            }

            $this->args[] = $opt;
        }

        return $this->args;
    }

    public function usage()
    {
        foreach ($this->map as $name => $sets) {
            $this->writeln("{$name}\t\t{$sets[1]}\tdefault: {$sets[2]}");
        }
    }

    protected function writeln($line)
    {
        if ($this->output) {
            $this->output->writeln($line);
        } else {
            echo $line, PHP_EOL;
        }
    }

    public static function parseBySscanf($string, $format)
    {
        switch ($format) {
            case '%s':
                return $string;
            case '%d':
                return (int)$string;
        }
    }
}

