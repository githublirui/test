<?php

class Utils {

    /**
     * Fetches an alternate IP address of the current visitor, attempting to detect proxies etc.
     *
     * @return	string
     */
    public static function fetch_alt_ip() {
        $alt_ip = $_SERVER['REMOTE_ADDR'];

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $alt_ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            // try to avoid using an internal IP address, its probably a proxy
            $ranges = array(
                '10.0.0.0/8' => array(ip2long('10.0.0.0'), ip2long('10.255.255.255')),
                '127.0.0.0/8' => array(ip2long('127.0.0.0'), ip2long('127.255.255.255')),
                '169.254.0.0/16' => array(ip2long('169.254.0.0'), ip2long('169.254.255.255')),
                '172.16.0.0/12' => array(ip2long('172.16.0.0'), ip2long('172.31.255.255')),
                '192.168.0.0/16' => array(ip2long('192.168.0.0'), ip2long('192.168.255.255')),
            );
            foreach ($matches[0] AS $ip) {
                $ip_long = ip2long($ip);
                if ($ip_long === false) {
                    continue;
                }

                $private_ip = false;
                foreach ($ranges AS $range) {
                    if ($ip_long >= $range[0] AND $ip_long <= $range[1]) {
                        $private_ip = true;
                        break;
                    }
                }

                if (!$private_ip) {
                    $alt_ip = $ip;
                    break;
                }
            }
        } else if (isset($_SERVER['HTTP_FROM'])) {
            $alt_ip = $_SERVER['HTTP_FROM'];
        }

