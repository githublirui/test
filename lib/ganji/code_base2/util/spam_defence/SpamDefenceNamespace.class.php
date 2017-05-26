<?php
/**
 * @brief 通知防垃圾相关
 * @file          SpamDefenceNamespace.class.php
 * @Copyright (c) 2011 Ganji Inc.
 * @author        chenchaofei <chenchaofei@ganji.com>
 * @version       1.0
 * @date          2011-10-8
 *
 */

require_once GANJI_CONF . '/InterfaceConfig.class.php';
require_once CODE_BASE2 . '/util/http/Curl.class.php';
require_once CODE_BASE2 . '/util/xml/XML2Array.class.php';

class SpamDefenceNamespace{

    const CONNECT_TIMEOUT = 3;

    const OPT_UPDATE = 4;
    const OPT_DELETE = 3;

    /**
     * @brief 实时防垃圾
     * @return
     */
    public static function checkSpamKeyword($content, $type, $dataType = 'Shop'){
        $requestString =<<<RS
<Msg Source="BadKeyword">
    <Operation>Get</Operation>
    <Data Type="{$dataType}">
        <Contents>
            <Content Type="{$type}">{$content}</Content>
        </Contents>
    </Data>
</Msg>
RS;
        $exitString = "<Msg><Operation>Quit</Operation></Msg>";

        $fsock = fsockopen(InterfaceConfig::SERVICE_SPAM_SERVER_IP, InterfaceConfig::SERVICE_SPAM_SERVER_PORT, $errNo, $errStr, self::CONNECT_TIMEOUT);
        if (false == $fsock) {
            return false;
        }
        if (! $responsionStr = fread($fsock, 8192)) {
            $fSockStartObj = simplexml_load_string($responsionStr);
            if ($fSockStartObj->Result != 'AUTHA') {
                fclose($fsock);
                return false;
            }
        }
        if (! fwrite($fsock, $requestString)) {
            fclose($fsock);
            return false;
        }
        if (! $responseString = fread($fsock, 8192)) {
            fclose($fsock);
            return false;
        }
        fwrite($fsock, $exitString);
        fclose($fsock);

        $resultObj = simplexml_load_string($responseString);
        if ($resultObj->ASResult == 'Validate') {
            return true;
        }
        return false;
    }

    /**
     * @brief 装修 上传图库案例 实时防垃圾 ( 装修问答 共用此防垃圾:  第三种 图库自定义标签 对应  问题回答）
     * @param $content
     * @param $type
     * @param $isZhuangxiu 是否装修调用
     * @param $OnlyWord 只判断关键字
     * @return bool
     */
    public static function checkFmSpamKeyword($content, $type, $isZhuangxiu = 0, $OnlyWord = 0){
        $spamType = 'ZhuangXiu';
        if($OnlyWord){
            $spamType = 'BadKeyword';
        }
        $requestString =<<<RS
<Msg Source="{$spamType}">
    <Operation>Get</Operation>
    <Data Type="ZhuangXiu">
        <Contents>
            <Content Type="{$type}"><![CDATA[{$content}]]></Content>
        </Contents>
    </Data>
</Msg>
RS;
        $exitString = "<Msg><Operation>Quit</Operation></Msg>";

        $fsock = fsockopen(InterfaceConfig::SERVICE_SPAM_SERVER_IP, InterfaceConfig::SERVICE_SPAM_SERVER_PORT, $errNo, $errStr, self::CONNECT_TIMEOUT);
        if (false == $fsock) {
            return false;
        }
        if (! $responsionStr = fread($fsock, 8192)) {
            $fSockStartObj = simplexml_load_string($responsionStr);
            if ($fSockStartObj->Result != 'AUTHA') {
                fclose($fsock);
                return false;
            }
        }
        if (! fwrite($fsock, $requestString)) {
            fclose($fsock);
            return false;
        }
        if (! $responseString = fread($fsock, 8192)) {
            fclose($fsock);
            return false;
        }
        fwrite($fsock, $exitString);
        fclose($fsock);

        $resultObj = simplexml_load_string($responseString);
        $array = XML2Array::createArray($responseString);
        //var_dump($array);
        $array = isset($array['Msg']['Reasons']['Reason']) ? $array['Msg']['Reasons']['Reason'] : false;
        if(!$isZhuangxiu){//不是装修
            if ($resultObj->ASResult == 'Validate') {
                return true;
            }
        }else{
            if ($resultObj->ASResult == 'Validate') {
                return true;
            }else if(is_array($array) > 0){
                $returnStr = '';
                $arrCount = count($array);

                foreach($array as $ak => $aitem){
                    if(!is_string($ak)){//2种判断
                        if($aitem['@attributes']['ShotRule'] == 'BadWordRule'){
                            $returnStr .= '含有敏感词汇，请修改'.(($ak + 1) < $arrCount ? '||' : '');
                        }
                        if($aitem['@attributes']['ShotRule'] == 'ExtractRule'){
                            $returnStr .= $aitem['@value'] .(($ak + 1) < $arrCount ? '||' : '');
                        }
                    }else{//一种判断
                        if(isset($aitem['ShotRule']) && $aitem['ShotRule'] == 'BadWordRule'){
                            $returnStr .= '含有敏感词汇，请修改';
                        }
                        if(isset($aitem['ShotRule']) && $aitem['ShotRule'] == 'ExtractRule'){
                            $returnStr .= $array['@value'];
                        }
                    }
                }
                if(strlen($returnStr) <= 0){
                    return false;
                }else{
                    return $returnStr;
                }
            }
        }
        return false;
    }

