<?php

class Apc {

    /**
     * Apc����-���û���
     * ���û���key��value�ͻ���ʱ��
     * @param  string $key   KEYֵ
     * @param  string $value ֵ
     * @param  string $time  ����ʱ��
     */
    public function set_cache($key, $value, $time = 0) {
        if ($time == 0)
            $time = null; //null��������û��� 
        return apc_store($key, $value, $time);
    }

    /**
     * Apc����-��ȡ����
     * ͨ��KEY��ȡ��������
     * @param  string $key   KEYֵ
     */
    public function get_cache($key) {
        return apc_fetch($key);
    }

    /**
     * Apc����-���һ������
     * ��memcache��ɾ��һ������
     * @param  string $key   KEYֵ
     */
    public function clear($key) {
        return apc_delete($key);
    }

    /**
     * Apc����-������л���
     * ������ʹ�øù���
     * @return
     */
    public function clear_all() {
        apc_clear_cache('user'); //����û����� 
        return apc_clear_cache(); //������� 
    }

    /**
     * ���APC�����Ƿ����
     * @param  string $key   KEYֵ
     */
    public function exists($key) {
        return apc_exists($key);
    }

    /**
     * �ֶ�����-���ڼ���
     * @param string $key  KEYֵ
     * @param int    $step ������stepֵ
     */
    public function inc($key, $step) {
        return apc_inc($key, (int) $step);
    }

    /**
     * �ֶ��Լ�-���ڼ���
     * @param string $key  KEYֵ
     * @param int    $step ������stepֵ
     */
    public function dec($key, $step) {
        return apc_dec($key, (int) $step);
    }

    /**
     * ����APC������Ϣ
     */
    public function info() {
        return apc_cache_info();
    }

}