        return $alt_ip;
    }

    public static function tableToPropelFomat($f) {
        $parts = explode('_', $f);
        foreach ($parts as &$part) {
            $part = ucfirst(strtolower(trim($part)));
        }
        return implode('', $parts);
    }

    public static function fieldToPropelFomat($f) {
        $parts = explode('_', $f);
        foreach ($parts as &$part) {
            $part = ucfirst(strtolower(trim($part)));
        }
        return implode('', $parts);
    }


    /**
     * to CSV
     *
     * @param string $file (full path)
     * @param array $data (2-dimensional array)
     */
    public static function toCsv($file, $data) {
        $fp = fopen($file, 'w');

        foreach ($data as $line) {
            fputcsv($fp, $line);
        }

        fclose($fp);

        $csv_data = file_get_contents($file);
        $csv_data = str_replace("\n", "\r\n", $csv_data);
        file_put_contents($file, $csv_data);
    }

    /**
     * to TSV
     *
     * @param string $file (full path)
     * @param array $data (2-dimensional array)
     */
    public static function toTsv($file, $data) {
        $fp = fopen($file, 'w');

        foreach ($data as $line) {
            fputcsv($fp, $line, "\t");
        }

        fclose($fp);

        $csv_data = file_get_contents($file);
        $csv_data = str_replace("\n", "\r\n", $csv_data);
        file_put_contents($file, $csv_data);
    }
    
    public static function escapeCSVValue($value) {
        $value = str_replace('"', '""', $value); // First off escape all " and make them ""
        if (eregi(",", $value) or eregi("\n", $value)) { // Check if I have any commas or new lines
            return '"' . $value . '"'; // If I have new lines or commas escape them
        } else {
            return $value; // If no new lines or commas just return the value
        }
    }

    /**
     *
     * @param string   $datetime   '2008-05-14 00:00:00'
     * @return string  '16/05/2008'
     */
    public static function timeMysqlDatetimeTo3clickDate($datetime, $format='d/m/Y') {
        return date($format, strtotime($datetime));
    }

    public static function showMsg($msg) {
        if (isset($msg) && !empty($msg)) {
            echo '
                <div id="msg_area" class="msg">
                    <div style="width:1px; display:table; white-space:nowrap; margin:0 auto;">
                        <b class="msg_notice">
                        <b class="msg_notice1"><b></b></b>
                        <b class="msg_notice2"><b></b></b>
                        <b class="msg_notice3"></b>
                        <b class="msg_notice4"></b>
                        <b class="msg_notice5"></b></b>
                        
                        <div class="msg_noticefg" style="padding:5px 15px;">
                            <img style="margin-bottom: -4px;" src="/images/common/legacy/icon/info.png"/> ' . $msg . '
                        </div>
                        
                        <b class="msg_notice">
                        <b class="msg_notice5"></b>
                        <b class="msg_notice4"></b>
                        <b class="msg_notice3"></b>
                        <b class="msg_notice2"><b></b></b>
                        <b class="msg_notice1"><b></b></b></b>
                    </div>
                </div>
        ';
        }
    }

    public static function showErrorMsg($msg) {
        if (isset($msg) && !empty($msg)) {
            echo '
                <div id="msg_area" class="msg">
                    <div style="width:1px; display:table; white-space:nowrap; margin:0 auto;">
                        <b class="msg_error">
                        <b class="msg_error1"><b></b></b>
                        <b class="msg_error2"><b></b></b>
                        <b class="msg_error3"></b>
                        <b class="msg_error4"></b>
                        <b class="msg_error5"></b></b>
                        
                        <div class="msg_errorfg" style="padding:5px 15px;color:#DD0000;">
                            <img style="margin-bottom: -4px;" src="/images/3rd/famicons/exclamation.png"/> ' . $msg . '
                        </div>
                        
                        <b class="msg_error">
                        <b class="msg_error5"></b>
                        <b class="msg_error4"></b>
                        <b class="msg_error3"></b>
                        <b class="msg_error2"><b></b></b>
                        <b class="msg_error1"><b></b></b></b>
                    </div>
                </div>
                 ';
        }
    }

    /**
     * Reject counter
     *
     */
    public static function getRejectedNum($order_id, $tab, $field, $component_id='') {
        $db = TcDb::connectdb();
        $number = 0;
        $sql = "select count(*) from `comments` where Order_id = $order_id and Tab = '$tab' and CommentType = 1 ";
        if ($component_id) {
            $sql .= " and Component_id = $component_id";
        }
        $result = mysql_query($sql, $db);
        while ($row = mysql_fetch_array($result)) {
            $number = $row[0];
        }
        return $number;
    }

    public static function getSizeScaleRangeName($SizeScaleRange_id) {
        $list = Utils::getSizeScaleRangeMap();
        return $list[$SizeScaleRange_id];
    }

    public static function getSizeScaleRangeMap() {
        $con = Propel::getConnection();
        $sql = "Select SizeScaleRangeName,SizeScaleRange_id from SizeScaleRange order by SizeScaleRangeName\n";

        $stmt = $con->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $list[$row[1]] = $row[0];
        }

        return $list;
    }

    public static function rebuildUrl($url) {
        $url_p = explode('/', $url);
        array_shift($url_p); #throw junk
        $module = array_shift($url_p);
        $action = array_shift($url_p);
        $tmp = array();
        $j = count($url_p) / 2;
        for ($i = 0; $i < $j; $i++) {
            $k = array_shift($url_p);
            $v = array_shift($url_p);
            $tmp[$k] = $v;
        }
        $url = "/$module/$action";
        foreach ($tmp as $k => $v) {
            $url .= "/$k/$v";
        }
        return $url;
    }

    public static function rebuildUrlForFullUrl($url) {
        $url_p = explode('/', $url);
        array_shift($url_p); #throw junk
        $prefix = array_shift($url_p);
        $module = array_shift($url_p);
        $action = array_shift($url_p);
        $tmp = array();
        $j = count($url_p) / 2;
        for ($i = 0; $i < $j; $i++) {
            $k = array_shift($url_p);
            $v = array_shift($url_p);
            $tmp[$k] = $v;
        }
        $url = "/$prefix/$module/$action";
        foreach ($tmp as $k => $v) {
            $url .= "/$k/$v";
        }
        return $url;
    }

    public static function rebuildUrlForAdmin($url) {
        $url_p = explode('/', $url);
        array_shift($url_p); #throw junk
        $prefix = array_shift($url_p);
        $module = array_shift($url_p);
        $action = array_shift($url_p);
        $tmp = array();
        $j = count($url_p) / 2;
        for ($i = 0; $i < $j; $i++) {
            $k = array_shift($url_p);
            $v = array_shift($url_p);
            $tmp[$k] = $v;
        }
        $url = "/$prefix/$module/$action";
        foreach ($tmp as $k => $v) {
            $url .= "/$k/$v";
        }
        return $url;
    }

    /**
     * used in _sort_title.php partial
     * @author radu
     */
    public static function rebuildUrlForSort($url, $sortby, $sortorder) {
        $url = explode('/', $url);
        $foundSo = false;
        $foundSb = false;

        foreach ($url as $k => $v) {
            if ($v == 'sortorder') {
                $foundSo = true;
                $url[$k + 1] = $sortorder;
            }
            if ($v == 'sortby') {
                $foundSb = true;
                $url[$k + 1] = $sortby;
            }
            if ($v == 'page') {
                unset($url[$k]);
                unset($url[$k + 1]);
            }
        }

        $url = implode('/', $url);

        if (!$foundSb) {
            $url.='/sortby/' . $sortby;
        }

        if (!$foundSo) {
            $url.='/sortorder/' . $sortorder;
        }

        return $url;
    }

    public static $currencylist = array();
    public static $currencysymbollist = array();
    public static $currencynumber = 0;

    public static function setCurrencyList() {
        if (count(self::$currencylist) == 0) {
            self::$currencylist[0] = 'AUD';
            self::$currencylist[1] = 'HKD';
            self::$currencylist[2] = 'USD';
            self::$currencysymbollist[0] = '$';

            self::$currencynumber = 0;

            $con = Propel::getConnection();
            $sql = "Select CurrencyID,CurrencySymbol from Currency order by CurrencyID;";

            $stmt = $con->prepare($sql);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                self::$currencylist[self::$currencynumber] = $row[0];
                self::$currencysymbollist[self::$currencynumber] = $row[1];
                self::$currencynumber++;
            }
        }
    }

    public static function FormatAmountWithCurrency($amount, $currency) {
        self::setCurrencyList();

        $CurrencySymbol = "$";

        for ($i = 0; $i < self::$currencynumber; $i++) {
            if (strcasecmp(self::$currencylist[$i], $currency) == 0) {
                $CurrencySymbol = self::$currencysymbollist[$i];
                break;
            }
        }

        return $CurrencySymbol . number_format($amount, 2);
    }

    /**
     * download a given file from an action
     *
     * @param string $file
     * @param file $output_name
     * @author radu
     */
    public static function downloadFile($file, $output_name='file') {

        //First, see if the file exists
        if (!is_file($file)) {
            return;
        }

        //Scoatem informatii despre fisier
        $len = filesize($file);
        $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));
        $file_explode = explode('.', $output_name);
        unset($file_explode[count($file_explode) - 1]);
        $file_prefix = implode('.', $file_explode);
        $filename = self::stripText($file_prefix) . '.' . $file_extension;
        //Setam Content-Type-urile pentru  fisierul in cauza
        switch ($file_extension) {
            case "pdf": $ctype = "application/pdf";
                break;
            case "zip": $ctype = "application/octet-stream";
                break;
            case "doc": $ctype = "application/msword";
                break;
            //case "xls": $ctype="application/vnd.ms-excel"; break;
            default: $ctype = "application/force-download";
        }

        //Scriem headerele
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");

        //Folosim Content-Type-ul din switch
        header("Content-Type: $ctype");

        //Fortam downloadul
        $header = "Content-Disposition: attachment; filename=" . $filename . ";";
        header($header);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $len);
        @readfile($file);
        exit;
    }

    /**
     * strip odds chars from a text, the text will be available for url
     *
     * @param string $text
     * @return string
     * @author radu
     */
    public static function stripText($text) {
        $text = strtolower($text);

        // strip all non word chars
//        $text = preg_replace('/\W/', ' ', $text);
        // replace all white space sections with a dash
        $text = preg_replace('/\ +/', '', $text);

        // trim dashes
        $text = preg_replace('/\-$/', '', $text);
        $text = preg_replace('/^\-/', '', $text);

        return $text;
    }

    public static function truncate_text($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false) {
        if ($text == '') {
            return '';
        }

        $mbstring = extension_loaded('mbstring');
        if ($mbstring) {
            $old_encoding = mb_internal_encoding();
            @mb_internal_encoding(mb_detect_encoding($text));
        }
        $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
        $substr = ($mbstring) ? 'mb_substr' : 'substr';

        if ($strlen($text) > $length) {
            $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
            if ($truncate_lastspace) {
                $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
            }
            $text = $truncate_text . $truncate_string;
        }

        if ($mbstring) {
            @mb_internal_encoding($old_encoding);
        }

        return $text;
    }

    public static function deleteDirectory($dir) {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir) || is_link($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!self::deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!self::deleteDirectory($dir . "/" . $item))
                    return false;
            };
        }
        return rmdir($dir);
    }

    public static function timestampExtractDatePart($timestamp) {
        return strtotime(date('Y-m-d', $timestamp));
    }

    /**
     * if $timestamp_a > ($timestamp_b - $xdays)
     *
     * @param int $xdays
     * @return bool
     */
    public static function isDateMoreThenXdays($timestamp_a, $timestamp_b, $xdays) {
        $t_a = Utils::timestampExtractDatePart($timestamp_a);
        $t_b = Utils::timestampExtractDatePart($timestamp_b);
        return $t_a > ($t_b - ($xdays * 3600 * 24));
    }

    public static function numToExcelAlpha($num) {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return self::numToExcelAlpha($num2) . $letter;
        } else {
            return $letter;
        }
    }

}