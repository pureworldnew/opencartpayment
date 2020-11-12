<?php
// ***************************************************
//                       File
//                  File Functions       
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************

if (!function_exists('isSymbolicLink')) {
	function isSymbolicLink($target) { // Windows is_link() hack
		if (defined('PHP_WINDOWS_VERSION_BUILD')) {
			if (file_exists($target) && str_replace('\\', '/', readlink($target)) != str_replace('\\', '/', $target)) {

				return true;
			}
		} elseif (is_link($target)) {
			return true;
		}
		return false;
	}
}

if (!function_exists('fix_path')) {
	function fix_path($file, $src_dir, $dst_dir) {

		if (defined('DIR_SYSTEM') && version_compare(VERSION, '2.3.0.2', '>=')) {
			
			$root_path = substr_replace(DIR_SYSTEM, '/', -8);	// -8 = /system/

			$src = $root_path . $src_dir . $file;
			$dst = $root_path . $dst_dir . $file;
			
			if (isSymbolicLink($src)) { // isSymbolicLink() : see leverod/system/library/file.php
				
				@symlink(readlink($src), $dst);
				unlink($src);
			
			} else {
			
				if (is_file($src)){
					rename($src, $dst); // move the file from $src to $dst, overwriting existing file, if any.
				}
			}
		}
	}
}
