<?php

/**
 * 首页，相关类目，应用列表入口配置
 * @author lirui1 <lirui1@ganji.com>
 * @copyright ganji.com
 */
require_once dirname(__FILE__) . '/../../include/AppBackendPage.class.php';

class CategoryMoudlePage extends AppBackendPage {

    public function preExecute() {
        $this->customers = CategoryMoudleConfig::$customers;
        $this->showModes = CategoryMoudleConfig::$showModes;
    }

    /**
     * 
     * 模块列表页面
     */
    public function defaultAction() {
        $m = new ClientCategoryItemConfModel();
        $this->datagrid = $this->_getDataGrid();
        $this->query_customerId = $this->getParameter('query_customerId') ? $this->getParameter('query_customerId') : key($this->customers);
        $this->query_categoryId = $this->getParameter('query_categoryId') ? $this->getParameter('query_categoryId') : 0;
        $this->query_version = $this->getParameter('query_version');
        $this->query_virtualId = $this->getParameter('query_virtualId');
        $allVersions = ClientVersionModel::selectAllVersion($this->query_customerId);
        $this->render(array(
            'datagrid' => $this->datagrid,
            'customers' => $this->customers,
            'categoryIds' => $this->categoryIds,
            'allVersions' => $allVersions,
            'warnStr' => BaseConf::getWarnMsg(),
            'selectCustomer' => $this->query_customerId,
            'selectCategoryId' => $this->query_categoryId,
            'softwareversion' => $this->query_version,
            'virtualId' => $this->query_virtualId,
                ), 'category/categoryMoudleDefault.php');
    }

    /**
     * 
     * ajax通过customerid获取分类id
     */
    public function ajaxGetCategoryIdsAction() {
        $this->render($this->customers[$this->getParameter('customerId')]['categoryIds']);
    }

    /**
     * 
     * ajax获取虚拟分类id
     */
    public function ajaxGetVirtualIdsAction() {
        $customerId = $this->getParameter('customerId');
        $categoryId = $this->getParameter('categoryId');
        if (!isset($this->customers[$customerId]['categoryIds'][$categoryId]['virtualIds'])) {
            $return = '';
        } else {
            $return = $this->customers[$customerId]['categoryIds'][$categoryId]['virtualIds'];
        }
        $this->render($return);
    }

    public function ajaxGetAllVersionAction() {
        $allVersions = ClientVersionModel::selectAllVersion($this->getParameter('customerId'));
        $this->render($allVersions);
    }

    /**
     * 获取模块
     */
    public function ajaxGetModulesAction() {
        $m = new ClientCategoryItemConfModel();
        $virtualId = (int) $this->getPostParameter('virtualId');
        $filter = array(
            array('customer_id', '=', $this->getParameter('customerId')),
            array('category_id', '=', $this->getParameter('categoryId')),
            array('virtual_id', '=', $virtualId),
            array('status', '=', 1),
        );
        $this->render($m->getData(false, array('id', 'module_id', 'module_title'), $filter, array(), array('module_id' => 'asc'), array('module_id')));
    }

    /**
     * 
     * 获取元素列表
     */
    public function ajaxGetItemListAction() {
        $m = new CategoryItemsModel();
        $filter = array(
            array('customer_id', '=', $this->getParameter('customerId')),
            array('category_id', '=', $this->getParameter('categoryId')),
            array('status', '=', 1),
        );
        $this->render($m->getData(false, array('id', 'item_id', 'title'), $filter));
    }

    /**
     * 
     * 删除模块
     */
    public function ajaxDeleteAction() {
        $id = (int) $this->getParameter('id');
        $m = new ClientCategoryItemConfModel();
        $result = $m->updateData(array('status' => 2), array(array('id', '=', $id)));
        $this->render(array(
            'data' => $result,
        ));
        exit;
    }

