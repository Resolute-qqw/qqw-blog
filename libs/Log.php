<?php
namespace libs;
class Log{
    private $file;
    function __construct($fileName){
        $this->file = fopen(ROOT.'logs/'.$fileName.'.log','a');
    }
    function log($content){
        $date = date('Y-m-d h:i:s');
        $cont = $date."\r\n";
        $cont .= str_repeat("=",110)."\r\n";
        $cont .= $content."\r\n\r\n";
        fwrite($this->file,$cont);
    }
}