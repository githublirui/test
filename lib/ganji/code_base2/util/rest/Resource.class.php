<?php

interface Resource
{
	/**
	 * 删除资源
	 *
	 */
	public function delete();
	
	/**
	 * 列表资源
	 *
	 */
	public function items();
	
	/**
	 * 获得资源
	 *
	 */
	public function get();
	
	/**
	 * 修改资源
	 *
	 */
	public function put();
	
	/**
	 * 新增资源
	 *
	 */
	public function post();
	
}
