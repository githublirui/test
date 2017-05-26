

Finagle-php框架使用说明
===========================

框架支持操作
---------------
::

    Http请求
    Thrift请求
    Redis操作（未来支持）
    MemCache操作（未来支持）
    MySQL操作（未来支持）


使用说明
--------------
::

    1. 单元测试时建议include这个文件: code_base2/util/finagle/SF_Bootstrap.php
    2. SF_Bootstrap.php这个文件在正式环境中一定不要使用



thrift示例
--------------
::

        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(200, SF_Duration::UNIT_MILLISECONDS))  // 设定连接超时时间, 默认为1s
            ->timeout(new SF_Duration(200, SF_Duration::UNIT_MILLISECONDS))  //设定超时时间, 默认为0.75s
            ->clientFactory(new SF_ThriftClientFactory("GJCounterServiceClient"))  // 指定为thrift client
            ->loadBalance(new SF_RandomLoadBalancerFactory())    // 负载均衡策略
            ->retries(3)        //重试次数，默认为不重试
            ->destName("rta.counter.thrift");  //设定服务名，要使用有意义的名字
            //->hosts($hosts);   //使用动态注册时忽略此选项

        $this->impl=$builder->build();

        try{
            return $this->impl->getPuidTotalPv( $puids );
        }
        catch (Exception $e) {
        }


http示例
-------------
::

        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(200, SF_Duration::UNIT_MILLISECONDS))
            ->timeout(new SF_Duration(200, SF_Duration::UNIT_MILLISECONDS))
            ->clientFactory(new SF_HttpClientFactory())
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->stats(SF_SimpleStats_Factory::get(0.1))    // 设定统计参数，样本比例为: 0.1
            ->retries(3)
            ->destName("ip2city.http");
            // ->hosts($hosts);   //使用动态注册时忽略此选项

        $this->impl=$builder->build();

        // 设定http参数
        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_GET, "/fcgi/ip2city/query?act=ip2city&ip=$ip");
        try {
            $r = $this->impl->execute( $req );
        }
        catch(Exception $e) {

        }


负载均衡说明
----------------
1. SF_RandomLoadBalancerFactory: 随机负载机制
2. SF_RoundRobinLoadBalancerFactory: 轮询负载机制


错误日志
------------

1. 地址: http://cateye.corp.ganji.com
2. category: <业务类型>.finagle.<服务名>.<错误名>


数据统计
--------------
1. 地址: http://graphite.corp.ganji.com/dashboard/
2. category::

    1. 统计次数: stats.finagle.php.<服务名>.service_call.total
    2. 时间相关: stats.timers.finagle.php.<服务名>.xxx

