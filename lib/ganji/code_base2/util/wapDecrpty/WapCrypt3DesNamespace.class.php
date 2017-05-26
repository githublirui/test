<?php
/**
*  3des加解密
*  基于QQ合作密钥
*  区别Crypt3DesStaticNamespace.class.php原因是加密源文件为hex（0x\d）
* @author    wangjian
* @touch     2012-9-17
* @category  wap
* @package   WapCrypt3DesNamespace.class.php
* @version   0.1.0
* @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
*/
class WapCrypt3DesNamespace
{
	public  function  _construct($key ,$iv)
	{
		// 		$this->key=$key;
		// 		$this->iv=$iv;

	}
	public  $key="%$#(*N@MHGPL><NRMvMghsO*";
	public $iv="s(2L@f!o";

	/**
	 *  加密
	 *
	 */
	public    function encrypt($input)
	{

		$input = $this->padding( $input );
		//$key = base64_decode($this->key);
		$key = $this->key;
		$td = mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
		//使用MCRYPT_3DES算法,cbc模式
		mcrypt_generic_init($td, $key, $this->iv);
		//初始处理
		$data = mcrypt_generic($td, $input);
		//加密
		mcrypt_generic_deinit($td);
		//结束
		mcrypt_module_close($td);
		$data = $this->removeBR(base64_encode($data));
		return $data;
	}

	//
	/**
	* 解密
	* @param string $input
	* @param bool $isHexStr 描述加密字符串
	* @return string
	*/
	public    function decrypt($encrypted,  $isHexStr)
	{

		if($isHexStr==true)
		{
			$encrypted=$this->hex2bin($encrypted);
		}
 
		$key = $this->key;
		$td = mcrypt_module_open( MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
		//使用MCRYPT_3DES算法,cbc模式
		mcrypt_generic_init($td, $key, $this->iv);
		//初始处理
		$decrypted = mdecrypt_generic($td, $encrypted);
		//解密
		mcrypt_generic_deinit($td);
		//结束
		mcrypt_module_close($td);
			
		$decrypted = $this->removePadding($decrypted);
		 
		return $decrypted;
	}

	//填充密码，填充至8的倍数
	private    function padding( $str )
	{
		$len = 8 - strlen( $str ) % 8;
		for ( $i = 0; $i < $len; $i++ )
		{
			$str .= chr( 0);
		}
		return $str ;
	}

	//删除填充符
	private    function removePadding( $str )
	{
		$len = strlen( $str );
		$newstr = "";
		$str = str_split($str);

		$removeflg=0;
		if($str[$len-1]>=0&&$str[$len-1]<10)
		{
			$removeflg=$str[$len-1];

		}
		for ($i = 0; $i < $len; $i++ )
		{

			if ($str[$i] != chr(4))
			{
				$newstr .= $str[$i];
			}
		}
		return $newstr;
	}

	//删除回车和换行
	private    function removeBR( $str )
	{
		$len = strlen( $str );
		$newstr = "";
		$str = str_split($str);
		for ($i = 0; $i < $len; $i++ )
		{
			if ($str[$i] != '\n' and $str[$i] != '\r')
			{
				$newstr .= $str[$i];
			}
		}
			
		return $newstr;
	}



	private     function hex2bin($hexData) {
		$binData = "";
		for($i = 0; $i < strlen ( $hexData ); $i += 2) {
			$binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
		}
		return $binData;
	}
}



?>