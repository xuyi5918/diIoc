<?php
/**
  * PHP 对象容器类
  *
  * @author 徐熠 <xuyi5918@live.cn>
  * @date 2017/5/8
  */ 
 class di {
 	private $bind = array();
 	private $objectList = array();
 	private $param = array();
 

 	/**
 	 * 设置要使用的对象
 	 */
 	public function set($bind, $object, $share = FALSE)
 	{	
 		if(is_object($object)) {

 			$this->objectList[$bind] = $object;
 		}else{
 			$this->bind[$bind]['class'] = $object;
 			$this->bind[$bind]['share'] = $share; //方式
 		}
 	}

 	/**
 	 * 
 	 */
 	private function interface_check()
 	{

 	}

 	/**
 	 * 获取容器中的对象
 	 */
 	public function make($bind, $param = array())
 	{

 		//从实例化的对象列表中获取
 		if(isset($this->objectList[$bind]) && ! empty($this->objectList[$bind])) {
 			return $this->objectList[$bind];
 		}

 		if (is_string($param) && ! isset($this->objectList[$param])) 
 		{
 			$this->make($param);
 		}

 		
 		if(! isset($this->bind[$bind]) && empty($this->bind[$bind]))
 		{
 			return NULL;
 		}

 		$class = $this->bind[$bind]['class']; //获取注册的类名

 		if(is_string($class))
 		{
 			if(! empty($param))
 			{
				$this->param = $param;
 			}

 			

 			$newClass = new \ReflectionClass($class); //反射
 			
 			/** 自动解决依赖关系 **/
 			$resultParam = $this->autoload($newClass);

 			if($resultParam === TRUE) {
 				$obj = new $class();
 			}else{
 				$obj = $newClass->newInstanceArgs($resultParam);
 			}



 			/**注册方式 是否共享**/

 			if($this->bind[$bind]['share']) {
 				$this->objectList[$bind] = $obj;
 			}
 			
 			return $obj;
 		}

 	}

 	/**
 	 * 获取参数
 	 */
 	private function param($key = 0)
 	{
 		if(! is_string($this->param))
 		{
			$param = $this->param[$key];
	 		unset($this->param[$key]);
	 		return $param;
 		}
 		

 		return $this->objectList[$this->param];
 	}

 	/**
 	 * 解决依赖关系
 	 */
 	private function autoload($ref)
 	{
 		$returnParam = array();


 		$constructor = $ref->getConstructor();
 		 if(is_null($constructor)){
 		 	return TRUE;
 		 } else {

 		 	$parameters = $constructor->getParameters();
			$total = 0;
 		 	foreach($parameters as $item) {

 		 		$param = $item->getClass();

 		 		if(is_null($param)) {

 		 			$returnParam[] = $this->defaultValue($item,$total);
 		 			$total ++;

 		 		}else{
					
					$class = $param->name;
 		 	
 		 			$make = $this->make($class);

 		 			if(is_null($make)) {
 		 				
 		 				$this->set($class, $class, TRUE);
 		 				$make = $this->make($class);
 		 			}

 		 			$returnParam[] = $make;
 		 		}
 		 	}
 		 	
 		 	return $returnParam;
 		 }

 	}

 	/**
 	 * 获取默认值
 	 */
 	private function defaultValue($defaultValue, $total) {

 		if($defaultValue->isDefaultValueAvailable()){

 			return isset($this->param[$total]) ? $this->param($total) : $defaultValue->getDefaultValue();
 		}

 		return $this->param();
 	}

 	/**
 	 * 删除掉存在与容器中的对象
 	 */
 	public function delete($bind)
 	{
 		if(isset($this->objectList[$bind]) || isset($this->bind[$bind])){
 			unset($this->objectList[$bind], $this->bind[$bind]);
 		}
 		return TRUE;
 	}

 	/**
 	 * 检测容器这种是否存在要使用的对象
 	 */
 	public function check($param)
 	{
 		if(! empty($param) && isset($this->objectList[$param])){
 			return TRUE;
 		}
 		return FALSE;
 	}

 }
?>