    /**
     * @brief webim实时防垃圾
     * @return
     */
    public static function checkWebIMSpamKeyword($content){
        $requestString =<<<RS
<Msg Source="BadKeyword">
    <Operation>Get</Operation>
    <Data Type="WebIM">
        <Contents>
            <Content Type="Description">{$content}</Content>
        </Contents>
    </Data>
</Msg>
RS;
        $exitString = "<Msg><Operation>Quit</Operation></Msg>";

        $fsock = fsockopen(InterfaceConfig::SERVICE_SPAM_SERVER_IP, InterfaceConfig::SERVICE_SPAM_SERVER_PORT, $errNo, $errStr, self::CONNECT_TIMEOUT);
        if (false == $fsock) {
            return false;
        }
        if (! $responsionStr = fread($fsock, 8192)) {
            $fSockStartObj = simplexml_load_string($responsionStr);
            if ($fSockStartObj->Result != 'AUTHA') {
                fclose($fsock);
                return false;
            }
        }
        if (! fwrite($fsock, $requestString)) {
            fclose($fsock);
            return false;
        }
        if (! $responseString = fread($fsock, 8192)) {
            fclose($fsock);
            return false;
        }
        fwrite($fsock, $exitString);
        fclose($fsock);
        $resultObj = simplexml_load_string($responseString);
        if ($resultObj->ASResult == 'Validate') {
            return true;
        }
        return false;
    }

    /**
     * @brief 当帖子被编辑的时候，创建编辑后台任务
     * @param string $dbName
     * @param string $tbName
     * @param integer $id
     * @return void
     */
    public static function update($dbName, $tbName , $id){
        if(empty($id) || empty($dbName) || empty($tbName)){
            return null;
        }
        $editUrl = InterfaceConfig::EDITOR_TASK_URL . '?' . http_build_query(array('opt' => self::OPT_UPDATE, 'db' => $dbName, 'tb' => $tbName, 'id' => $id));
        self::makeRequest($editUrl);
    }

    /**
     * @brief 当帖子被删除的时候，通知垃圾贴系统
     * @param $postList
     * @return void
     */
    public static function delete(&$postList){
        $buf = array();
        foreach( $postList as $post ){
            $buf[] = sprintf('%s,%s,%s,%s', $post['post_id'], $post['city_id'], $post['category'], $post['major_category']);
        }
        if( $buf ){
            $url = InterfaceConfig::EDITOR_TASK_URL . '?opt=' . self::OPT_DELETE . '&postlist=' . join('|', $buf);
            self::makeRequest($url);
        }
    }

    /**
     * @brief 请求一个URL
     * @param string $url
     * @return void
     */
    private function makeRequest($url){
        $curlObj = new Curl();
        $curlObj->get($url);
    }
    
