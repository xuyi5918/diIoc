<?php
include '../diIoc.php';

interface intvals{
	public function bbb();
}

class object {

	public function bb()
	{
		return 'v 1.1';
	}
}

class base{
	public $base = NULL;
	public function __construct(object $a,$c = "hello ",$b='world') {
		$this->base = $a;
		echo($c.$b);
		
	}
	public function baseUrl() {
		echo 'URL'. $this->base->bb();
	}
}


 class test{
 	public $base;

 	public function __construct($a = 10,$b = 11,base $base)
 	{
 		$this->base = $base;
 		$this->base->baseUrl();
 		var_dump($a,$b);
 	}
 	public function a(){
 		return 'aaaaaa';
 	}
 }

 class APP{
 		public $app;
 		public function APP(test $test){
 			$this->app = $test;
 			$this->app->a();
 		}
 }


 
 
 // $di->set('APP','APP');

 //  $di->make('APP',array(1,'print'));
 //  // $di->delete('test');

 // echo  $di->make('test',array(100,'vprint'))->a();


 class main{

 	public function __construct($void){
 		$void->main();
 	}
 }


 class void{

 	public function main(){
 		echo 'void';
 	}
 }

 class url{

 	public function main(){
 		echo 'url';
 	}
 }


 $di = new di();
  $di->set('main','main',FALSE);
  $di->set('void','void',TRUE);
	$di->set('url','url',TRUE);
	
 $main = $di->make('main','void');

 $main = $di->make('main','url');

 

?>