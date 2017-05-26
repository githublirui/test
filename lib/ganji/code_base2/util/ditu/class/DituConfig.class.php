<?php
    /**
     * 地图配置类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     *            zhangjian <zhangjian@staff.ganji.com>
     * @info
     *            add by zhangjian
     *            add const EARTH_RADII
     *
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    final class DituConfig
    {
        /** 
         * the Map of the Ditu Code of the City Chinese Name
         * 
         * @staticvar array
         */
        public static $CITYS_DITU_CODE = array(
            //四个直辖市
            '北京'     => '980|600|1;2;3;4;5;|fjtekqrposNKGE;hrlphplnJOOE;11;true;true;北京|http://www.mapabc.com/qnmap/map.xml|BE',
            '上海'     => '980|600|1;2;3;4;5;|fkoelopplrJOGE;hjlijlnlnNOOI;11;true;true;上海|http://www.mapabc.com/qnmap/map.xml|BE',
            '天津'     => '980|600|1;2;3;4;|fjuejmmjsJOOE;hrlhhkslnJKGE;11;false;false;天津|http://www.mapabc.com/qnmap/map.xml|BE',
            '重庆'     => '980|600|1;2;3;4;|fitemlskmtNOOE;grllmlthrNGOI;11;false;false;重庆|http://www.mapabc.com/qnmap/map.xml|BE',
            //广东省
            '广州'     => '980|600|1;2;3;4;5;|fjqekjmjmqFKOE;gllhkllgoJKKE;11;true;true;广州|http://www.mapabc.com/qnmap/map.xml|BE',
            '深圳'     => '980|600|1;2;3;4;5;|fjrehmplrqJGOM;gkllljlktNOKI;11;true;true;深圳|http://www.mapabc.com/qnmap/map.xml|BE',
            '东莞'     => '980|600|1;2;3;4;|fjqeomtjooJGGI;gllgkpojsFGOE;11;false;false;东莞|http://www.mapabc.com/qnmap/map.xml|BE',
            '珠海'     => '980|600|1;2;3;4;|fjqempnnsuJOOM;gklinhrkmJGOE;11;false;false;珠海|http://www.mapabc.com/qnmap/map.xml|BE',
            '汕头'     => '980|600|1;2;3;4;|fjteojnnnrNGOE;glljnhlgnJKKM;11;false;false;汕头|http://www.mapabc.com/qnmap/map.xml|BE',
            '佛山'     => '980|600|1;2;3;4;|fjqeiiklnvJOOI;gllgjhrppNGOM;11;false;false;佛山|http://www.mapabc.com/qnmap/map.xml|BE',
            '江门'     => '980|600|1;2;3;4;|fjpenqllqrNGOE;gkljnhnooNOKE;11;false;false;江门|http://www.mapabc.com/qnmap/map.xml|BE',
            '中山'     => '980|600|1;2;3;4;|fjqekolloqNKGI;gklljltptNOKI;11;false;false;中山|http://www.mapabc.com/qnmap/map.xml|BE',
            '惠州'     => '980|600|1;2;3;4;|fjrekqtmlqJGKM;gllgqksgoJGKM;11;false;false;惠州|http://www.mapabc.com/qnmap/map.xml|BE',
            '茂名'     => '980|600|1;2;3;4;|fjneqjonlvNKKE;gjlmnjrjpFGKI;11;false;false;茂名|http://www.mapabc.com/qnmap/map.xml|BE',
            '韶关'     => '980|600|1;2;3;4;|fjqemqqoowJGKI;gmlnqmklmJOOM;11;false;false;韶关|http://www.mapabc.com/qnmap/map.xml|BE',
            '湛江'     => '980|600|1;2;3;4;|fjneljklrqJGKI;gjlhqpsltNOOM;11;false;false;湛江|http://www.mapabc.com/qnmap/map.xml|BE',
            '肇庆'     => '980|600|1;2;3;4;|fjpelopnmpNGOM;gllgoknoNOOE;11;false;false;肇庆|http://www.mapabc.com/qnmap/map.xml|BE',
            '梅州'     => '980|600|1;2;3;4;|fjteiirlmpJOKI;gmliqksgpFKKI;11;false;false;梅州|http://www.mapabc.com/qnmap/map.xml|BE',
            '汕尾'     => '980|600|1;2;3;4;|fjsekolgtsJGKI;gklnpllpmJKKM;11;false;false;汕尾|http://www.mapabc.com/qnmap/map.xml|BE',
            '河源'     => '980|600|1;2;3;4;|ggijrqppotNKNI;hicsqhlplNGNI;11;false;false;河源|http://www.mapabc.com/qnmap/map.xml|BE',
            '阳江'     => '980|600|1;2;3;4;|ggfjuplrjpFKNI;hgctsirqiJOJI;11;false;false;阳江|http://www.mapabc.com/qnmap/map.xml|BE',
            '清远'     => '980|600|1;2;3;4;|gghjllnpnpNONM;hicrtpkokJKNM;11;false;false;清远|http://www.mapabc.com/qnmap/map.xml|BE',
            '潮州'     => '980|600|1;2;3;4;|fjtenlmgktNKGI;gllmoingqFOOE;11;false;false;潮州|http://www.mapabc.com/qnmap/map.xml|BE',
            '揭阳'     => '980|600|1;2;3;4;|fjtekntnsrJOKM;gllljopmlNKKM;11;false;false;揭阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '云浮'     => '980|600|1;2;3;4;|gggjllmljNKNI;hhcunqlskJKNI;11;false;false;云浮|http://www.mapabc.com/qnmap/map.xml|BE',
            //四川省
            '成都'     => '980|600|1;2;3;4;|firehnpomvJKKI;hilmmpliNKKM;11;false;false;成都|http://www.mapabc.com/qnmap/map.xml|BE',
            '自贡'     => '980|600|1;2;3;4;|fireookioqNOGM;grljmknolFGOI;11;false;false;自贡|http://www.mapabc.com/qnmap/map.xml|BE',
            '泸州'     => '980|600|1;2;3;4;|fiselmllqpFOGM;gqlopmkhpNGKE;11;false;false;泸州|http://www.mapabc.com/qnmap/map.xml|BE',
            '德阳'     => '980|600|1;2;3;4;|gfijopproqNOFM;igcmonqmnNGJM;11;false;false;德阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '绵阳'     => '980|600|1;2;3;4;|fireoooiqsNOKM;hjlkmmsnqNOGI;11;false;false;绵阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '南充'     => '980|600|1;2;3;4;|gfkjlqmsptJKJI;ifcstllsjNKFM;11;false;false;南充|http://www.mapabc.com/qnmap/map.xml|BE',
            '凉山'     => '980|600|1;2;3;4;|gfgjnlknpoNKNM;hmculqnmqJKJI;11;false;false;凉山彝族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '乐山'     => '980|600|1;2;3;4;|fiqeolqjrqNGKM;grllpnqhoNOOM;11;false;false;乐山|http://www.mapabc.com/qnmap/map.xml|BE',
            '达州'     => '980|600|1;2;3;4;|gfljppqnpFOFE;igcnmjnrqJKJM;11;false;false;达州|http://www.mapabc.com/qnmap/map.xml|BE',
            '宜宾'     => '980|600|1;2;3;4;|firenjsmttFOOI;gqlnmqmiqJOKM;11;false;false;宜宾|http://www.mapabc.com/qnmap/map.xml|BE',
            '攀枝花'   => '980|600|1;2;3;4;|gffjrqrmjnNOJI;hlcqsklllFONE;11;false;false;攀枝花|http://www.mapabc.com/qnmap/map.xml|BE',
            '广元'     => '980|600|1;2;3;4;|gfjjtklronNKNI;ihcpopmpqNKNM;11;false;false;广元|http://www.mapabc.com/qnmap/map.xml|BE',
            '遂宁'     => '980|600|1;2;3;4;|gfjjqplljrJGNM;ifcqmkppqFOJM;11;false;false;遂宁|http://www.mapabc.com/qnmap/map.xml|BE',
            '内江'     => '980|600|1;2;3;4;|fisehnmplvNGOE;grllpjpNGGM;11;false;false;内江|http://www.mapabc.com/qnmap/map.xml|BE',
            '广安'     => '980|600|1;2;3;4;|gfkjrljsqnNKFM;ifcpshooFKJM;11;false;false;广安|http://www.mapabc.com/qnmap/map.xml|BE',
            '眉山'     => '980|600|1;2;3;4;|fiqepjqhqrJKGM;hilgkpslpNGKM;11;false;false;眉山|http://www.mapabc.com/qnmap/map.xml|BE',
            '雅安'     => '980|600|1;2;3;4;|gfhjlinminNGNI;hocuukorqJKNI;11;false;false;雅安|http://www.mapabc.com/qnmap/map.xml|BE',
            '巴中'     => '980|600|1;2;3;4;|gfkjsmmrjmJKJM;igctqolqnJOFI;11;false;false;巴中|http://www.mapabc.com/qnmap/map.xml|BE',
            '资阳'     => '980|600|1;2;3;4;|firenmkmrvFGOM;hilhjppmoJKOM;11;false;false;资阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '阿坝'     => '980|600|1;2;3;4;|JHKMRMISJTPLLL;LIGWYTNUOLDHL;11;false;false;阿坝|http://www.mapabc.com/qnmap/map.xml|BE',
            '甘孜'     => '980|600|1;2;3;4;|JHJMYQIQOQHHHL;LHGOTRNWRLHLL;11;false;false;甘孜|http://www.mapabc.com/qnmap/map.xml|BE',
            //浙江省
            '杭州'     => '980|600|1;2;3;4;|fkneiomprqJGOM;hilioktnqNGKI;11;false;false;杭州|http://www.mapabc.com/qnmap/map.xml|BE',
            '宁波'     => '980|600|1;2;3;4;|fkoemlnptrNOOI;grlonpnjpJKGE;11;false;false;宁波|http://www.mapabc.com/qnmap/map.xml|BE',
            '温州'     => '980|600|1;2;3;4;|fknenkrlluNKOI;gqlgjokhtFOOI;11;false;false;温州|http://www.mapabc.com/qnmap/map.xml|BE',
            '嘉兴'     => '980|600|1;2;3;4;|fkneomslooNGKI;hilnmktiJOOI;11;false;false;嘉兴|http://www.mapabc.com/qnmap/map.xml|BE',
            '湖州'     => '980|600|1;2;3;4;|fknehqqpmuJOKM;hiloohpprNKGI;11;false;false;湖州|http://www.mapabc.com/qnmap/map.xml|BE',
            '绍兴'     => '980|600|1;2;3;4;|fknemqmptoJKGM;hilghkpplJOGM;11;false;false;绍兴|http://www.mapabc.com/qnmap/map.xml|BE',
            '金华'     => '980|600|1;2;3;4;|mnvfplnotnhOOHK;nvkhqjiktsGOLK;11;false;false;金华|http://www.mapabc.com/qnmap/map.xml|BE',
            '衢州'     => '980|600|1;2;3;4;|ggmjtommopJKNI;hncuomrljJKFE;11;false;false;衢州|http://www.mapabc.com/qnmap/map.xml|BE',
            '舟山'     => '980|600|1;2;3;4;|fkpekhtjppJOKM;grlpmjrjnJOKM;11;false;false;舟山|http://www.mapabc.com/qnmap/map.xml|BE',
            '台州'     => '980|600|1;2;3;4;|fkoelmppkrNKKE;gqlmohrprNGKI;11;false;false;台州|http://www.mapabc.com/qnmap/map.xml|BE',
            '丽水'     => '980|600|1;2;3;4;|ggnjuiosntJOFE;hncpqjknqNKJM;11;false;false;丽水|http://www.mapabc.com/qnmap/map.xml|BE',
            //贵州省
            '贵阳'     => '980|600|1;2;3;4;|fiteohsmluJGOI;gollnotlpFOKI;11;false;false;贵阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '六盘水'   => '980|600|1;2;3;4;|JHMMXNHRJPHDLL;KNGTYNIUMLDLH;11;false;false;六盘水|http://www.mapabc.com/qnmap/map.xml|BE',
            '遵义'     => '980|600|1;2;3;4;|gfkjukjnjpNGNI;hmcrujomiNONM;11;false;false;遵义|http://www.mapabc.com/qnmap/map.xml|BE',
            '安顺'     => '980|600|1;2;3;4;|gfjjujplnpNKFM;hlcnqoksjNGJM;11;false;false;安顺|http://www.mapabc.com/qnmap/map.xml|BE',
            '铜仁'     => '980|600|1;2;3;4;|gfnjmpprmqNKNM;hmcsmpmmNONI;11;false;false;铜仁地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '毕节'     => '980|600|1;2;3;4;|gfjjnpqoqpJKJM;hmcolkknkNGNE;11;false;false;毕节地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '黔西南'   => '980|600|1;2;3;4;|JHMMYKMNQULLLH;KMGOXSHTOPDLH;11;false;false;黔西南州|http://www.mapabc.com/qnmap/map.xml|BE',
            '黔东南'   => '980|600|1;2;3;4;|gfljupjlhqJKNM;hlcqtkirmNKJM;11;false;false;黔东南苗族侗族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '黔南'     => '980|600|1;2;3;4;|gfljqijnqlJOFM;hlcntkiomNKFI;11;false;false;黔南布依族苗族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            //辽宁省
            '沈阳'     => '980|600|1;2;3;4;|fkqelkogooFOGI;ijlohmqnoJGGM;11;false;false;沈阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '大连'     => '980|600|1;2;3;4;|fkoenlnpkuNKOI;hqlpjiqjnJGOI;11;false;false;大连|http://www.mapabc.com/qnmap/map.xml|BE',
            '鞍山'     => '980|600|1;2;3;4;|fkqehhmjooNGOI;ijlhimrmNOGE;11;false;false;鞍山|http://www.mapabc.com/qnmap/map.xml|BE',
            '抚顺'     => '980|600|1;2;3;4;|fkqeqjojqoNKOI;ijlonornlFOKM;11;false;false;抚顺|http://www.mapabc.com/qnmap/map.xml|BE',
            '丹东'     => '980|600|1;2;3;4;|fkrekqolsNKOI;iilhjolpoJKOM;11;false;false;丹东|http://www.mapabc.com/qnmap/map.xml|BE',
            '锦州'     => '980|600|1;2;3;4;|fkoeilrmsvFKOI;ijlhjnkpmNGOI;11;false;false;锦州|http://www.mapabc.com/qnmap/map.xml|BE',
            '营口'     => '980|600|1;2;3;4;|fkpejjmmqtFGKI;iilmnhmnqNKKM;11;false;false;营口|http://www.mapabc.com/qnmap/map.xml|BE',
            '辽阳'     => '980|600|1;2;3;4;|fkqeiothnwNOOI;ijlioornsNOKM;11;false;false;辽阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '盘锦'     => '980|600|1;2;3;4;|fkpeholgpNKKM;ijlhipsgsFOOI;11;false;false;盘锦|http://www.mapabc.com/qnmap/map.xml|BE',
            '葫芦岛'   => '980|600|1;2;3;4;|fknepmqilpNOOI;iilnlplhlNOOM;11;false;false;葫芦岛|http://www.mapabc.com/qnmap/map.xml|BE',
            '本溪'     => '980|600|1;2;3;4;|fkqeookkrwNOKE;ijliqhqonFGOI;11;false;false;本溪|http://www.mapabc.com/qnmap/map.xml|BE',
            '阜新'     => '980|600|1;2;3;4;|ghfjrmltqtNGFM;jhclmllmmNKJE;11;false;false;阜新|http://www.mapabc.com/qnmap/map.xml|BE',
            '铁岭'     => '980|600|1;2;3;4;|ghhjtlkqlNONE;jhcntnotmJGNI;11;false;false;铁岭|http://www.mapabc.com/qnmap/map.xml|BE',
            '朝阳'     => '980|600|1;2;3;4;|ghejpmiontJKFE;jgcqsknnlNKFI;11;false;false;朝阳|http://www.mapabc.com/qnmap/map.xml|BE',
            //江苏省
            '南京'     => '980|600|1;2;3;4;|fjveoqtkrNKKI;hklgomroJKKI;11;false;false;南京|http://www.mapabc.com/qnmap/map.xml|BE',
            '苏州'     => '980|600|1;2;3;4;|fknenksmtNOKI;hjljjhpksNGGM;11;false;false;苏州|http://www.mapabc.com/qnmap/map.xml|BE',
            '无锡'     => '980|600|1;2;3;4;|fknekhllotJKKI;hjllpimFKOM;11;false;false;无锡|http://www.mapabc.com/qnmap/map.xml|BE',
            '徐州'     => '980|600|1;2;3;4;|fjueionppqJKOM;hmlimppntFOKI;11;false;false;徐州|http://www.mapabc.com/qnmap/map.xml|BE',
            '常州'     => '980|600|1;2;3;4;|fjweqoopktJOOE;hjlnnqrnJKKI;11;false;false;常州|http://www.mapabc.com/qnmap/map.xml|BE',
            '南通'     => '980|600|1;2;3;4;|fknepokknrNOOE;hklgiiqinFOOI;11;false;false;南通|http://www.mapabc.com/qnmap/map.xml|BE',
            '连云港'   => '980|600|1;2;3;4;|fjweiomonoNKOI;hmllqmspoFKKI;11;false;false;连云港|http://www.mapabc.com/qnmap/map.xml|BE',
            '淮安'     => '980|600|1;2;3;4;|ggnjlinpmlJONI;iicrmirsoNOJI;11;false;false;淮安|http://www.mapabc.com/qnmap/map.xml|BE',
            '盐城'     => '980|600|1;2;3;4;|fkneiktilpNOKM;hlljoqnoNOOM;11;false;false;盐城|http://www.mapabc.com/qnmap/map.xml|BE',
            '扬州'     => '980|600|1;2;3;4;|fjwelimoqsNOGI;hklkiqonoJGKM;11;false;false;扬州|http://www.mapabc.com/qnmap/map.xml|BE',
            '镇江'     => '980|600|1;2;3;4;|fjwellmkmwFOOM;hkliiikkpFKOI;11;false;false;镇江|http://www.mapabc.com/qnmap/map.xml|BE',
            '泰州'     => '980|600|1;2;3;4;|fjweqjkoouJOKM;hklkniqmmFOGI;11;false;false;泰州|http://www.mapabc.com/qnmap/map.xml|BE',
            '宿迁'     => '980|600|1;2;3;4;|ggmjnqormqJKFI;iicuqkqsjFKJM;11;false;false;宿迁|http://www.mapabc.com/qnmap/map.xml|BE',
            //福建省
            '福州'     => '980|600|1;2;3;4;|fjwekhqkkwFOKM;golgonlgoJKGM;11;false;false;福州|http://www.mapabc.com/qnmap/map.xml|BE',
            '厦门'     => '980|600|1;2;3;4;|fjveihlpopJOGM;gmlkoqmnpNOKE;11;false;false;厦门|http://www.mapabc.com/qnmap/map.xml|BE',
            '宁德'     => '980|600|1;2;3;4;|fjwemlsomtJOKI;golmmiqFOGM;11;false;false;宁德|http://www.mapabc.com/qnmap/map.xml|BE',
            '龙岩'     => '980|600|1;2;3;4;|ggljlkkqnlNONM;hkclulnsqNKNI;11;false;false;龙岩|http://www.mapabc.com/qnmap/map.xml|BE',
            '南平'     => '980|600|1;2;3;4;|ggmjmoqqqsNGJM;hlcroqquFONI;11;false;false;南平|http://www.mapabc.com/qnmap/map.xml|BE',
            '漳州'     => '980|600|1;2;3;4;|fjuennppsuNOKE;gmllijnlNKOI;11;false;false;漳州|http://www.mapabc.com/qnmap/map.xml|BE',
            '泉州'     => '980|600|1;2;3;4;|fjvempollsJGOM;gmlpjqpJOOI;11;false;false;泉州|http://www.mapabc.com/qnmap/map.xml|BE',
            '三明'     => '980|600|1;2;3;4;|ggljrlipFONM;hlcnrpiqnJONI;11;false;false;三明|http://www.mapabc.com/qnmap/map.xml|BE',
            '莆田'     => '980|600|1;2;3;4;|fjwehhpoopFKOI;gnlkkmqltNKKE;11;false;false;莆田|http://www.mapabc.com/qnmap/map.xml|BE',
            //河北省
            '石家庄'   => '980|600|1;2;3;4;|fjreloqktqJKGI;hqlglpmnFGKI;11;false;false;石家庄|http://www.mapabc.com/qnmap/map.xml|BE',
            '唐山'     => '980|600|1;2;3;4;|fjveinrjnJKGM;hrlmkmmoJGGI;11;false;false;唐山|http://www.mapabc.com/qnmap/map.xml|BE',
            '秦皇岛'   => '980|600|1;2;3;4;|fjwemptlqtJKGI;hrlplospoNKGE;11;false;false;秦皇岛|http://www.mapabc.com/qnmap/map.xml|BE',
            '邯郸'     => '980|600|1;2;3;4;|fjrelptiFKKM;hollqqoilNGOI;11;false;false;邯郸|http://www.mapabc.com/qnmap/map.xml|BE',
            '邢台'     => '980|600|1;2;3;4;|ggijqhmoiNONI;imclsiotmJKJI;11;false;false;邢台|http://www.mapabc.com/qnmap/map.xml|BE',
            '保定'     => '980|600|1;2;3;4;|fjselnqkssFKGI;hqlooqsmnJKKI;11;false;false;保定|http://www.mapabc.com/qnmap/map.xml|BE',
            '张家口'   => '980|600|1;2;3;4;|fjrepptopoFOKI;iilojnmhpJOKI;11;false;false;张家口|http://www.mapabc.com/qnmap/map.xml|BE',
            '承德'     => '980|600|1;2;3;4;|fjueqkolpsJKOM;iilpqmomqNKOM;11;false;false;承德|http://www.mapabc.com/qnmap/map.xml|BE',
            '沧州'     => '980|600|1;2;3;4;|ggkjtnolnqFOFM;incomjmpjJGJE;11;false;false;沧州|http://www.mapabc.com/qnmap/map.xml|BE',
            '廊坊'     => '980|600|1;2;3;4;|fjteohqjnrJGOI;hrllkhskoNOGM;11;false;false;廊坊|http://www.mapabc.com/qnmap/map.xml|BE',
            '衡水'     => '980|600|1;2;3;4;|ggjjrqqpqoFGJM;imcsomlrlNONI;11;false;false;衡水|http://www.mapabc.com/qnmap/map.xml|BE',
            //河南省
            '郑州'     => '980|600|1;2;3;4;|fjqennngoNKOI;hmlnnismtJGGM;11;false;false;郑州|http://www.mapabc.com/qnmap/map.xml|BE',
            '洛阳'     => '980|600|1;2;3;4;|fjpelhklmuNOOM;hmlmmnqolNOGM;11;false;false;洛阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '平顶山'   => '980|600|1;2;3;4;|gghjohlqmmNGNE;iicsnoluiNGNI;11;false;false;平顶山|http://www.mapabc.com/qnmap/map.xml|BE',
            '焦作'     => '980|600|1;2;3;4;|fjqejmtproNKGI;hnlihmrkpNOOI;11;false;false;焦作|http://www.mapabc.com/qnmap/map.xml|BE',
            '鹤壁'     => '980|600|1;2;3;4;|ggijnqplknNKJI;ikcsppjloNOFM;11;false;false;鹤壁|http://www.mapabc.com/qnmap/map.xml|BE',
            '新乡'     => '980|600|1;2;3;4;|fjqepptotNKKI;hnljhipktNOGE;11;false;false;新乡|http://www.mapabc.com/qnmap/map.xml|BE',
            '安阳'     => '980|600|1;2;3;4;|fjreklqgtuFGOI;holhiimgqJKOM;11;false;false;安阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '南阳'     => '980|600|1;2;3;4;|gggjqkmlktNKJI;ihcuuqkuFONM;11;false;false;南阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '漯河'     => '980|600|1;2;3;4;|ggijlknuqqJOFI;iicqroktNGNM;11;false;false;漯河|http://www.mapabc.com/qnmap/map.xml|BE',
            '济源'     => '980|600|1;2;3;4;|gggjqpqmisNOJE;ikcluhlnpJGNM;11;false;false;济源|http://www.mapabc.com/qnmap/map.xml|BE',
            '开封'     => '980|600|1;2;3;4;|fjreklkiluFOGM;hmlnpqnhJOOE;11;false;false;开封|http://www.mapabc.com/qnmap/map.xml|BE',
            '濮阳'     => '980|600|1;2;3;4;|ggjjljrnoqJKJI;ikcsrkkmmJONE;11;false;false;濮阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '许昌'     => '980|600|1;2;3;4;|fjqepkohktNKOM;hmlgjjkopNGOI;11;false;false;许昌|http://www.mapabc.com/qnmap/map.xml|BE',
            '三门峡'   => '980|600|1;2;3;4;|ggfjmqjqhsNKFI;ijcssmpskNGJI;11;false;false;三门峡|http://www.mapabc.com/qnmap/map.xml|BE',
            '商丘'     => '980|600|1;2;3;4;|ggjjrmjpmmJOJI;ijcpoprnqFOJI;11;false;false;商丘|http://www.mapabc.com/qnmap/map.xml|BE',
            '信阳'     => '980|600|1;2;3;4;|ggijlonnkqFKNI;ihcmnkrupJOJM;11;false;false;信阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '周口'     => '980|600|1;2;3;4;|ggijrmlllqNOFE;iicrmnntiJOJM;11;false;false;周口|http://www.mapabc.com/qnmap/map.xml|BE',
            '驻马店'   => '980|600|1;2;3;4;|ggijljqlisNOJI;ihcutkjsjNKJM;11;false;false;驻马店|http://www.mapabc.com/qnmap/map.xml|BE',
            //吉林省
            '长春'     => '980|600|1;2;3;4;|fksekjontqJGKE;illopnsmmJGKM;11;false;false;长春|http://www.mapabc.com/qnmap/map.xml|BE',
            '吉林'     => '980|600|1;2;3;4;|fktemoljorNGOE;illolmsjrNOGM;11;false;false;吉林|http://www.mapabc.com/qnmap/map.xml|BE',
            '四平'     => '980|600|1;2;3;4;|njngplqnvkkJIKO;qkhjsmnmtnJIKG;11;false;false;四平|http://www.mapabc.com/qnmap/map.xml|BE',
            '辽源'     => '980|600|1;2;3;4;|ghjjmlmqiqFOJM;jhculmqlmJGNI;11;false;false;辽源|http://www.mapabc.com/qnmap/map.xml|BE',
            '通化'     => '980|600|1;2;3;4;|ghjjulimnqFONI;jgcsnqjskFGNM;11;false;false;通化|http://www.mapabc.com/qnmap/map.xml|BE',
            '白山'     => '980|600|1;2;3;4;|ghkjpjltloNOFM;jgcuphjlqNKJI;11;false;false;白山|http://www.mapabc.com/qnmap/map.xml|BE',
            '松原'     => '980|600|1;2;3;4;|ghijtjltnoJONE;jkcmphqupFOJI;11;false;false;松原|http://www.mapabc.com/qnmap/map.xml|BE',
            '白城'     => '980|600|1;2;3;4;|ghgjtkqmmmNKFM;jkcrnhlrmJKJI;11;false;false;白城|http://www.mapabc.com/qnmap/map.xml|BE',
            '延边'     => '980|600|1;2;3;4;|JQULSVUPOLUDHJH;MQJVXQOOLHNHLJH;11;false;false;延边朝鲜族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            //黑龙江省
            '哈尔滨'   => '980|600|1;2;3;4;|fktenmmhsuNOGM;inlnnimnnFKGI;11;false;false;哈尔滨|http://www.mapabc.com/qnmap/map.xml|BE',
            '齐齐哈尔' => '980|600|1;2;3;4;|ghhjukpmnsJOJM;jmcopintoJKJM;11;false;false;齐齐哈尔|http://www.mapabc.com/qnmap/map.xml|BE',
            '鸡西'     => '980|600|1;2;3;4;|giejunrtmpJONM;jkcnumjrNGNE;11;false;false;鸡西|http://www.mapabc.com/qnmap/map.xml|BE',
            '鹤岗'     => '980|600|1;2;3;4;|giejnoqqlqFGJM;jmcooiiqnNKNI;11;false;false;鹤岗|http://www.mapabc.com/qnmap/map.xml|BE',
            '双鸭山'   => '980|600|1;2;3;4;|gifjmmrtqrJONM;jlcrpnrpqNKNI;11;false;false;双鸭山|http://www.mapabc.com/qnmap/map.xml|BE',
            '大庆'     => '980|600|1;2;3;4;|ghjjlqmnnpJOFI;jlcqtplnoNOJI;11;false;false;大庆|http://www.mapabc.com/qnmap/map.xml|BE',
            '伊春'     => '980|600|1;2;3;4;|ghmjuhjmlrNOFM;jmcsnlpoNKJI;11;false;false;伊春|http://www.mapabc.com/qnmap/map.xml|BE',
            '佳木斯'   => '980|600|1;2;3;4;|giejonrqpFKJM;jlctlomqpFOJM;11;false;false;佳木斯|http://www.mapabc.com/qnmap/map.xml|BE',
            '黑河'     => '980|600|1;2;3;4;|ghljqhiulmNKNM;kfcnpoqtqFKJI;11;false;false;黑河|http://www.mapabc.com/qnmap/map.xml|BE',
            '绥化'     => '980|600|1;2;3;4;|ghkjuqioptNOJI;jlcronrsmNOJI;11;false;false;绥化|http://www.mapabc.com/qnmap/map.xml|BE',
            '七台河'   => '980|600|1;2;3;4;|gifjlhptqnNOFE;jkcssirlqFGNE;11;false;false;七台河|http://www.mapabc.com/qnmap/map.xml|BE',
            '牡丹江'   => '980|600|1;2;3;4;|ghnjrjksktJOJI;jjcqtlpqnJGJM;11;false;false;牡丹江|http://www.mapabc.com/qnmap/map.xml|BE',
            '大兴安岭' => '980|600|1;2;3;4;|JJMMTKKPNSLLLH;NIGUWMNTKLLHL;11;false;false;大兴安岭地区|http://www.mapabc.com/qnmap/map.xml|BE',
            //山东省
            '济南'     => '980|600|1;2;3;4;|fjuehjkltvNOKE;holmmqnloFOGI;11;false;false;济南|http://www.mapabc.com/qnmap/map.xml|BE',
            '青岛'     => '980|600|1;2;3;4;|fknekkolnpJKOM;holgmmplnJOOM;11;false;false;青岛|http://www.mapabc.com/qnmap/map.xml|BE',
            '威海'     => '980|600|1;2;3;4;|fkpeimpomtFGGE;hpllhhqllNKKI;11;false;false;威海|http://www.mapabc.com/qnmap/map.xml|BE',
            '淄博'     => '980|600|1;2;3;4;|fjvehmkgrNOOE;holohnnjoFOKM;11;false;false;淄博|http://www.mapabc.com/qnmap/map.xml|BE',
            '枣庄'     => '980|600|1;2;3;4;|ggljohqrqrJGJI;ijctmjpnpFKJI;11;false;false;枣庄|http://www.mapabc.com/qnmap/map.xml|BE',
            '东营'     => '980|600|1;2;3;4;|fjvenonhtuJKGI;hplkipngoNKOI;11;false;false;东营|http://www.mapabc.com/qnmap/map.xml|BE',
            '烟台'     => '980|600|1;2;3;4;|fkoekpsmnsFOOE;hplljothNKKM;11;false;false;烟台|http://www.mapabc.com/qnmap/map.xml|BE',
            '潍坊'     => '980|600|1;2;3;4;|fjweiilhqrJGKM;holnijnjlNOGI;11;false;false;潍坊|http://www.mapabc.com/qnmap/map.xml|BE',
            '莱芜'     => '980|600|1;2;3;4;|ggljroqqhtJOJM;ilcnmknsnFKFI;11;false;false;莱芜|http://www.mapabc.com/qnmap/map.xml|BE',
            '滨州'     => '980|600|1;2;3;4;|ggmjlinultNOJM;imcotkklqJOJI;11;false;false;滨州|http://www.mapabc.com/qnmap/map.xml|BE',
            '济宁'     => '980|600|1;2;3;4;|ggkjqpprknNKNM;ikcpmkkqqNKNI;11;false;false;济宁|http://www.mapabc.com/qnmap/map.xml|BE',
            '泰安'     => '980|600|1;2;3;4;|fjueiklprwNGOM;holhqkqnnFKKE;11;false;false;泰安|http://www.mapabc.com/qnmap/map.xml|BE',
            '日照'     => '980|600|1;2;3;4;|ggnjqjoohsJKNM;ikcpmppqpNONE;11;false;false;日照|http://www.mapabc.com/qnmap/map.xml|BE',
            '临沂'     => '980|600|1;2;3;4;|ggmjolopjoJOFM;ikclqkptlNKFE;11;false;false;临沂|http://www.mapabc.com/qnmap/map.xml|BE',
            '德州'     => '980|600|1;2;3;4;|fjtekhpjluNKOM;hplkloqgmJGKM;11;false;false;德州|http://www.mapabc.com/qnmap/map.xml|BE',
            '聊城'     => '980|600|1;2;3;4;|ggjjupnqkrNOJI;ilcpqnirnNOJM;11;false;false;聊城|http://www.mapabc.com/qnmap/map.xml|BE',
            '菏泽'     => '980|600|1;2;3;4;|ggjjpniomtNGJE;ikcnpomtlJKJI;11;false;false;菏泽|http://www.mapabc.com/qnmap/map.xml|BE',
            //安徽省
            '合肥'     => '980|600|1;2;3;4;|fjuejqpnpsNOOM;hjlonqnkpJOKI;11;false;false;合肥|http://www.mapabc.com/qnmap/map.xml|BE',
            '芜湖'     => '980|600|1;2;3;4;|fjveknsosvNOKM;hjljliphoNGKM;11;false;false;芜湖|http://www.mapabc.com/qnmap/map.xml|BE',
            '蚌埠'     => '980|600|1;2;3;4;|fjuelhlnotNKKI;hklpkhmjqFOOM;11;false;false;蚌埠|http://www.mapabc.com/qnmap/map.xml|BE',
            '马鞍山'   => '980|600|1;2;3;4;|fjvemhmgnqJKGM;hjlmqjtprNKKI;11;false;false;马鞍山|http://www.mapabc.com/qnmap/map.xml|BE',
            '安庆'     => '980|600|1;2;3;4;|fjuehmkorsJOOM;hilljokglNKKM;11;false;false;安庆|http://www.mapabc.com/qnmap/map.xml|BE',
            '滁州'     => '980|600|1;2;3;4;|ggmjoiqmotJOJM;ihcoliisnNKNI;11;false;false;滁州|http://www.mapabc.com/qnmap/map.xml|BE',
            '阜阳'     => '980|600|1;2;3;4;|ggjjtirsosJGJM;ihctuojljJGJM;11;false;false;阜阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '宿州'     => '980|600|1;2;3;4;|ggkjuplslJKJI;iicropiqnFOFI;11;false;false;宿州|http://www.mapabc.com/qnmap/map.xml|BE',
            '巢湖'     => '980|600|1;2;3;4;|fjuepnnppqJKKM;hjlmhjrloJKGI;11;false;false;巢湖|http://www.mapabc.com/qnmap/map.xml|BE',
            '六安'     => '980|600|1;2;3;4;|ggkjpqjpnpNOFE;igcspjooiNGJE;11;false;false;六安|http://www.mapabc.com/qnmap/map.xml|BE',
            '淮南'     => '980|600|1;2;3;4;|ggkjuqrrpoNOFM;ihcropktjFGNI;11;false;false;淮南|http://www.mapabc.com/qnmap/map.xml|BE',
            '淮北'     => '980|600|1;2;3;4;|ggkjsqkqhrNOJM;iicurprtnJGJE;11;false;false;淮北|http://www.mapabc.com/qnmap/map.xml|BE',
            '铜陵'     => '980|600|1;2;3;4;|ggljtiithoNGJE;ifcuplnslNGNI;11;false;false;铜陵|http://www.mapabc.com/qnmap/map.xml|BE',
            '黄山'     => '980|600|1;2;3;4;|fjvekhpkmqNKKI;grlnijqgnNGOM;11;false;false;黄山|http://www.mapabc.com/qnmap/map.xml|BE',
            '亳州'     => '980|600|1;2;3;4;|ggjjspkqnqJGJI;iictrqnumFKJE;11;false;false;亳州|http://www.mapabc.com/qnmap/map.xml|BE',
            '池州'     => '980|600|1;2;3;4;|fjuelqngqtNOOI;hilmnknilNKOE;11;false;false;池州|http://www.mapabc.com/qnmap/map.xml|BE',
            '宣城'     => '980|600|1;2;3;4;|ggmjsmmsktJKNM;ifcupnqqiNKNM;11;false;false;宣城|http://www.mapabc.com/qnmap/map.xml|BE',
            //广西省
            '南宁'     => '980|600|1;2;3;4;|fivekirgsoJOOM;gklohnmmoJOKM;11;false;false;南宁|http://www.mapabc.com/qnmap/map.xml|BE',
            '桂林'     => '980|600|1;2;3;4;|fjnejpooquFOKI;gnlipjqnnFOKI;11;false;false;桂林|http://www.mapabc.com/qnmap/map.xml|BE',
            '柳州'     => '980|600|1;2;3;4;|fiwelimmkvJKOI;gmljhnrloJOKE;11;false;false;柳州|http://www.mapabc.com/qnmap/map.xml|BE',
            '梧州'     => '980|600|1;2;3;4;|ggfjohmpnoJOJI;hicqlknqoJKNM;11;false;false;梧州|http://www.mapabc.com/qnmap/map.xml|BE',
            '钦州'     => '980|600|1;2;3;4;|gfmjrhqsirJOJI;hgcuqhqqoJGJE;11;false;false;钦州|http://www.mapabc.com/qnmap/map.xml|BE',
            '贵港'     => '980|600|1;2;3;4;|gfnjrhomhqNOFM;hiclukqrnJKJE;11;false;false;贵港|http://www.mapabc.com/qnmap/map.xml|BE',
            '玉林'     => '980|600|1;2;3;4;|ggejmmisimNKJI;hhcrnmnnnFGNI;11;false;false;玉林|http://www.mapabc.com/qnmap/map.xml|BE',
            '百色'     => '980|600|1;2;3;4;|JHOMVLPTIULLLH;KKGXPLPVLLHH;11;false;false;百色|http://www.mapabc.com/qnmap/map.xml|BE',
            '河池'     => '980|600|1;2;3;4;|gfmjlnjtosJONE;hjcruokllJOFM;11;false;false;河池|http://www.mapabc.com/qnmap/map.xml|BE',
            '来宾'     => '980|600|1;2;3;4;|gfnjnjjsppJKJM;hicsomiqnNOFI;11;false;false;来宾|http://www.mapabc.com/qnmap/map.xml|BE',
            '北海'     => '980|600|1;2;3;4;|fiweijmprsNOOM;gjlkoprnpJOKM;11;false;false;北海|http://www.mapabc.com/qnmap/map.xml|BE',
            '防城港'   => '980|600|1;2;3;4;|fivekmkpmoFKGI;gjlmjkoisJKOM;11;false;false;防城港|http://www.mapabc.com/qnmap/map.xml|BE',
            '贺州'     => '980|600|1;2;3;4;|ggfjqnktmnNKJI;hjcpnormFKJE;11;false;false;贺州|http://www.mapabc.com/qnmap/map.xml|BE',
            '崇左'     => '980|600|1;2;3;4;|gfljokrpmpFKNI;hhcpnlqmqJGNI;11;false;false;崇左|http://www.mapabc.com/qnmap/map.xml|BE',
            //海南省
            '海口'     => '980|600|1;2;3;4;|fjnekjkhtvJKKM;gilgkjngnNKGM;11;false;false;海口|http://www.mapabc.com/qnmap/map.xml|BE',
            '三亚'     => '980|600|1;2;3;4;|fiweminmlsNKOI;fqlikmphrJOKI;11;false;false;三亚|http://www.mapabc.com/qnmap/map.xml|BE',
            //内蒙古
            '呼和浩特' => '980|600|1;2;3;4;|fjoennqmpJOKM;iilohpmmmNGOI;11;false;false;呼和浩特|http://www.mapabc.com/qnmap/map.xml|BE',
            '包头'     => '980|600|1;2;3;4;|fiwepolisrFOGE;iilmniqlFOOM;11;false;false;包头|http://www.mapabc.com/qnmap/map.xml|BE',
            '乌海'     => '980|600|1;2;3;4;|gfkjtiluqqJGJM;iocrrqjrlNGJI;11;false;false;乌海|http://www.mapabc.com/qnmap/map.xml|BE',
            '赤峰'     => '980|600|1;2;3;4;|ggmjumoromNONM;jhcnrolmJKNI;11;false;false;赤峰|http://www.mapabc.com/qnmap/map.xml|BE',
            '通辽'     => '980|600|1;2;3;4;|ghgjnnjlqlFGJI;jicrlmrmmFKNM;11;false;false;通辽|http://www.mapabc.com/qnmap/map.xml|BE',
            '鄂尔多斯' => '980|600|1;2;3;4;|ggejlhloioNONM;ioctnjlmmJKJM;11;false;false;鄂尔多斯|http://www.mapabc.com/qnmap/map.xml|BE',
            '呼伦贝尔' => '980|600|1;2;3;4;|ggnjsmntilNGNM;jocnpmpujJKNE;11;false;false;呼伦贝尔|http://www.mapabc.com/qnmap/map.xml|BE',
            '巴彦淖尔' => '980|600|1;2;3;4;|JHPMSSPQIQLHHL;MHGVVQQNQLLHH;11;false;false;巴彦淖尔盟|http://www.mapabc.com/qnmap/map.xml|BE',
            '乌兰察布' => '980|600|1;2;3;4;|gghjmjjlqoJOJE;jgcloiplpFOJE;11;false;false;乌兰察布|http://www.mapabc.com/qnmap/map.xml|BE',
            '兴安'     => '980|600|1;2;3;4;|ghgjmkmrqpNGJI;jlcltojspJKNM;11;false;false;兴安盟|http://www.mapabc.com/qnmap/map.xml|BE',
            '锡林郭勒' => '980|600|1;2;3;4;|JIOMPTIVMOLHHL;MKGXTQPSODHLL;11;false;false;锡林郭勒|http://www.mapabc.com/qnmap/map.xml|BE',
            '阿拉善'   => '980|600|1;2;3;4;|JHNMWKIOQLHHD;LPGWTPJSODDLD;11;false;false;阿拉善|http://www.mapabc.com/qnmap/map.xml|BE',
            //山西省
            '太原'     => '980|600|1;2;3;4;|fjpemklkspFKKM;hplommlimJOKM;11;false;false;太原|http://www.mapabc.com/qnmap/map.xml|BE',
            '大同'     => '980|600|1;2;3;4;|gghjnqrsoqFONI;jfclsplunNKJM;11;false;false;大同|http://www.mapabc.com/qnmap/map.xml|BE',
            '阳泉'     => '980|600|1;2;3;4;|gghjqpinkrFKJM;imctqnosnNGJM;11;false;false;阳泉|http://www.mapabc.com/qnmap/map.xml|BE',
            '长治'     => '980|600|1;2;3;4;|gghjmioqoNONI;ilcmujjqmFKFM;11;false;false;长治|http://www.mapabc.com/qnmap/map.xml|BE',
            '晋城'     => '980|600|1;2;3;4;|gggjtmkskpFKNI;ikcpuhnqmNOJI;11;false;false;晋城|http://www.mapabc.com/qnmap/map.xml|BE',
            '朔州'     => '980|600|1;2;3;4;|gggjpjromtJGNE;iocompnrmFKFI;11;false;false;朔州|http://www.mapabc.com/qnmap/map.xml|BE',
            '晋中'     => '980|600|1;2;3;4;|gggjsmkmqtNKNI;imcrtpllnFOJE;11;false;false;晋中|http://www.mapabc.com/qnmap/map.xml|BE',
            '运城'     => '980|600|1;2;3;4;|ggejuqprlJGFM;ikcloimtoJGNE;11;false;false;运城|http://www.mapabc.com/qnmap/map.xml|BE',
            '忻州'     => '980|600|1;2;3;4;|gggjskmrkmFONE;incpmmptmNONE;11;false;false;忻州|http://www.mapabc.com/qnmap/map.xml|BE',
            '临汾'     => '980|600|1;2;3;4;|ggfjqjotqJONM;ilcmljiuoJKJM;11;false;false;临汾|http://www.mapabc.com/qnmap/map.xml|BE',
            '吕梁'     => '980|600|1;2;3;4;|ggfjmkiloqJONM;imcqnhkunJKNM;11;false;false;吕梁|http://www.mapabc.com/qnmap/map.xml|BE',
            //宁夏
            '银川'     => '980|600|1;2;3;4;|gfkjnplnilNOFM;incprjpuFOFI;11;false;false;银川|http://www.mapabc.com/qnmap/map.xml|BE',
            '石嘴山'   => '980|600|1;2;3;4;|gfkjopoohoFONE;ioclmlnsiNONM;11;false;false;石嘴山|http://www.mapabc.com/qnmap/map.xml|BE',
            '吴忠'     => '980|600|1;2;3;4;|gfkjmqmpnrNOJI;imcutmjqnJGJM;11;false;false;吴忠|http://www.mapabc.com/qnmap/map.xml|BE',
            '固原'     => '980|600|1;2;3;4;|JHOMROJRMTLLHH;LNGOQQJVDDHH;11;false;false;固原|http://www.mapabc.com/qnmap/map.xml|BE',
            '中卫'     => '980|600|1;2;3;4;|gfjjrpolhlNKFE;imcptjqulFOFI;11;false;false;中卫|http://www.mapabc.com/qnmap/map.xml|BE',
            //甘肃省
            '兰州'     => '980|600|1;2;3;4;|gfhjtknmltJKNM;ilclqqqtmJKJI;11;false;false;兰州|http://www.mapabc.com/qnmap/map.xml|BE',
            '金昌'     => '980|600|1;2;3;4;|gfgjmonlpnNONI;incpsnlsmFONE;11;false;false;金昌|http://www.mapabc.com/qnmap/map.xml|BE',
            '白银'     => '980|600|1;2;3;4;|gfijmmirnmNKNE;ilcqqhoojNKJI;11;false;false;白银|http://www.mapabc.com/qnmap/map.xml|BE',
            '天水'     => '980|600|1;2;3;4;|gfjjshjrjoJGNI;ijcqtomrlFONE;11;false;false;天水|http://www.mapabc.com/qnmap/map.xml|BE',
            '武威'     => '980|600|1;2;3;4;|gfgjrklqmqNKNM;imcuoiospJKJI;11;false;false;武威|http://www.mapabc.com/qnmap/map.xml|BE',
            '张掖'     => '980|600|1;2;3;4;|gfejpmitnsJOFI;incuohrriNOJM;11;false;false;张掖|http://www.mapabc.com/qnmap/map.xml|BE',
            '平凉'     => '980|600|1;2;3;4;|gfkjsjnnktFKJE;ikcqlqpopJOJM;11;false;false;平凉|http://www.mapabc.com/qnmap/map.xml|BE',
            '酒泉'     => '980|600|1;2;3;4;|oncqmkqsoNGJM;iocsqnkukNGNM;11;false;false;酒泉|http://www.mapabc.com/qnmap/map.xml|BE',
            '庆阳'     => '980|600|1;2;3;4;|gfljtnjqhmNGNI;ikcuolmmnJKJI;11;false;false;庆阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '定西'     => '980|600|1;2;3;4;|gfijrjjtpoJKNI;ikcqtmjuqJKFE;11;false;false;定西|http://www.mapabc.com/qnmap/map.xml|BE',
            '陇南'     => '980|600|1;2;3;4;|gfijuklrmrJOJI;iicprqnpkNKJM;11;false;false;陇南|http://www.mapabc.com/qnmap/map.xml|BE',
            '临夏'     => '980|600|1;2;3;4;|JHLMRLNVRRDDHL;LMGTYKLSQHLHL;11;false;false;临夏|http://www.mapabc.com/qnmap/map.xml|BE',
            '甘南'     => '980|600|1;2;3;4;|JHKMYKOSONHHHH;LLGXXMOSLLHLD;11;false;false;甘南|http://www.mapabc.com/qnmap/map.xml|BE',
            '嘉峪关'   => '980|600|1;2;3;4;|RPGQWSLSQLLLD;LQGVXOMLLHD;11;false;false;嘉峪关|http://www.mapabc.com/qnmap/map.xml|BE',
            //陕西省
            '西安'     => '980|600|1;2;3;4;|fiveqmokkoJKOM;hmlinlthpJGKM;11;false;false;西安|http://www.mapabc.com/qnmap/map.xml|BE',
            '铜川'     => '980|600|1;2;3;4;|gfmjulprhmJKFM;ijculhmrNONI;11;false;false;铜川|http://www.mapabc.com/qnmap/map.xml|BE',
            '宝鸡'     => '980|600|1;2;3;4;|fiueiksospFGOM;hmljnqsglJOGM;11;false;false;宝鸡|http://www.mapabc.com/qnmap/map.xml|BE',
            '咸阳'     => '980|600|1;2;3;4;|fiveohtosNOKM;hmljjnropJKOI;11;false;false;咸阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '渭南'     => '980|600|1;2;3;4;|gfnjqiinooNKJI;ijcqloloqNOJI;11;false;false;渭南|http://www.mapabc.com/qnmap/map.xml|BE',
            '延安'     => '980|600|1;2;3;4;|fiwelqqlNKOM;hollqkniNOKM;11;false;false;延安|http://www.mapabc.com/qnmap/map.xml|BE',
            '汉中'     => '980|600|1;2;3;4;|gfljlkjolrJOFI;iiclsqqtqFKNI;11;false;false;汉中|http://www.mapabc.com/qnmap/map.xml|BE',
            '榆林'     => '980|600|1;2;3;4;|gfnjsmlrqsNKFM;incnumltmNGJM;11;false;false;榆林|http://www.mapabc.com/qnmap/map.xml|BE',
            '安康'     => '980|600|1;2;3;4;|gfnjlkjlpmJGFI;ihcruiotiJKNI;11;false;false;安康|http://www.mapabc.com/qnmap/map.xml|BE',
            '商洛'     => '980|600|1;2;3;4;|gfnjullmhsFGNM;iicttjmqkJGJM;11;false;false;商洛|http://www.mapabc.com/qnmap/map.xml|BE',
            //青海省
            '西宁'     => '980|600|1;2;3;4;|gffjsoorjtFOJE;ilcrmokoJKNI;11;false;false;西宁|http://www.mapabc.com/qnmap/map.xml|BE',
            '海东'     => '980|600|1;2;3;4;|gfgjlponnNONI;ilcqlqjtnJKJM;11;false;false;海东地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '海北'     => '980|600|1;2;3;4;|gfejuokuimJKNM;ilcupklmlJOFM;11;false;false;海北藏族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '黄南'     => '980|600|1;2;3;4;|JHJMXLNQPHDLL;LMGSVOHWPHHLL;11;false;false;黄南州|http://www.mapabc.com/qnmap/map.xml|BE',
            '海南'     => '980|600|1;2;3;4;|gfejrkpsjpNOFM;ilconlltkJONE;11;false;false;海南藏族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '果洛'     => '980|600|1;2;3;4;|JHIMROLWKNLHHL;LLGSWLOQNLPLL;11;false;false;果洛州|http://www.mapabc.com/qnmap/map.xml|BE',
            '玉树'     => '980|600|1;2;3;4;|ROGOPMOVHLHL;LKGOPRHWNLHDL;11;false;false;玉树州|http://www.mapabc.com/qnmap/map.xml|BE',
            '海西'     => '980|600|1;2;3;4;|97.37345695495605;37.3731584651113;11;false;false;海西蒙古族藏族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            //湖北省
            '武汉'     => '980|600|1;2;3;4;|fjrejotlqNOKM;hillmmkgrFOOM;11;false;false;武汉|http://www.mapabc.com/qnmap/map.xml|BE',
            '黄石'     => '980|600|1;2;3;4;|fjsehmnoluFGKI;hiliijnloNKOI;11;false;false;黄石|http://www.mapabc.com/qnmap/map.xml|BE',
            '襄樊'     => '980|600|1;2;3;4;|fjpeikpiqFGGI;hklgnjkhtNOOI;11;false;false;襄樊|http://www.mapabc.com/qnmap/map.xml|BE',
            '十堰'     => '980|600|1;2;3;4;|fjneopmgksNKKE;hklmmmngqNKKM;11;false;false;十堰|http://www.mapabc.com/qnmap/map.xml|BE',
            '荆州'     => '980|600|1;2;3;4;|fjpejlpjqqFKOI;hiljjmmilNOKM;11;false;false;荆州|http://www.mapabc.com/qnmap/map.xml|BE',
            '宜昌'     => '980|600|1;2;3;4;|fjoejqoopuNOGE;hilnhilnlFKOM;11;false;false;宜昌|http://www.mapabc.com/qnmap/map.xml|BE',
            '荆门'     => '980|600|1;2;3;4;|fjpeiqqhrvNOOI;hjlgjorklNKGE;11;false;false;荆门|http://www.mapabc.com/qnmap/map.xml|BE',
            '鄂州'     => '980|600|1;2;3;4;|fjreqhnmrwNKGM;hiljpqrppNKOI;11;false;false;鄂州|http://www.mapabc.com/qnmap/map.xml|BE',
            '仙桃'     => '980|600|1;2;3;4;|gghjpmlmmrJGNI;ifcorlnspNKFI;11;false;false;仙桃|http://www.mapabc.com/qnmap/map.xml|BE',
            '潜江'     => '980|600|1;2;3;4;|gggjtqmpppFKJI;ifcpmqqpkNKJI;11;false;false;潜江|http://www.mapabc.com/qnmap/map.xml|BE',
            '孝感'     => '980|600|1;2;3;4;|gghjuiqqkJKJE;ifcunloppNOFI;;11;false;false;孝感|http://www.mapabc.com/qnmap/map.xml|BE',
            '黄冈'     => '980|600|1;2;3;4;|ggijtonpmlNONM;ifcppnmqpJGFM;11;false;false;黄冈|http://www.mapabc.com/qnmap/map.xml|BE',
            '咸宁'     => '980|600|1;2;3;4;|ggijoknrmnFOJE;hoctokouqNKNM;11;false;false;咸宁|http://www.mapabc.com/qnmap/map.xml|BE',
            '随州'     => '980|600|1;2;3;4;|gghjoonpqrNKJM;igcsmnplqJGNI;11;false;false;随州|http://www.mapabc.com/qnmap/map.xml|BE',
            '恩施'     => '980|600|1;2;3;4;|JHRMTSKNNMLHHH;LHGQXLKNOHHPD;11;false;false;恩施|http://www.mapabc.com/qnmap/map.xml|BE',
            '天门'     => '980|600|1;2;3;4;|gghjmnluitNOJM;ifcrqiktnFOFI;11;false;false;天门|http://www.mapabc.com/qnmap/map.xml|BE',
            '神农架'   => '980|600|1;2;3;4;|JIIMTNOQJSDPHL;LIGTWSMQNHLHH;11;false;false;神农架|http://www.mapabc.com/qnmap/map.xml|BE',
            //湖南省
            '长沙'     => '980|600|1;2;3;4;|fjqehhtjouNOOM;gqlihpomsNKGM;11;false;false;长沙|http://www.mapabc.com/qnmap/map.xml|BE',
            '株洲'     => '980|600|1;2;3;4;|fjqeiltkotFOOI;gplolhqkoNOKE;11;false;false;株洲|http://www.mapabc.com/qnmap/map.xml|BE',
            '湘潭'     => '980|600|1;2;3;4;|fjpeqilkpoNKOM;gplompqkrJKKM;11;false;false;湘潭|http://www.mapabc.com/qnmap/map.xml|BE',
            '衡阳'     => '980|600|1;2;3;4;|fjpenimimuJOKI;goloqnmlJOGM;11;false;false;衡阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '邵阳'     => '980|600|1;2;3;4;|ggfjpnpnonNKNM;hmcnoojpkNONI;11;false;false;邵阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '岳阳'     => '980|600|1;2;3;4;|fjqeihshtuJGOM;grljnnnhqNKOM;11;false;false;岳阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '常德'     => '980|600|1;2;3;4;|fjoeohmmrpFOOM;grlgknqjqJKKM;11;false;false;常德|http://www.mapabc.com/qnmap/map.xml|BE',
            '郴州'     => '980|600|1;2;3;4;|gghjlkkmimFKJI;hkcsunqpjNONI;11;false;false;郴州|http://www.mapabc.com/qnmap/map.xml|BE',
            '永州'     => '980|600|1;2;3;4;|ggfjrhpuiqNKJM;hlcpopipoNONM;11;false;false;永州|http://www.mapabc.com/qnmap/map.xml|BE',
            '娄底'     => '980|600|1;2;3;4;|ggfjuqqnjpJONI;hmcsnmqqoJKJI;11;false;false;娄底|http://www.mapabc.com/qnmap/map.xml|BE',
            '张家界'   => '980|600|1;2;3;4;|fjneloqptFOKI;grlhiqqnrNOOE;11;false;false;张家界|http://www.mapabc.com/qnmap/map.xml|BE',
            '益阳'     => '980|600|1;2;3;4;|gggjomnqlmJOJM;hncqsqrsmJONM;11;false;false;益阳|http://www.mapabc.com/qnmap/map.xml|BE',
            '怀化'     => '980|600|1;2;3;4;|gfnjuooslsFONI;hmcqpplroNKJI;11;false;false;怀化|http://www.mapabc.com/qnmap/map.xml|BE',
            '湘西'     => '980|600|1;2;3;4;|QHPIOOIOMLNHOF;ROETMVKJSJHKN;11;false;false;湘西|http://www.mapabc.com/qnmap/map.xml|BE',
            //江西省
            '南昌'     => '980|600|1;2;3;4;|fjsepqrjqsJOKE;gqlmoprlrNOKM;11;false;false;南昌|http://www.mapabc.com/qnmap/map.xml|BE',
            '景德镇'   => '980|600|1;2;3;4;|fjuejiootvNOOE;grliqktoqJKOM;11;false;false;景德镇|http://www.mapabc.com/qnmap/map.xml|BE',
            '萍乡'     => '980|600|1;2;3;4;|gghjtmklloNKJI;hmcrnjpnnJOJE;11;false;false;萍乡|http://www.mapabc.com/qnmap/map.xml|BE',
            '九江'     => '980|600|1;2;3;4;|fjseqqkjtsFKOI;grlnimpmqNKGI;11;false;false;九江|http://www.mapabc.com/qnmap/map.xml|BE',
            '新余'     => '980|600|1;2;3;4;|ggijuiqmhtJKJI;hmctmnlmqJKFM;11;false;false;新余|http://www.mapabc.com/qnmap/map.xml|BE',
            '鹰潭'     => '980|600|1;2;3;4;|ggljlklshrFOFM;hncnoqmlpJKJE;11;false;false;鹰潭|http://www.mapabc.com/qnmap/map.xml|BE',
            '赣州'     => '980|600|1;2;3;4;|ggijulknntJKNM;hkctqmkriNGFM;11;false;false;赣州|http://www.mapabc.com/qnmap/map.xml|BE',
            '上饶'     => '980|600|1;2;3;4;|ggljunqtntFOJI;hncppmoonJKNI;11;false;false;上饶|http://www.mapabc.com/qnmap/map.xml|BE',
            '吉安'     => '980|600|1;2;3;4;|ggijuposhrFGNI;hmcmmkoopFKJI;11;false;false;吉安|http://www.mapabc.com/qnmap/map.xml|BE',
            '抚州'     => '980|600|1;2;3;4;|ggkjonksmpFOFI;hmcusqksoFKJE;11;false;false;抚州|http://www.mapabc.com/qnmap/map.xml|BE',
            '宜春'     => '980|600|1;2;3;4;|ggijoqqtkrNONM;hmctlnqlnNKFI;11;false;false;宜春|http://www.mapabc.com/qnmap/map.xml|BE',
            //云南省
            '昆明'     => '980|600|1;2;3;4;|fipeohnjJKGI;gnlglqspnJOGM;11;false;false;昆明|http://www.mapabc.com/qnmap/map.xml|BE',
            '玉溪'     => '980|600|1;2;3;4;|fipemkppmtFGKM;gmljlollqJOKM;11;false;false;玉溪|http://www.mapabc.com/qnmap/map.xml|BE',
            '保山'     => '980|600|1;2;3;4;|RQGPVMLSHHLL;KMGPQPQNRHHLH;11;false;false;保山|http://www.mapabc.com/qnmap/map.xml|BE',
            '昭通'     => '980|600|1;2;3;4;|gfhjshlsnlJKFI;hmcormnrnFONE;11;false;false;昭通|http://www.mapabc.com/qnmap/map.xml|BE',
            '红河'     => '980|600|1;2;3;4;|gfhjmpilkqNOFI;hicpohjoiFONM;11;false;false;红河哈尼族彝族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '西双版纳' => '980|600|1;2;3;4;|gfejsqrnmsNKJM;hhclmhqulJOFI;11;false;false;西双版纳傣族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '楚雄'     => '980|600|1;2;3;4;|gffjqlmsjmNKFM;hkclqlrnnFKNE;11;false;false;楚雄彝族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '大理'     => '980|600|1;2;3;4;|gfejnjkmjJONM;hkcqtlkojJONI;11;false;false;大理白族自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '德宏'     => '980|600|1;2;3;4;|RPGTXOJWNHHHH;KLGSSNPTLHHPH;11;false;false;德宏州|http://www.mapabc.com/qnmap/map.xml|BE',
            '曲靖'     => '980|600|1;2;3;4;|gfhjspmoimFKJM;hkcpuilpiNOJM;11;false;false;曲靖|http://www.mapabc.com/qnmap/map.xml|BE',
            '丽江'     => '980|600|1;2;3;4;|JHIMRNHOOHLLH;KNGWWQJPNHHHL;11;false;false;丽江|http://www.mapabc.com/qnmap/map.xml|BE',
            '普洱'     => '980|600|1;2;3;4;|JHIMYRHPRRLHDH;KJGVWRMQJHDLD;11;false;false;普洱|http://www.mapabc.com/qnmap/map.xml|BE',
            '临沧'     => '980|600|1;2;3;4;|JHIMPTIVQSDDHH;KKGWXTIQMDDDL;11;false;false;临沧|http://www.mapabc.com/qnmap/map.xml|BE',
            '文山'     => '980|600|1;2;3;4;|JHMMROJSPOLPDL;KKGRVTMPDDHD;11;false;false;文山|http://www.mapabc.com/qnmap/map.xml|BE',
            '怒江'     => '980|600|1;2;3;4;|RPGWUQNOJHLLH;KMGWTRMWRLHLH;11;false;false;怒江|http://www.mapabc.com/qnmap/map.xml|BE',
            '迪庆'     => '980|600|1;2;3;4;|RQGVPQOTMHLLL;KOGWRRPRLLDLL;11;false;false;迪庆|http://www.mapabc.com/qnmap/map.xml|BE',
            //新疆
            '乌鲁木齐' => '980|600|1;2;3;4;|nmcrmnqFOFE;jictnkmsmJONE;11;false;false;乌鲁木齐|http://www.mapabc.com/qnmap/map.xml|BE',
            '克拉玛依' => '980|600|1;2;3;4;|njcuoijqjJONM;jkcqunlqpFKNM;11;false;false;克拉玛依|http://www.mapabc.com/qnmap/map.xml|BE',
            '吐鲁番'   => '980|600|1;2;3;4;|nocnmhouiJKJM;jicllnrrkJOFI;11;false;false;吐鲁番地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '哈密'     => '980|600|1;2;3;4;|RKGTQOKNQLHDH;MJGWRQKNOLLLH;11;false;false;哈密地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '和田'     => '980|600|1;2;3;4;|PQGXQLIWMHHHH;LOGPQLKQDDLH;11;false;false;和田地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '阿克苏'   => '980|600|1;2;3;4;|QHGQVMKONLHHH;MIGPWKJWOHLLH;11;false;false;阿克苏地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '喀什'     => '980|600|1;2;3;4;|PMGXYOMTQHHLD;LQGSVKPTLHLHL;11;false;false;喀什地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '伊犁'     => '980|600|1;2;3;4;|ngcooplnqJGFI;jicunkkqkJKFI;11;false;false;伊犁哈萨克自治州|http://www.mapabc.com/qnmap/map.xml|BE',
            '石河子'   => '980|600|1;2;3;4;|nlclqjopJOFM;jjcolmiqkJOJE;11;false;false;石河子|http://www.mapabc.com/qnmap/map.xml|BE',
            '巴音郭楞' => '980|600|1;2;3;4;|QNGPTPQNRLHHD;MIGVVOJWLLHL;11;false;false;巴音郭楞州|http://www.mapabc.com/qnmap/map.xml|BE',
            '克孜勒苏' => '980|600|1;2;3;4;|PNGQRLLPQLLHH;LQGVQTKQNHLLL;11;false;false;克孜勒苏|http://www.mapabc.com/qnmap/map.xml|BE',
            '昌吉'     => '980|600|1;2;3;4;|QOGQYQKQLDDLD;MLGOQLPQLLDD;11;false;false;昌吉|http://www.mapabc.com/qnmap/map.xml|BE',
            '博尔塔拉' => '980|600|1;2;3;4;|QJGOVSHRLDLHL;MLGWYOKNRLHLL;11;false;false;博尔塔拉|http://www.mapabc.com/qnmap/map.xml|BE',
            '塔城'     => '980|600|1;2;3;4;|QJGXXQNNLDHHD;MNGVTNISMLHDH;11;false;false;塔城|http://www.mapabc.com/qnmap/map.xml|BE',
            '阿勒泰'   => '980|600|1;2;3;4;|QPGPSQJUKHLPD;MOGWUOQVMDDDD;11;false;false;阿勒泰|http://www.mapabc.com/qnmap/map.xml|BE',
            //西藏
            '拉萨'     => '980|600|1;2;3;4;|ogcmmproqJONI;hocrqmksoNOFM;11;false;false;拉萨|http://www.mapabc.com/qnmap/map.xml|BE',    		
            '昌都'     => '980|600|1;2;3;4;|ROGPUKOQJLHLH;LIGPUNIUPLDLH;11;false;false;昌都地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '山南'     => '980|600|1;2;3;4;|ogctllrqlNGJI;hocnphosiNGNI;11;false;false;山南地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '日喀则'   => '980|600|1;2;3;4;|nncttpltNKNE;hocnsipnJKJM;11;false;false;日喀则地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '那曲'     => '980|600|1;2;3;4;|RJGOUTINRLLLH;LIGSWOITMHHHD;11;false;false;那曲地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '阿里'     => '980|600|1;2;3;4;|QHGPPPMVQDHHH;LJGTPMNTHLHD;11;false;false;阿里地区|http://www.mapabc.com/qnmap/map.xml|BE',
            '林芝'     => '980|600|1;2;3;4;|RLGRVKIWRLDDH;KQGUTSNUNHLHL;11;false;false;林芝地区|http://www.mapabc.com/qnmap/map.xml|BE',
            //香港
            '香港'     => '980|600|1;2;3;4;|JIMMQPPTOOHLHH;KJGQWTLNQHLLL;11;false;false;香港特别行政区|http://www.mapabc.com/qnmap/map.xml|BE',
            //澳门
            '澳门'     => '980|600|1;2;3;4;|JILMUNOWLQHHHD;KJGPYLHTJHHLD;11;false;false;澳门特别行政区|http://www.mapabc.com/qnmap/map.xml|BE',
        );
        /**
         * Default Coordinate
         *
         * @staticvar array
         */
        public static $DEFAULT_COORDINATE = array(
            'HJSEOVQWVIPODHO','KIKGQQOOVLOLHK'
        );
        /**
         * the Options of the CURL Object
         *
         * @staticvar array
         */
        public static $CURL_OPTIONS    = array(
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPGET        => true,
            CURLOPT_RETURNTRANSFER => true,
        );
        /**
         * Default Value of the Chars to Replace
         *
         * @staticvar array
         */
        public static $DEFAULT_REPLACE = array(
            ';', '|'
        );
        /**
         * Default Value of the Address
         *
         * @staticvar string
         */
        const DEFAULT_ADDRESS          = '';
        /**
         * Default Value of the City Name
         * 
         * @staticvar string
         */
        const DEFAULT_CITY_NAME        = '北京';
        /**
         * Default Value of the City Pinyin
         * 
         * @staticvar string
         */
        const DEFAULT_CITY_PINYIN      = 'bj';
        /**
         * Default Value of the Template File of the Ditu Page
         *
         * @staticvar string
         */
        const DEFAULT_DETAIL_TEMPLATE  = '/templates/map/map.htm';
        /**
         * Default Value of the Split Char of the Line
         *
         * @staticvar string
         */
        const DEFAULT_LINE_SPLIT       = ',';
        /**
         * Default Value of the Template File of the DituMark Page
         *
         * @staticvar string
         */
        const DEFAULT_MARK_TEMPLATE    = '/templates/map/mark.htm';
        /**
         * Default Value of the Suggest Number
         *
         * @staticvar integer
         */
        const DEFAULT_SUGGEST_NUM      = 10;
        /**
         * Default Value of the Traffic Iframe
         *
         * @staticvar string
         */
        const DEFAULT_TRAFFIC_IFRAME   = 'traffic_iframe';
        /**
         * Default Value of the Template File of the DituTraffic Page
         *
         * @staticvar string
         */
        const DEFAULT_TRAFFIC_TEMPLATE = '/templates/map/traffic.htm';
        /**
         * Default Value of the Input Id or Name of the Parent Page
         * 
         * @staticvar string
         */
        const DEFAULT_PARENT_INPUT     = 'latlng';
        /**
         * Default Value of the Width of the Left Div
         *
         * @staticvar integer
         */
        const DEFAULT_LEFT_WIDTH       = 300;
        /**
         * Default Value of the Height of the Left Div
         *
         * @staticvar integer
         */
        const DEFAULT_LEFT_HEIGHT      = 350;
        /**
         * Default Value of the Width of the Pop Window
         * 
         * @staticvar integer
         */
        const DEFAULT_POP_WIDTH        = 600;
        /**
         * Default Value of the Height of the Pop Window
         *
         * @staticvar integer
         */
        const DEFAULT_POP_HEIGHT       = 500;
        /**
         * Default Value of the Round of the Center
         * 
         * @staticvar integer
         */
        const DEFAULT_RANGE            = 1000;
        /**
         * Default Value of the Width of the Right Div
         *
         * @staticvar integer
         */
        const DEFAULT_RIGHT_WIDTH      = 435;
        /**
         * Default Value of the Height of the Right Div
         * 
         * @staticvar integer
         */
        const DEFAULT_RIGHT_HEIGHT     = 350;
        /**
         * Default Value of the Search Url
         *
         * @staticvar string
         */
        const DEFAULT_SEARCH_URL       = 'http://search1.mapabc.com/sisserver?&config=BESN&searchName=%s&number=10&a_k=4726f4e7aa32db60204e6c05d7e41ada440055e47021072e45322d670324223eedbbd89f7b0788d2';
        /**
         * Default Value of the Search Bus Url
         *
         * @staticvar string
         */
        const DEFAULT_SEARCH_BUS_URL   = 'http://search1.mapabc.com/sisserver?&config=BELSBXY&cityCode=%s&cenX=%s&cenY=%s&srctype=BUS&number=100&range=%d&a_k=4726f4e7aa32db60204e6c05d7e41ada440055e47021072e45322d670324223eedbbd89f7b0788d2';
        /**
         * Default Value of the Step Speed of a Person
         *
         * @staticvar integer
         */
        const DEFAULT_STEP_SPEED       = 80;
        /**
         * Default Value of the Zoom Level of the Ditu
         *
         * @staticvar integer
         */
        const DEFAULT_ZOOM_LEVEL       = 4;
        /**
         * earth radii var
         * 
         * @staticvar integer
         */
        const EARTH_RADII              = 6378137;
        const LOG_FLUSH                = true;
        const LOG_LEVEL                = 8;
        const LOG_NAME                 = 'ditu_traffic_page';
        const LOG_PATH                 = '/tmp/ditu_traffic_page/';
        const OTHER_INFO_PREFIX        = 'DITU_OTHER_INFO_';
        const OTHER_INFO_EXPIRE        = 86400;
        const OTHER_INFO_COUNT         = 8;
        const TRAFFIC_EXPIRE           = 86400;
        const TRAFFIC_PREFIX           = 'DITU_TRAFFIC_';
        const TRAFFIC_BUS_URL          = '/bus/line/%s.html';
    }