    /**
     * @brief 检测公司是否在黑名单中
     * @return 
     */
    public static function checkCompanyKeyword($userName,$CompanyName){
        $requestString =<<<RS
<Msg Source="BlackList">
    <Operation>Get</Operation>
    <Data Type="BlackList">
       <UserDatas>
			<UserData Type="SourceType">CompanyReject</UserData>
            <UserData Type="CategoryIndex">-2</UserData>
            <UserData Type="MajorCategoryIndex">-2</UserData>
	   </UserDatas>
	   <Contents>
	   		<Content Type="UserName">{$userName}</Content>
            <Content Type="CompanyName">{$CompanyName}</Content>
       </Contents>
    </Data>
</Msg>
RS;
        $exitString = "<Msg><Operation>Quit</Operation></Msg>";
        
        $fsock = fsockopen(InterfaceConfig::SERVICE_SPAM_SERVER_IP, InterfaceConfig::SERVICE_SPAM_SERVER_PORT, $errNo, $errStr, self::CONNECT_TIMEOUT);
        if (false == $fsock) {
            return false;
        }
        if (! $responsionStr = fread($fsock, 8192)) {
            $fSockStartObj = simplexml_load_string($responsionStr);
            if ($fSockStartObj->Result != 'AUTHA') {
                fclose($fsock);
                return false;
            }
        }
        if (! fwrite($fsock, $requestString)) {
            fclose($fsock);
            return false;
        }
        if (! $responseString = fread($fsock, 8192)) {
            fclose($fsock);
            return false;
        }
        fwrite($fsock, $exitString);
        fclose($fsock);
        $resultObj = simplexml_load_string($responseString);
        if ($resultObj->ASResult == 'Validate') {
            return true;
        }
        return false;
    }
    /**
     * 检测用户是否在主站黑名单中
     * @param int $userId 用户id 
     * @param string $userName 用户名 
     * @param int $categoryIndex 大类的ScriptIndex 
     * @return bool 在黑名单中返回true,不在返回false
     */
    public static function checkBlackList($userId, $userName, $categoryIndex=-2) {
        $userId = intval($userId);
        $userName = strip_tags($userName);
        $categoryIndex = intval($categoryIndex);
        if($userId <= 0) {
            return false;
        }
        $requestString =<<<RS
<Msg Source="BlackList">
    <Operation>Get</Operation>
    <Data Type="BlackList">
        <UserDatas>
            <UserData Type="SourceType">8</UserData>
            <UserData Type="CategoryIndex">{$categoryIndex}</UserData>
            <UserData Type="MajorCategoryIndex">-2</UserData>
        </UserDatas>
        <Contents>
            <Content Type="UserId">{$userId}</Content>
            <Content Type="UserName">{$userName}</Content>
        </Contents>
    </Data>
</Msg>
RS;

        $fsock = fsockopen(InterfaceConfig::SERVICE_SPAM_SERVER_IP, InterfaceConfig::SERVICE_SPAM_SERVER_PORT, $errNo, $errStr, 3);
        if (false == $fsock) {
            return false;
        }   
        if (! $responsionStr = fread($fsock, 8192)) {
            $fSockStartObj = simplexml_load_string($responsionStr);
            if ($fSockStartObj->Result != 'AUTHA') {
                fclose($fsock);
                return false;
            }   
        }
        if (! fwrite($fsock, $requestString)) {
            fclose($fsock);
            return false;
        }   
        if (! $responseString = fread($fsock, 8192)) {
            fclose($fsock);
            return false;
        }   
        fclose($fsock);

        $resultObj = simplexml_load_string($responseString);
        return $resultObj->ASResult == 'Invalidate' ? true : false;
    }
    
    /**
     * 调用防垃圾实时接口检测是否包含qq号
     */
    public static function checkQQ($content) {
        $requestString = <<<RS
<Msg Source="TextExtract">
    <Operation>Get</Operation>
    <Data Type="TextExtract">
        <Contents>
            <Content Type="TextExtract">{$content}</Content>
        </Contents>
    </Data>
</Msg>
RS;
        $exitString = "<Sys>Quit</Sys>";
        $fsock = fsockopen(InterfaceConfig::WANTED_SPAM_SERVER_IP, InterfaceConfig::WANTED_SPAM_SERVER_PORT, $errNo, $errStr, 2);
        if (!$fsock){
            for ($i = 0; $i <= 1; $i++) {
                $fsock = fsockopen(InterfaceConfig::WANTED_SPAM_SERVER_IP, InterfaceConfig::WANTED_SPAM_SERVER_PORT, $errNo, $errStr, 2);
                if ($fsock) {
                    break;
                }
            }
            if(!$fsock) {
                return false;
            }
        }
        if ($responsionStr = fread($fsock, 8192)) {
            $fSockStartObj = simplexml_load_string($responsionStr);
            if ($fSockStartObj->Result != 'AUTHA') {
                fclose($fsock);
                return false;
            }
        } else {
            fclose($fsock);
            return false;
        }
        
        if (!fwrite($fsock, $requestString)) {
            fclose($fsock);
            return false;
        }
        if (!$responseString = fread($fsock, 8192)) {
            fclose($fsock);
            return false;
        }
        fwrite($fsock, $exitString);
        fclose($fsock);
        return self::getResultFromXml($responseString);
    }
    
    /**
     * 获取接口返回结果
     * @param string $responseString xml
     * @return boolean|multitype:string 结果
     */
    private static function getResultFromXml($responseString) {
        $resultObj = @simplexml_load_string($responseString);
        if ($resultObj->ASResult == 'Validate') {
            return true;
        } elseif ($resultObj->ASResult == 'Invalidate' || $resultObj->ASResult == 'ToReview') {
            $reasons = $resultObj->Reasons;
            if (!is_null($reasons)) {
                foreach ($reasons->children() as $reason) {
                    $attributes = $reason->attributes();
                    $type = htmlspecialchars($attributes['Type']);
                    if($type == 'ExtractRule' && isset($attributes['QQ'])) {
                        $qq = htmlspecialchars($attributes['QQ']);
                        if (!empty($qq)) {
                            return false;
                        }
                    }
                }
            }
            return true;
        } elseif ($resultObj->Result == 'Exception') {
            $errorMessage = '调用实时防垃圾错误：' . $resultObj->Value;
            if (class_exists('Logger')) {
                Logger::logError($errorMessage, 'as_check_qq');
            }
            return true;
        }
        return false;
    }
}
