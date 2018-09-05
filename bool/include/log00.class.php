<?php
/**
file log00.class.php
作用: 紀錄信息到日誌
**/

//defined('ACC') || exit('ACC defined');

class Log00 {

    const LOGFILE = 'curr.log'; //建立一個常數，代表一個日志文件的名稱

    // 寫日志的文件
    public static function write($cont) {
        $cont .= "\r\n";
        // 判斷日誌是否備份
        $log = self::isBak(); // 計算出日志的文件地址
        
        $fh = fopen($log,'ab');
        fwrite($fh,$cont);
        fclose($fh); 
    }

    // 備份日志
    public static function bak() {
        //就是把原來的日志文件，建立的名稱，儲存起來。
        //用年-月-日，bak的格式
	
        $log = ROOT . 'data/log/' . self::LOGFILE;
        $bak = ROOT . 'data/log/' . date('ymd') . mt_rand(10000,99999) . '.bak';
        return rename($log,$bak);
    }

    // 讀取並判斷日志的大小
    public static function isBak() {
        $log = ROOT . 'data/log/' . self::LOGFILE;
        
        if(!file_exists($log)) { //如果文件不存在，則創建該文件
            touch($log);    // touch在Linux系統，是快速建立文件的命令
            return $log;
        }

        // 要是存在，則判斷大小
        // 清除缓存
        //clearstatcache(true,$log);
        $size = filesize($log);
        if($size <= 1024 * 1024) { //大於1M
            return $log;
        }
        
        // 走到這一行，說明>1M
        if(!self::Bak()) {
            return $log;
        } else {
            touch($log);
            return $log;
        }
    }
}




