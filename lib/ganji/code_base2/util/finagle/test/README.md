测试实例说明
###############


目录结构:

|- integrate   集成测试
|- lib         测试的公用库
|- unit        单元测试用例



注意:

    1. 使用测试之前需要先启动相应服务




需要提前启动的服务:

    1. 在192.168.35.141这台机器上启动finagle.hello_service.deploy.jar，占用端口为8888::

        java -jar -Dservice.host=192.168.35.141 -Dservice.port=8888\
            -Dservice.announce=zk!192.168.129.213:2181!/soa/services/hello!1\
               /var/www/hellodemo/finagle.hello_service.deploy.jar






