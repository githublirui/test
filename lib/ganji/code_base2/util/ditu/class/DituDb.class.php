<?php
    /**
     * 地图基础数据库类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * 
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    require_once(SITE_PATH . '/framework/core/HttpHandler.class.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    //Include Class Files
    require_once(APP_PATH . '/ditu/class/DituDbConfig.class.php');
    
    class DituDb
    {
        /**
         * the Obj of the Master Db
         *
         * @access protected
         * @var object
         */
        protected $masterDbObj = null;
        /**
         * the Obj of the Slave Db
         *
         * @access protected
         * @var object
         */
        protected $slaveDbObj  = null;
        /**
         * the Name of the Database
         *
         * @access protected
         * @var string
         */
        protected $dbName      = DituDbConfig::DEFAULT_DATABASE_NAME;
        /**
         * the Error String After Execute the Sql
         *
         * @access protected
         * @var string
         */
        protected $error       = '';
        /**
         * the Last Executed Sql
         * 
         * @access protected
         * @var string
         */
        protected $sql         = '';
        /**
         * the Construct of the DituDb Class
         *
         * @param  string
         * @return void
         */
        public function __construct($dbname = false)
        {
            if ($dbname === false)
            {
                $this->dbName = DituUtils::getDatabaseName();
            }
            else
            {
                $this->dbName = $dbname;
            }
            $this->masterDbObj = DBFactory::createDb($this->dbName, DBConfig::MASTER, DBConfig::ENCODING_UTF8);
            $this->slaveDbObj  = DBFactory::createDb($this->dbName, DBConfig::SLAVE,  DBConfig::ENCODING_UTF8);
            $this->connect();
        }
        /**
         * Get the Last Executed Sql
         *
         * @param  void
         * @return string $this->sql
         */
        public function getSql()
        {
            return $this->sql;
        }
        /**
         * Get the Error String After Execute the Sql
         * 
         * @param  void
         * @return string $this->error
         */
        public function getSqlError()
        {
            return $this->error;
        }
        /**
         * Get the Data From DataBase
         *
         * @param  string $tables
         * @param  string $columns
         * @param  array  $filters
         * @param  array  $limit
         * @param  array  $orders
         * @param  array  $groupBy
         * @param  string $dataSet
         * @return mixed
         */
        public function getDataFromDb(
            $tables  = DituDbConfig::DEFAULT_TABLE_NAME,
            $columns = DituDbConfig::DEFAULT_COLUMNS,
            $filters = DituDbConfig::DEFAULT_FILTERS,
            $limit   = DituDbConfig::DEFAULT_LIMIT,
            $orders  = DituDbConfig::DEFAULT_ORDERS,
            $groupBy = DituDbConfig::DEFAULT_GROUPBY,
            $dataSet = DituDbConfig::DEFAULT_DATASET
        )
        {
            $this->error = '';
            $this->sql   = SqlBuilder::buildSelectSql($tables, $columns, $filters, $limit, $orders, $groupBy);
            if (array_search($dataSet, DituDbConfig::$ALL_DATASET) === false)
            {
                $this->error = $dataSet;
                return false;
            }
            return $this->$dataSet();
        }        
        /**
         * Connect the Database
         * 
         * @param  object $this->masterDbObj
         * @param  object $this->slaveDbObj
         * @return void
         */
        protected function connect()
        {
            $this->error = '';
            try
            {
                $this->masterDbObj->connect();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
            }
            try
            {
                $this->slaveDbObj->connect();
            }
            catch (Exception $e)
            {
                if (strlen($this->error))
                {
                    $this->error .= ' ' . $e->getMessage();
                }
                else
                {
                    $this->error = $e->getMessage();
                }
            }
        }
        /**
         * Disconnect the Database
         * 
         * @param  object $this->masterDbObj
         * @param  object $this->slaveDbObj
         * @return void
         */
        protected function disconnect()
        {
            $this->error = '';
            try
            {
                $this->masterDbObj->disconnect();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
            }
            try
            {
                $this->slaveDbObj->disconnect();
            }
            catch (Exception $e)
            {
                if (strlen($this->error))
                {
                    $this->error .= ' ' . $e->getMessage();
                }
                else
                {
                    $this->error = $e->getMessage();
                }
            }
        }
        /**
         * Execute the Sql
         *
         * @param  string $this->sql
         * @param  object $this->masterDbObj
         * @return mixed
         */
        protected function execute()
        {
            $this->error = '';
            try
            {
                $res = $this->masterDbObj->execute($this->sql);
                return $res;
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Get All the Result as a Array
         *
         * @param  string $this->sql
         * @param  object $this->slaveDbObj 
         * @return mixed
         */
        protected function getAll()
        {
            $this->error = '';
            try
            {
                $res = $this->slaveDbObj->getAll($this->sql);
                return $res;
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Get the First Row of the Results
         * 
         * @param  string $this->sql
         * @param  object $this->slaveDbObj
         * @return array|boolean
         */
        protected function getRow()
        {
            $this->error = '';
            try
            {
                $res = $this->slaveDbObj->getRow($this->sql);
                return $res;
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Get the First Column of the First Row of the Results
         *
         * @param  string $this->sql
         * @param  object $this->slaveDbObj
         * @return mixed|boolean
         */
        protected function getOne()
        {
            $this->error = '';
            try
            {
                $res = $this->slaveDbObj->getOne($this->sql);
                return $res;
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Begin a Transaction
         *
         * @param  object $this->masterDbObj
         * @return boolean
         */
        protected function begin()
        {
            $this->error = '';
            try
            {
                return $this->masterDbObj->begin();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Commit a Transaction
         *
         * @param  object $this->masterDbObj
         * @return boolean
         */
        protected function commit()
        {
            $this->error = '';
            try
            {
                return $this->masterDbObj->commit();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Rollback a Transaction
         *
         * @param  object $this->masterDbObj
         * @return boolean
         */
        protected function rollback()
        {
            $this->error = '';
            try
            {
                return $this->masterDbObj->rollback();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Get the Last Affected
         *
         * @param  object $this->masterDbObj
         * @return mixed|boolean
         */
        protected function lastAffected()
        {
            $this->error = '';
            try
            {
                return $this->masterDbObj->lastAffected();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                return false;
            }
        }
        /**
         * Get the Last Insert Id
         *
         * @param  object $this->masterDbObj
         * @return mixed
         */
        protected function lastInsertId()
        {
            $this->error = '';
            return $this->masterDbObj->lastInsertId();
        }
        /**
         * the Destruct of the DituDb Class
         *
         * @param  void
         * @return void
         */
        public function __destruct()
        {
            $this->disconnect();
        }
    }