<?php
/**
 * 渲染类
 * 负责渲染骨架, 所有的pagelet
 * 将所有准备的数据, 填充到骨架中和所有的pagelet中
 * 将pagelet的html交给js去渲染到骨架中的div中去
 * @author zhanglei <zhanglei19881228@sina.com>
 * @date 2015-05-28 17:30
 */
class Render
{
    private $_pagelet = null;
    
    
    public function renderSkeleton()
    {
        if (!$this->_pagelet->getSkeleton())
        {
            return;
        }
        $this->renderHtml();
        $this->flush();
    }
    
    /**
     * 渲染页面, 包括骨架和pagelets
     * @param Pagelet $pagelet Pagelet类
     */
    public function renderPage(Pagelet $pagelet)
    {
        $this->_pagelet = $pagelet;
        $this->renderSkeleton();
        
        $children = $this->_pagelet->getChildren();
        if (!empty($children)){
            foreach ($children as $name => $child)
            {
                $class = $pagelet->getPagelet($name);
                $class->setValue('params', $child);
                $this->renderPagelet($class);
            }
        }
        return;
    }
    
    public function renderPagelet(Pagelet $pagelet)
    {
        $this->_pagelet = $pagelet;
        $params = $this->_pagelet->getValue('params');
        $tpl    = $this->_pagelet->getTemplate();
        $data   = $this->_pagelet->prepareData();
        if (!empty($data))
        {
            extract($data);
        }
        
        if(file_exists($tpl))
        {
            include_once($tpl);
        }
        $params['html'] = ob_get_contents();
     
        ob_clean();
        
        $html = $this->_sendJs($params);
        
        echo $html;
        
        $this->flush();
    }
    
    /**
     * 引入页面, 并且将变量输出
     * @param type $tpl 页面地址
     * @param type $data 数据
     * @return string 
     */
    public function renderHtml()
    {
        $tpl    = $this->_pagelet->getTemplate();
        $data   = $this->_pagelet->prepareData();
        
        if (!empty($data))
        {
            extract($data);
        }
        
        if (file_exists($tpl))
        {
            include_once($tpl);
        }
        return;
    }
    
    
    /**
     * 输出缓冲
     */
    public function flush()
    {
        if (ob_get_level())
        {
            ob_flush();
        }
        flush();
        //usleep(3000000);
    }
    
    /**
     * 将html交给js去放入配置的div中去
     * @param type $params 数据
     * @return type string
     */
    private function _sendJs($params)
    {
        if (!isset($params['func']))
        {
            return;
        }
        
        $function_name = $params['func'];
        unset($params['func']);
        
        return sprintf("<script>%s(%s)</script>", $function_name, json_encode($params));
    }
    
}