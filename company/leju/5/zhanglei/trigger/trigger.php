/**
 * delimiter $$
 *     drop trigger test.t_order_0_trigger$$ 
 *     create 
 *     trigger t_order_0_trigger after insert on test_order_0
 *     for each row
 *     begin 
            declare user_id int unsigned default 0;
            declare check_user int unsigned default 0;
            select uid into user_id from test.test_user where uid = new.uid;
            if user_id then 
                select uid into check_user from test.test_stat where uid = new.uid;
                if check_user then
                    update test.test_stat set money = money + new.money where uid = new.uid;
                else
                    insert into test.test_stat set uid = new.uid, money = new.money;
                end if;
            end if;
 *     end;
 * $$
 * delimiter;
 */

DELIMITER $$ 
    drop trigger test.t_order_0_trigger$$ 
    create 
    trigger t_order_0_trigger after insert on test_order_0 
    for each row 
    begin 
        declare user_id int unsigned default 0;
        declare check_user int unsigned default 0;
        select uid into user_id from test.test_user where uid = new.uid;
        if user_id then 
            select uid into check_user from test.test_stat where uid = new.uid;
            if check_user then
                update test.test_stat set money = money + new.money where uid = new.uid;
            else
                insert into test.test_stat set uid = new.uid, money = new.money;
            end if;
        end if;
    end;
$$ 
DELIMITER ; 

声明 $$分隔符
声明 ; 分隔符