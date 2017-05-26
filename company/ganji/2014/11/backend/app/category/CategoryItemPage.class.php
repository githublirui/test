<?php

/**
 * 首页，相关类目，应用列表入口配置
 * @author lirui1 <lirui1@ganji.com>
 * @copyright ganji.com
 */
require_once dirname(__FILE__) . '/../../include/AppBackendPage.class.php';

class CategoryItemPage extends AppBackendPage {

    public function preExecute() {
        $this->customers = CategoryMoudleConfig::$customers;
        $this->query_customerId = $this->getParameter('query_customerId') ? $this->getParameter('query_customerId') : key($this->customers);
        $this->query_categoryId = $this->getParameter('query_categoryId');
    }

    public function defaultAction() {
        $this->datagrid = $this->_getDataGrid();
        $this->query_virtualId = $this->getParameter('query_virtualId');
        $this->render(array(
            'datagrid' => $this->datagrid,
            'customers' => $this->customers,
            'categoryIds' => $this->categoryIds,
            'warnStr' => BaseConf::getWarnMsg(),
            'selectCustomer' => $this->query_customerId,
            'selectCategoryId' => $this->query_categoryId,
            'virtualId' => $this->query_virtualId,
                ), 'category/categoryItemDefault.php');
    }

    //设置数据栏目
    private function _getDataGrid() {
        $dataGrid = new DataGridWidget();
        $url = '/appConfManage/category/categoryItem/ajaxGetData/';
        $queryString = $this->getQueryString();
        if (!empty($queryString)) {
            $url .= "?" . $queryString;
        }
        $dataGrid->setUrl($url);
        $dataGrid->setCol('id', 'id', '');
        $dataGrid->setCol("customer_id", "客户端id", "");
        $dataGrid->setCol("category_id", "大类", '');
        $dataGrid->setCol("item_id", "条目id", '');
        $dataGrid->setCol("title", "标题", '');
        $dataGrid->setCol('created', "创建时间", '');
        $dataGrid->setCol('updated', "更新时间", '');
        $dataGrid->setCol('action', '操作', '');
        $dataGrid->setFormatCallback(array($this, 'fieldFormat'));
        return $dataGrid;
    }

    /**
     * 
     * 保存模块
     */
    public function saveAction() {
        $result = '';
        $data = array();
        $saveType = (int) $this->getGetParameter('saveType');
        $model = new CategoryItemsModel();
        $data['title'] = trim($this->getPostParameter('title'));
        $data['img_url'] = trim($this->getPostParameter('img_url'));
        $data['is_new'] = trim($this->getPostParameter('is_new'));
        $data['jump_type'] = trim($this->getPostParameter('jump_type'));
        $data['data_params'] = trim($this->getPostParameter('data_params'));
        $data['short_title'] = trim($this->getPostParameter('short_title'));
        $data['data_source'] = $this->getPostParameter('data_source');
        $data['ext'] = trim($this->getPostParameter('ext'));
        $virtualId = $this->getPostParameter('virtualId');
        $data['updated'] = time();
        if ($saveType == 1) {
            //edit
            $id = (int) $this->getGetParameter('id');
            $result = $model->doSave($data, false, array(array('id', '=', $id)));
        } else {
            //insert
            if ($virtualId !== false) {
                $data['virtual_id'] = $virtualId;
            }
            $data['customer_id'] = $this->getPostParameter('customer');
            $data['category_id'] = $this->getPostParameter('categoryId');
            $data['item_id'] = $this->getPostParameter('item_id') ? $this->getPostParameter('item_id') : $model->getNewItemId($data['customer_id'], $data['category_id'], $this->getPostParameter('moduleId'));
            $data['status'] = 1;
            $data['created'] = time();
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
        $categoryId = (int) $this->getParameter('query_categoryId');
        $virtualId = $this->getParameter('query_virtualId');
        $pageSize = 12;
        $dataGrid = $this->_getDataGrid();
        //获取数据筛选条件
        $customer = $customer > 0 ? $customer : 705;
        $categoryItemsModel = new CategoryItemsModel();
        $filter = array(array('customer_id', '=', $customer), array('category_id', '=', $categoryId), array('status', '=', 1));
        if ($virtualId !== false) {
            $filter[] = array('virtual_id', '=', $virtualId);
        }
        $data = $categoryItemsModel->getData(false, '*', $filter, array(($page - 1) * $pageSize, $pageSize), array('item_id' => 'asc'));
        $count = $categoryItemsModel->getData(false, 'count(1) as total', $filter);
        $dataGrid->setData($data);
        $dataGrid->setPager($count[0]['total'], $page, $pageSize);
        $this->render(array(
            'data' => $dataGrid->getData(),
        ));
    }

    public function fieldFormat($name, $row) {
        if ($name == 'action') {
            return '<a href="/appConfManage/category/categoryItem/edit?id=' . $row['id'] . '">
                <i class="splashy-pencil_small"></i>编辑
            </a>
            <a href="javascript:void(0)" class="deleteModule" link="/appConfManage/category/categoryItem/ajaxDelete?id=' . $row['id'] . '">
                <i class="splashy-error_x"></i>删除
            </a>';
        }
        if ($name == 'category_id') {
            return $this->customers[$row['customer_id']]['categoryIds'][$row['category_id']]['name'];
        }
        if ($name == 'created' || $name == 'updated') {
            if ($row[$name] <= 0) {
                return '';
            }
            return date('Y-m-d H:i:s', $row[$name]);
        }
        return $row[$name];
    }

    public function editAction() {
        $id = (int) $this->getParameter('id');
        $categoryItemsModel = new CategoryItemsModel();
        $item = current($categoryItemsModel->getData(false, '*', array(array('id', '=', $id))));
        $this->render(array(
            'item' => $item,
            'customers' => $this->customers,
            'jumpTypes' => CategoryMoudleConfig::$itemJumpTypes,
                ), 'category/categoryItemEdit.php');
    }

    public function ajaxDeleteAction() {
        $id = (int) $this->getParameter('id');
        $m = new CategoryItemsModel();
        $result = $m->updateData(array('status' => 2), array(array('id', '=', $id)));
        $this->render(array(
            'data' => $result,
        ));
        exit;
    }

    public function createAction() {
        $this->render(array(
            'warnStr' => BaseConf::getWarnMsg(),
            'jumpTypes' => CategoryMoudleConfig::$itemJumpTypes,
            'dataSources' => CategoryMoudleConfig::$itemDataSources,
            'customers' => $this->customers,
            'categoryIds' => $this->categoryIds,
                ), 'category/categoryItemCreate.php');
    }

    public function includeFiles() {
        require_once BACKEND . '/widget/DataGridWidget.class.php';
        require_once APP_BACKEND . '/config/CategoryMoudleConfig.class.php';
        require_once APP_BACKEND . '/model/category/CategoryItemsModel.class.php';
    }

}