    //设置数据栏目
    private function _getDataGrid() {
        $dataGrid = new DataGridWidget();
        $url = '/appConfManage/category/categoryMoudle/ajaxGetData/';
        $queryString = $this->getQueryString();
        if (!empty($queryString)) {
            $url .= "?" . $queryString;
        }
        $dataGrid->setUrl($url);
        $dataGrid->setCol('id', 'id', '');
        $dataGrid->setCol("customer_id", "客户端id", "");
        $dataGrid->setCol("category_id", "大类", '');
        $dataGrid->setCol("version_id", "客户端版本", '');
        $dataGrid->setCol('module_id', "条目id", '');
        $dataGrid->setCol('module_title', "标题", '');
        $dataGrid->setCol('action', '操作', '');
        $dataGrid->setFormatCallback(array($this, 'fieldFormat'));
        return $dataGrid;
    }

    /**
     * 
     * 保存更新模块
     */
    public function saveAction() {
        $result = '';
        $data = array();
        $saveType = (int) $this->getGetParameter('saveType');
        $model = new ClientCategoryItemConfModel();
        $data['version_id'] = $this->getPostParameter('versionId') ? trim($this->getPostParameter('versionId')) : 0;
        $data['module_title'] = trim($this->getPostParameter('title'));
        $data['module_icon'] = trim($this->getPostParameter('icon'));
        $data['module_mode'] = trim($this->getPostParameter('mode'));
        $virtual_id = $this->getPostParameter('virtualId');
        $data['ext'] = trim($this->getPostParameter('ext'));
        $items = $this->getPostParameter('new_item_list');
        $itemsArr = explode(',', $items);
        if (is_array($itemsArr) && count($itemsArr) > 0) {
            $data['items'] = $items;
        }
        if ($saveType == 1) {
            //edit
            $id = (int) $this->getParameter('id');
            $result = $model->doSave($data, false, array(array('id', '=', $id)));
        } else {
            //insert
            if ($virtual_id !== false) {
                $data['virtual_id'] = $virtual_id;
            }
            $data['customer_id'] = $this->getPostParameter('customer');
            $data['status'] = 1;
            $data['category_id'] = $this->getPostParameter('categoryId');
            $data['module_id'] = $model->getNewModuleId($data['customer_id'], $data['category_id']);
            $result = $model->doSave($data);
        }
        if (!$result) {
            $errMsg = '保存失败';
        }
        $this->render(array(
            'flag' => $result,
            'errMsg' => $errMsg,
                ), 'result.php');
    }

    /**
     * 
     * 获取模块列表数据 
     */
    public function ajaxGetDataAction() {
        $page = (int) $this->getParameter('page');
        $page = $page <= 0 ? 1 : $page;
        $customer = $this->getParameter('query_customerId');
        $virtualId = $this->getParameter('query_virtualId');
        $categoryId = (int) $this->getParameter('query_categoryId');
        $version = $this->getParameter('query_version');
        $pageSize = 12;
        $dataGrid = $this->_getDataGrid();
        //获取数据筛选条件
        $customer = $customer > 0 ? $customer : 705;
        $clientCategoryItemConfModel = new ClientCategoryItemConfModel();
        $filter = array(array('customer_id', '=', $customer), array('category_id', '=', $categoryId), array('status', '=', 1));
        if ($virtualId !== false) {
            $filter[] = array('virtual_id', '=', $virtualId);
        }
        if (!empty($version)) {
            $filter[] = array('version_id', '<=', $version);
            $data = $clientCategoryItemConfModel->getData(false, '*', $filter, array(($page - 1) * $pageSize, $pageSize), array('module_id' => 'asc'), 'module_id');
            $count = $clientCategoryItemConfModel->getData(false, 'count(1) as total', $filter, array(), array(), 'module_id');
        } else {
            $data = $clientCategoryItemConfModel->getData(false, '*', $filter, array(($page - 1) * $pageSize, $pageSize), array('version_id' => 'asc', 'module_id' => 'asc'));
            $count = $clientCategoryItemConfModel->getData(false, 'count(1) as total', $filter);
        }
        $dataGrid->setData($data);
        $dataGrid->setPager($count[0]['total'], $page, $pageSize);
        $this->render(array(
            'data' => $dataGrid->getData(),
        ));
    }

