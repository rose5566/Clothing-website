<?php
/***
水印:就是把指定的水印複製到目標上，並加透明效果

縮略圖:就是把大圖片複製到小尺寸畫面上
***/

class ImageTool {
    // imageInfo 分析圖片的信息
    // return array()
    public static function imageInfo($image) {
        // 判斷圖片是否存在
        if(!file_exists($image)) {
            return false;
        }

        $info = getimagesize($image);
        
        if($info == false) {
            return false;
        }

        // 此時info分析出来,是一个陣列
        $img['width'] = $info[0];
        $img['height'] = $info[1];
        $img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);

        return $img;
    }



    /*
        加水印功能
        parm String $dst 等操作圖片
        parm String $water 水印小圖
        parm String $save,不填則默認替換原始圖
    */
    public static function water($dst,$water,$save=NULL,$pos=2,$alpha=50) {
        // 先保證2個圖片存在
        if(!file_exists($dst) || !file_exists($water)) {
            return false;
        }
        
        
        // 首先保證水印不能比待操作圖片還大
        $dinfo = self::imageInfo($dst);
        $winfo = self::imageInfo($water);

        if($winfo['height'] > $dinfo['height'] || $winfo['width'] > $dinfo['width']) {
            return false;
        }

        // 兩張圖，讀到畫布上! 但是圖片可能是png,可能是jpeg,用什麼函數讀?
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $wfunc = 'imagecreatefrom' . $winfo['ext'];

        if(!function_exists($dfunc) || !function_exists($wfunc)) {
            return false;
        }


        // 動態加載函數來創建畫布
        $dim = $dfunc($dst);  // 創建待操作的畫布
        $wim = $wfunc($water);  // 創建水印畫布


        // 根据水印的位置 計算黏貼的坐標
        switch($pos) {
            case 0: // 左上角
            $posx = 0;
            $posy = 0;
            break;

            case 1: // 右上角
            $posx = $dinfo['width'] - $winfo['width'];
            $posy = 0;
            break;

            case 3: // 左下角
            $posx = 0;
            $posy = $dinfo['height'] - $winfo['height'];
            break;
        
            default:
            $posx = $dinfo['width'] - $winfo['width'];
            $posy = $dinfo['height'] - $winfo['height'];
        }


        // 加水印
        imagecopymerge ($dim,$wim, $posx , $posy , 0 , 0 , $winfo['width'] , $winfo['height'] , $alpha);

        // 保存
        if(!$save) {
            $save = $dst;
            unlink($dst); // 删除原圖
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($dim,$save);

        imagedestroy($dim);
        imagedestroy($wim);

        return true;
    }


    /**
        thumb 生成缩略圖
        等比例缩放,兩邊留白
    **/
    public static function thumb($dst,$save=NULL,$width=200,$height=200) {
        // 首先判斷待處理的圖片存不存在
        $dinfo = self::imageInfo($dst);
        if($dinfo == false) {
            return false;
        }

        // 計算缩放比例
        $calc = min($width/$dinfo['width'], $height/$dinfo['height']);

        // 創建原始圖的畫布
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $dim = $dfunc($dst);

        // 創建缩略畫布
        $tim = imagecreatetruecolor($width,$height);

        // 創建白色填充缩略畫布
        $white = imagecolorallocate($tim,255,255,255);

        // 填充縮略畫布
        imagefill($tim,0,0,$white);

        // 複製並縮略
        $dwidth = (int)$dinfo['width']*$calc;
        $dheight = (int)$dinfo['height']*$calc;

        $paddingx = (int)($width - $dwidth) / 2;
        $paddingy = (int)($height - $dheight) / 2;


        imagecopyresampled($tim,$dim,$paddingx,$paddingy,0,0,$dwidth,$dheight,$dinfo['width'],$dinfo['height']);

        // 保存圖片
        if(!$save) {
            $save = $dst;
            unlink($dst);
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($tim,$save);

        imagedestroy($dim);
        imagedestroy($tim);

        return true;

    }

}