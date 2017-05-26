<?php
$stat_procedure = "
    drop procedure stat_procedure;
    delimiter $$
        create procedure stat_procedure(in userid int, in paycount int)
        begin
            declare user_id int unsigned default 0;
            declare check_user int unsigned default 0;
            select uid into user_id from test.test_user where uid = userid;
            if user_id then 
                select uid into check_user from test.test_stat where uid = userid;
                if check_user then
                    update test.test_stat set money = money + paycount where uid = userid;
                else
                    insert into test.test_stat set uid = userid, money = paycount;
                end if;
            end if;
        end;
    $$
    delimiter ;
";
?>