    public function fieldFormat($name, $row) {
        if ($name == 'action') {
            return '<a href="/appConfManage/category/categoryMoudle/edit?id=' . $row['id'] . '">
                <i class="splashy-pencil_small"></i>编辑
            </a>
            <a href="javascript:void(0)" class="deleteModule" link="/appConfManage/category/categoryMoudle/ajaxDelete?id=' . $row['id'] . '">
                <i class="splashy-error_x"></i>删除
            </a>';
        }
        if ($name == 'category_id') {
            return $this->customers[$row['customer_id']]['categoryIds'][$row['category_id']]['name'];
        }
        return $row[$name];
    }

    /**
     * 
     * 
     * 编辑模块
     */
    public function editAction() {
        $id = (int) $this->getParameter('id');
        $clientCategoryItemConfModel = new ClientCategoryItemConfModel();
        $categoryItemsModel = new CategoryItemsModel();
        $module = current($clientCategoryItemConfModel->getData(false, '*', array(array('id', '=', $id))));
        //获取子元素信息
        $itemIds = explode(',', $module['items']);
        $filterItem = array(
            array('customer_id', '=', $module['customer_id']),
            array('category_id', '=', $module['category_id']),
            array('status', '=', 1),
            array('item_id', 'IN', $itemIds)
        );
        $itemList = $categoryItemsModel->getData(false, array('id', 'item_id', 'title'), $filterItem);
        $module['items'] = $itemList;
        $filter = array(
            array('customer_id', '=', $module['customer_id']),
            array('category_id', '=', $module['category_id']),
            array('status', '=', 1),
            array('id', 'NOT IN', $itemIds),
        );
        $allItemList = $categoryItemsModel->getData(false, array('id', 'item_id', 'title'), $filter);
        //过滤已经选中的
        $allVersions = ClientVersionModel::selectAllVersion($module['customer_id']);
        $this->render(array(
            'module' => $module,
            'allItemList' => $allItemList,
            'customers' => $this->customers,
            'allVersions' => $allVersions,
            'warnStr' => BaseConf::getWarnMsg(),
            'showModes' => $this->showModes,
                ), 'category/categoryMoudleEdit.php');
    }

    /**
     * 
     * 创建模块
     */
    public function createAction() {
        $this->render(array(
            'warnStr' => BaseConf::getWarnMsg(),
            'customers' => $this->customers,
            'showModes' => $this->showModes,
            'categoryIds' => $this->categoryIds,
                ), 'category/categoryMoudleCreate.php');
    }

    /**
     * 
     * 生成配置
     */
    public function generateConfigAction() {
        $customerId = $this->getParameter('customerId');
        $categoryId = (int) $this->getParameter('categoryId');
        $virtualId = (int) $this->getParameter('virtualId');
        $m = new ClientCategoryItemConfModel();
        $fileName = $customerId . '_' . $categoryId;
        if ($virtualId > 0) {
            $fileName .= '_' . $virtualId;
        }
        ob_end_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        echo ($m->generateConfig($customerId, $categoryId));
        exit;
    }

    public function includeFiles() {
        require_once BACKEND . '/widget/DataGridWidget.class.php';
        require_once CODE_BASE2 . '/app/mobile_client/model/ClientVersionModel.class.php';
        require_once APP_BACKEND . '/model/category/ClientCategoryItemConfModel.class.php';
        require_once APP_BACKEND . '/model/category/CategoryItemsModel.class.php';
        require_once APP_BACKEND . '/config/CategoryMoudleConfig.class.php';
    }

}
