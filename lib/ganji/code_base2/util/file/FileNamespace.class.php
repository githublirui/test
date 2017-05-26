<?php
/**
 * @brief 文件处理
 * move by zhengyifeng from ganji_v3/framwork/core/file.class.php
 * @todo 功能太少
 * 
 */
class FileNamespace {

	/**
	 * 移动文件
	 *
	 * @param string $mfile  文件路径
	 * @param string $mpath  目标路径
	 * @return 
	 */
	public static function moveFile($mfile, $newPath, $oldPath, $rename = false) {
		if ($newPath != "" && !ereg("\.\.", $newPath)) {
			$oldfile = $oldPath . $mfile;
			$newPath = str_replace("\\", "/", $newPath);
			$newPath = ereg_replace("/{1,}", "/", $newPath);
			$newfile = $newPath . $mfile;
			if ($rename) {
				$newfile = $newPath . $rename;
			}

			if (!file_exists($newPath)) {
				self::makeDirs($newPath);
			}
			if (is_readable($oldPath) && is_file($oldfile) && is_readable($newPath) && is_writable($newPath)) {
				copy($oldfile, $newfile);
				@chown($newfile, 'www');
				unlink($oldfile);
				if (file_exists($newfile)) {
					return 1;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	/*
	 * 递归创建函数
	 */

	static function makeDirs($name, $mode = 0777) { //creates directory tree recursively
		return is_dir($name) or ( self::makeDirs(dirname($name), $mode) and mkdir($name, $mode) );
	}

}

?>
