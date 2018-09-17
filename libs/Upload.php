<?php
namespace libs;
class Upload{
    private function __construct(){}
    private function __clone(){}
    private static $upload = null;
    public static function make(){
        if(self::$upload===null){
            self::$upload = new self;
        }
        return self::$upload;
    }

        private $root = ROOT.'public/uploads';
        private $ext = ['image/jpeg','image/jpg','image/ejpeg','image/png','image/gif','image/bmp'];
        private $maxSize = 1024*1024*2;
        private $file;
        private $subdir;

        public function upload($name, $subdir){
            $this->file = $_FILES[$name];
            $this->subdir = $subdir;
            if(!$this->checkType()){
                die("图片类型不对哦");
            }
            if(!$this->checkSize()){
                die("图片大小不对哦");
            }

            $name = $this->mkname();
            $dir = $this->mkdir();
            

            $pathEnd = $this->root."/".$dir.$name;
            move_uploaded_file($this->file['tmp_name'],$pathEnd);
            return $dir.$name;
        }

        private function mkname(){
            $name = md5(time().rand(1,9999));
            $exts = strrchr($this->file['name'],".");
            return $name.$exts;
        }
        private function mkdir(){
            $dir = $this->subdir."/".date("Ymd");
                if(!is_dir($this->root."/".$dir)){
                    mkdir($this->root."/".$dir);
                }
            return $dir."/";
        }

        private function checkType(){
            return in_array($this->file['type'],$this->ext);
        }
        private function checkSize(){
            return $this->file['size'] < $this->maxSize;
        }

        
        
}