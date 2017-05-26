<?php
/**
 * �Զ�ά���������(��Ҫ���ڶ�Mysql�����õ�������)
 * @package              ganji
 * @author               yangyu
 * @file                 $RCSfile: EyouArraySort.function.php,v $
 * @version              $Revision: 1.2 $
 * @modifiedby           $Author: yangyu $
 * @lastmodified         $Date: 2007/11/27 08:01:37 $
 * @copyright            Copyright (c) 2010, ganji.com
 */

class MysqlSort{
    /** {{{ ��mysql�������ĳ��field���п������� sort($mysql,$field)
     * @param  array   $mysql  ���޸����ֵ
     * @param  field   $mysql  ����Ǹ��ֶν�������
     * @return null
     */
    public static function sort(&$mysql,$field,$left = null,$right = null){
        if(count($mysql) <= 1)
            return true;
        if(null == $left)
            $left   = 0;
        if(null == $right)
            $right  = count($mysql)-1;

        if($left + 15 <= $right){//���鳤��С��15���ò� ������ʵ��
            //1:ѡ�����ĵ�
            $pivot  = self::MysqlMedian3(&$mysql,$field,$left,$right);
            $i      = $left;
            $j      = $right - 1;//Ŀǰ���ĵ��λ��
            for( ; ; ){
                //2:��I��ʼ�������������ǰ��ʼ���������I��=I+1�����ҵ���һ������pivot�����߽�����
                //ֻҪֵС�����ĵ��ֵ�����ƣ�ͣ��ʱi��λ�þ��ǵ�һ������pivot��λ��
                while($mysql[++$i][$field] < $pivot){}
                //ֻҪֵ�������ĵ��ֵ�����ƣ�ͣ��ʱj��λ�þ��ǵ�һ��С��pivot��λ��
                while($mysql[--$j][$field] > $pivot){}
                if($i < $j)
                    self::swap(&$mysql[$i],&$mysql[$j]);
                else
                    break;
            }
            self::swap(&$mysql[$i],&$mysql[$right - 1]); //���´洢��ֵ��
            self::sort(&$mysql, $field,$left, $i-1);
            self::sort(&$mysql, $field,$i+1, $right);
        }
        else
            self::insertionSort(&$mysql,$field,$left,$right);
    }//}}}
    /** {{{ (desc)��mysql�������ĳ��field���п������� sortDesc($mysql,$field)
     * @param  array   $mysql
     * @return array
     */
    public static function sortDesc(&$mysql,$field,$left = null,$right = null){
        if(count($mysql) <= 1)
            return true;
        if(null == $left)
            $left   = 0;
        if(null == $right)
            $right  = count($mysql)-1;

        if($left + 10 <= $right){//���鳤��С��10���ò� ������ʵ��
            //1:ѡ�����ĵ�
            $pivot  = self::MysqlMedian3Desc(&$mysql,$field,$left,$right);
            $i      = $left;
            $j      = $right - 1;//Ŀǰ���ĵ��λ��
            for( ; ; ){
                //2:��I��ʼ�������������ǰ��ʼ���������I��=I+1�����ҵ���һ������pivot�����߽�����
                //ֻҪֵС�����ĵ��ֵ�����ƣ�ͣ��ʱi��λ�þ��ǵ�һ������pivot��λ��
                while($mysql[++$i][$field] > $pivot){}
                //ֻҪֵ�������ĵ��ֵ�����ƣ�ͣ��ʱj��λ�þ��ǵ�һ��С��pivot��λ��
                while($mysql[--$j][$field] < $pivot){}
                if($i < $j)
                    self::swap(&$mysql[$i],&$mysql[$j]);
                else
                    break;
            }
            self::swap(&$mysql[$i],&$mysql[$right - 1]); //���´洢��ֵ��
            self::sortDesc(&$mysql, $field,$left, $i-1);
            self::sortDesc(&$mysql, $field,$i+1, $right);
        }
        else
            self::insertionSortDesc(&$mysql,$field,$left,$right);
    }//}}}

