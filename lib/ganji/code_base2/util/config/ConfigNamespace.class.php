<?php
/*
 * Created on 2012-11-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class ConfigNamespace 
 {
 	private static $CONFIGS = array();
 	//在读文件时，如果文件不存在会重试，这里配置重试的最大次数
 	private static $MAX_READ_CONFIG_RETRY = 15;
 	private static $DOC_PATTERN = "/^.*@config\(\"(.*)\"\).*$/s";
 	
 	//在调用getConfig或者initStaticField之前必须先init
 	public function init($config_file_dir,$config_name_list) {
 		if(!is_dir($config_file_dir)) {
 			throw new Exception("param config_file_dir must be a dir,value=".$config_file_dir);
 		}
 		
 		if(!is_array($config_name_list)) {
 			throw new Exception("param config_file_list must be an array,value=".$config_name_list);
 		}
 		
 		self::initConfigs($config_file_dir,$config_name_list);
 	}
 	
 	 private function initConfigs($config_file_dir,$config_name_list) {
	 	$temp_configs = array();
	 	
	 	//处理dir是否以/ 或者 \ 结尾
	 	$last_character = substr($config_file_dir,strlen($config_file_dir) - 1);
	 	if(strcmp($last_character,"/") != 0) {
	 		if(strcmp($last_character,"\\") != 0) {
	 			$config_file_dir = $config_file_dir."/";
	 		}
	 	}
	 	
	 	$exist_files = self::findConfigFiles($config_file_dir,$config_name_list);
	 	foreach($exist_files as $config_name) {
	 		$file_content = self::readConifg($config_file_dir.$config_name);
	 		if($file_content === false) {
	 			continue;
	 		}
	 		
	 		$json_file_content = json_decode($file_content,true);
	 		
	 		if(isset($json_file_content["Settings"])) {
	 			$a_setting = $json_file_content["Settings"];
	 			foreach($a_setting as $key => $value) {
	 				if(is_array($value)) {
	 					if(isset($value["Key"])) {
	 						$sub_key = $value["Key"];
	 						$sub_type = "String";
	 						if(isset($value["Type"])) {
	 							$sub_type = $value["Type"];
	 						}
	 						
	 						if(isset($value["Value"])) {
		 						$sub_value = $value["Value"];
		 						
		 						if(strcmp($sub_type,"String") == 0) {
							 		$temp_configs[$sub_key] = strval($sub_value);
								} else if(strcmp($sub_type,"Integer") == 0) {
									$temp_configs[$sub_key] = intval($sub_value);
								} else if(strcmp($sub_type,"Boolean") == 0) {
									$temp_configs[$sub_key] = (bool)$sub_value;
								} else if(strcmp($sub_type,"Long") == 0) {
									$temp_configs[$sub_key] = (double)$sub_value;
								} else {
									$temp_configs[$sub_key] = $sub_value;
								}
	 						}
	 					}
	 				} else {
	 					$temp_configs[$key] = $value;
	 				}
	 			}
	 		}
	 	}
	 	
	 	self::$CONFIGS = $temp_configs;
	 }
	 
	 private function findConfigFiles($dir,$config_name_list) {
	 	$dir_handle = opendir($dir);
	 	$file_list = array();
	 	while($file = readdir($dir_handle)) {
	 		if($file == "." || $file == "..") {
	 			continue;
	 		}
	 		
	 		$full_name = $dir."/".$file;
	 		if(is_dir($full_name)) {
	 			continue;
	 		}
	 		
	 		if(!preg_match("/^.*\\.json$/i",$file)) {
	 			continue;
	 		}
	 		
	 		foreach($config_name_list as $config_name) {
	 			$name_pattern = "/^".$config_name.".*\\.json$/i";
	 			if(preg_match($name_pattern,$file)) {
		 			array_push($file_list,$file);
		 		}
	 		}
	 	}
	 	
	 	array_unique($file_list);
	 	//先sort，如果配置ms,ms.nh在数组中，sort后会先处理ms信息，后处理ms.nh信息。如果ms.nh
	 	//和ms有相同的key，ms.nh会覆盖ms的配置
	 	sort($file_list);
	 	
	 	closedir($dir_handle);
	 	
	 	return $file_list;
	 }
	 
	 private function readConifg($file_name) {
	 	$handle = false;
	 	$retry = 1;
	 	
	 	$contents = false;
	 	while($retry++ < self::$MAX_READ_CONFIG_RETRY) {
	 		$contents = file_get_contents($file_name);
	 		
	 		if($contents === false) {
	 			sleep(1);
	 		} else {
	 			return $contents;
	 		}
	 	}
	 	
	 	return false;
	 }
	 
	 /**
	  * 获取配置信息，调用此函数之前必须先调用ConfigNamespace::init($config_file_dir,$config_name_list)
	  * 另外可以调用ConfigNamespace::initStaticField($a_class)自动为类的static feild赋值
	  */
	 public function getConfig($key) {
	 	if(isset(self::$CONFIGS[$key])) {
	 		$value = self::$CONFIGS[$key];
	 		
	 		return $value;
	 	}
	 	
	 	throw new Exception("can not find ".$key);
	 }
	 
	 /**
	  * 调用此函数自动为php类的static feild赋值，
	  * 调用此函数之前必须先调用ConfigNamespace::init($config_file_dir,$config_name_list)
	  */
	 public function initStaticField($a_class) {
 		$reflect_class = new ReflectionClass($a_class);
	 	
 		$props = $reflect_class->getProperties();
 		foreach($props as $afield) {
 			if($afield->isStatic()) {
	 			$name = $afield->name;
	 			$class_instance = new $a_class;
	 			$config_name = $afield->getValue($class_instance);
	 			
	 			if(!is_string($config_name)) {
	 				continue;
	 			}
	 			
	 			$len = strlen($config_name);
	 			$_config_name = NULL;
			 	if($len > 2) {
			 		$start = substr($config_name,0,1);
			 		$end = substr($config_name,$len - 1);
			 		
			 		if(strcmp($start,"<") == 0 && strcmp($end,">") == 0) {
			 			$_config_name = substr($config_name,1,$end - 1);
			 		}
			 	}
			 	
			 	if($_config_name == NULL) {
			 		continue;
			 	}
	 			
	 			$value = ConfigNamespace::getConfig($_config_name);
	 			$afield->setValue($class_instance,$value);
 			}
 		}
	 	
	 }
	 
	/**
	  * 调用此函数自动特定注释的field设值，
	  * 调用此函数之前必须先调用ConfigNamespace::init($config_file_dir,$config_name_list)
	  */
	 public function initNotedFields($a_class) {
	 	$reflect_class = new ReflectionClass($a_class);
	 	
 		$props = $reflect_class->getProperties();
 		$class_instance = $reflect_class->newInstance();
 		foreach($props as $afield) {
 			$field_doc = $afield->getDocComment();
 			$matches =  array();
 			if($field_doc && preg_match(self::$DOC_PATTERN,$field_doc,$matches)) {
 				if(isset($matches[1])) {
 					$config_key = $matches[1];
 					$config_value = ConfigNamespace::getConfig($config_key);
 					$afield->setValue($class_instance,$config_value);
 				}
 			}
 		}
	 }
	 
 }
 
?>
