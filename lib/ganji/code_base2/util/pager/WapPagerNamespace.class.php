<?php
/**
 * Enter description here ...
 *
 * @author    wangjian
 * @touch     2012-9-24
 * @category  wap
 * @package   WapPagerNamespace.class.php 
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */
class WapPagerNamespace
{
    
    public $pageSize = 32;
    public $currentPage = 0;
    public $recordCount = 0;
    public $pageTotal = 0;
    //最多1000条数据
    public $maxRecordCount;
    
    /**
    * 迁移移动 mobile_base pager 类，后续和code_base2 合并
    * @param int $pageSize
    * @param int $currentPage
    * @param int $recordCount
    * @param int $maxRecordCount
    * @return StdClass
    */
    public function __construct($pageSize, $currentPage, $recordCount,$maxRecordCount = 1000)
    {
        $this->pageSize = $pageSize;
        $this->currentPage = $currentPage;
        $this->recordCount = $recordCount;
        $this->maxRecordCount = $this->recordCount;
        //最多取1000条
        if($maxRecordCount){
            $this->maxRecordCount = min($this->recordCount,$maxRecordCount);
        }
        // 总页数
        $this->pageTotal = (int)ceil($this->maxRecordCount / $this->pageSize);
    
        $this->currentPage = min($this->currentPage,$this->pageTotal);
    }
   
//     public static  function getWapPager($pageSize, $currentPage, $recordCount,$maxRecordCount = 1000)
//     {
//         //最多取1000条
//         if($maxRecordCount)
//         {
//             $tmpmaxRecordCount=min($recordCount,$maxRecordCount);
//         }else
//         {
//             $tmpmaxRecordCount=$recordCount;
//         }
//         // 总页数
//         $pageTotal= (int)ceil($tmpmaxRecordCount / $pageSize);
//         $pagerArray=array(
//             'pageSize'=>$pageSize,
//             'currentPage' => min($currentPage,$pageTotal),
//             'recordCount' => $recordCount,
//             'maxRecordCount' =>$maxRecordCount,
//             'pageTotal'=> $pageTotal,
//         );
//         return (object)$pagerArray;
//     }
    
    
}