    /** {{{ ����2����ֵ swap($v1,$v2)
     * @param  $v1               Ҫ�����ĵ�һ��ֵ
     * @param  $v2               Ҫ��v1������ֵ
     */
    private static function swap(&$v1,&$v2){
        $tmp    = $v1;
        $v1     = $v2;
        $v2     = $tmp;
    }//}}}
    /** {{{ �ÿ��������㷨����ֵ Median3($mysql,$field)
     * @param  array   $mysql       Ҫ�����mysql����
     * @param  string  $field        Ҫ����field������mysql������
     * @return mysql����ֵ
     */
    private static function MysqlMedian3(&$mysql,$field,$left = null, $right = null){
        if(null == $left)
            $left   = 0;
        if(null == $right)
            $right  = count($mysql)-1;

        $center = ceil(($left+$right)/2);

        if($mysql[$left][$field] > $mysql[$center][$field]){//�����ߵ�ֵ������ֵ
            self::swap(&$mysql[$left],&$mysql[$center]);
        }
        if($mysql[$left][$field] > $mysql[$right][$field])
            self::swap(&$mysql[$left],&$mysql[$right]);
        if($mysql[$center][$field] > $mysql[$right][$field])
            self::swap(&$mysql[$center],&$mysql[$right]);

        //����Ӧ����    $mysql[left] <=  $mysql[center]   <=  $mysql[right]

        self::swap(&$mysql[$center],&$mysql[$right-1]);
        return $mysql[$right - 1][$field];
    }//}}}
    /** {{{ ��mysql������ò����������� insertionSort($mysql,$field,$left,$reigh)
     */
    private static function insertionSort(&$mysql,$field,$left = null,$right=null){
        if(null == $left)
            $left   = 1;
        if(null == $right)
            $right  = count($mysql);
        for($p=$left;$p<=$right;$p++){
            $tmp = $mysql[$p];
            for($j=$p; $j>0 && $mysql[$j-1][$field]>$tmp[$field]; $j--)
                $mysql[$j] = $mysql[$j - 1];
            $mysql[$j] = $tmp;
        }
    }//}}}
    /** {{{ (desc)�ÿ��������㷨����ֵ Median3Desc($mysql,$field)
     * @param  array   $mysql       Ҫ�����mysql����
     * @param  string  $field        Ҫ����field������mysql������
     * @return mysql����ֵ
     */
    private static function MysqlMedian3Desc(&$mysql,$field,$left = null, $right = null){
        if(null == $left)
            $left   = 0;
        if(null == $right)
            $right  = count($mysql)-1;

        $center = ceil(($left+$right)/2);

        if($mysql[$left][$field] < $mysql[$center][$field]){//�����ߵ�ֵ������ֵ
            self::swap(&$mysql[$left],&$mysql[$center]);
        }
        if($mysql[$left][$field] < $mysql[$right][$field])
            self::swap(&$mysql[$left],&$mysql[$right]);
        if($mysql[$center][$field] > $mysql[$right][$field])
            self::swap(&$mysql[$center],&$mysql[$right]);

        //����Ӧ����    $mysql[left] <=  $mysql[center]   <=  $mysql[right]

        self::swap(&$mysql[$center],&$mysql[$right-1]);
        return $mysql[$right - 1][$field];
    }//}}}
    /** {{{ (desc)��mysql������ò����������� insertionSort($mysql,$field,$left,$reigh)
     */
    private static function insertionSortDesc(&$mysql,$field,$left = null,$right=null){
        if(null == $left)
            $left   = 1;
        if(null == $right)
            $right  = count($mysql);
        for($p=$left;$p<=$right;$p++){
            $tmp = $mysql[$p];
            for($j=$p; $j>0 && $mysql[$j-1][$field]<$tmp[$field]; $j--)
                $mysql[$j] = $mysql[$j - 1];
            $mysql[$j] = $tmp;
        }
    }//}}}
}
