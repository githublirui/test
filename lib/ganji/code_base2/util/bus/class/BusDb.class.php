<?php
    /**
     * 公交数据访问处理类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusDbConfig.class.php');
    
    class BusDb
    {
        /**
         * the Value of the City Domain
         *
         * @access protected
         * @var string
         */
        protected $city  = BusConfig::DEFAULT_CITY_DOMAIN;
        /**
         * the Resource of the Database
         *
         * @access protected
         * @var resource
         */
        protected $dbObj = null;
        /**
         * Use Which Db, Slave or Master
         *
         * @access protected
         * @var integer
         */
        protected $db    = DBConfig::SLAVE;
        /**
         * the Error String After Execute the Sql
         *
         * @access protected
         * @var string
         */
        protected $error = '';
        /**
         * the Last Executed Sql
         * 
         * @access protected
         * @var string
         */
        protected $sql   = '';
        /**
         * the Construct of the BusDb Class
         *
         * @param  string
         * @return void
         */
        public function __construct()
        {
            if (func_num_args() == 2 && isset(BusConfig::$BUS_CITYS_MAP[func_get_arg(0)]))
            {
                $this->city = func_get_arg(0);
                $this->db   = func_get_arg(1);
            }
            else if (func_num_args() == 1 && isset(BusConfig::$BUS_CITYS_MAP[func_get_arg(0)]))
            {
                $this->city = func_get_arg(0);
            }
            else
            {
                $this->city = BusUtils::getCityByDomain();
            }
            $this->dbObj = DBFactory::createDb(BusDbConfig::DEFAULT_DATABASE_NAME, $this->db, DBConfig::ENCODING_UTF8);
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
            $tables  = BusDbConfig::DEFAULT_TABLE_NAME,
            $columns = BusDbConfig::DEFAULT_COLUMNS,
            $filters = BusDbConfig::DEFAULT_FILTERS,
            $limit   = BusDbConfig::DEFAULT_LIMIT,
            $orders  = BusDbConfig::DEFAULT_ORDERS,
            $groupBy = BusDbConfig::DEFAULT_GROUPBY,
            $dataSet = BusDbConfig::DEFAULT_DATASET
        )
        {
            $this->error = '';
            $this->sql   = SqlBuilder::buildSelectSql($tables, $columns, $filters, $limit, $orders, $groupBy);
            if (array_search($dataSet, BusDbConfig::$ALL_DATASET) === false)
            {
                $this->error = $dataSet;
                return false;
            }
            return $this->$dataSet();
        }        
        /**
         * Connect the Database
         * 
         * @param  object $this->dbObj
         * @return void
         */
        protected function connect()
        {
            $this->error = '';
            try
            {
                $this->dbObj->connect();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
            }
        }
        /**
         * Disconnect the Database
         * 
         * @param  object $this->dbObj
         * @return void
         */
        protected function disconnect()
        {
            $this->error = '';
            try
            {
                $this->dbObj->disconnect();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
            }
        }
        /**
         * Execute the Sql
         *
         * @param  string $this->sql
         * @return mixed
         */
        protected function execute()
        {
            $this->error = '';
            try
            {
                $res = $this->dbObj->execute($this->sql);
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
         * @param  $this->sql
         * @return mixed
         */
        protected function getAll()
        {
            $this->error = '';
            try
            {
                $res = $this->dbObj->getAll($this->sql);
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
         * @param  object $this->dbObj
         * @return array|boolean
         */
        protected function getRow()
        {
            $this->error = '';
            try
            {
                $res = $this->dbObj->getRow($this->sql);
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
         * @param  object $this->dbObj
         * @return mixed|boolean
         */
        protected function getOne()
        {
            $this->error = '';
            try
            {
                $res = $this->dbObj->getOne($this->sql);
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
         * @param  $this->dbObj
         * @return boolean
         */
        protected function begin()
        {
            $this->error = '';
            try
            {
                return $this->dbObj->begin();
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
         * @param  object $this->dbObj
         * @return boolean
         */
        protected function commit()
        {
            $this->error = '';
            try
            {
                return $this->dbObj->commit();
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
         * @param  $this->dbObj
         * @return boolean
         */
        protected function rollback()
        {
            $this->error = '';
            try
            {
                return $this->dbObj->rollback();
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
         * @param  $this->dbObj
         * @return mixed|boolean
         */
        protected function lastAffected()
        {
            $this->error = '';
            try
            {
                return $this->dbObj->lastAffected();
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
         * @param  $this->dbObj
         * @return mixed
         */
        protected function lastInsertId()
        {
            $this->error = '';
            return $this->dbObj->lastInsertId();
        }
        /**
         * the Destruct of the BusDb Class
         *
         * @param  resource $this->dbObj
         * @return void
         */
        public function __destruct()
        {
            $this->disconnect();
        }
    }