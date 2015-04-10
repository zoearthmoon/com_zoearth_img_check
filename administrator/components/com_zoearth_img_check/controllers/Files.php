<?php
/**
 * @author      Zoearth
 */ 
defined('_JEXEC') or die('Restricted access');

define('CONTROLLER','Files');
define('CONTROLLER_NAME','檔案');
define('CONTROLLER_BASE_URL',Juri::base().'index.php?option='.COM_NAME.'&view=Files');

class LoanCustomerControllerFiles extends ZoeController
{
    function display($cachable = false, $urlparams = false)
    {
        //$this->index();
    }
    
    function getFiles()
    {
        $guid = JRequest::getVar('guid');
        $imgWidth = JRequest::getVar('width');//20140110 zoearth 圖片用
        $imgHeight = JRequest::getVar('height');
        //20130801 zoearth 取得檔案
        $Files = new ZoeFiles();
        $fileData = $Files->get($guid);
        if (!$fileData)
        {
            JError::raiseError(500,"錯誤，找不到檔案");
        }

        $type = strtolower(substr($fileData['fileName'],-3,3));
        $isPic = FALSE;
        switch ($type)
        {
            case "jpg":
                $ContentType = 'image/jpeg';$isPic = TRUE;break;
            case "png":
                $ContentType = 'image/png';$isPic = TRUE;break;
            case "gif":
                $ContentType = 'image/gif';$isPic = TRUE;break;
            case "bmp":
                $ContentType = 'application/x-bmp';$isPic = TRUE;break;
            case "doc":
            case "docx":
                $ContentType = 'application/msword';break;
            default:
                $ContentType = 'application/octet-stream';break;
        }
        
        $file_name = $fileData['fileName'];
        $file_path = JPATH_SITE.DS.'images'.DS.COM_NAME.DS.$fileData['filePath'];
        if (!file_exists($file_path))
        {
            JError::raiseError(500,"錯誤，找不到檔案");
        }
        header('Pragma: public');
        header('Content-Type: '.$ContentType);
        header('Cache-Control: public, must-revalidate');
        header('Content-Transfer-Encoding: binary');
        
        if ($isPic)
        {
            $this->resize_pic($file_path,$imgWidth,$imgHeight);
        }
        else
        {
            $file_size = filesize($file_path);
            header('Content-Length: ' . $file_size);
            readfile($file_path);
        }
        exit();
    }
    
    function delete()
    {        
        if (!(isset($_POST['guid']) && $_POST['guid'] > 0 ))
        {
            echo '0';
            exit();
        }
        $guid = $_POST['guid'];
        //20130801 zoearth 取得檔案
        $Files = new ZoeFiles();
        $fileData = $Files->get($guid);
        
        if ($Files->delete($guid))
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
        exit();
    }
    
    //20140110 zoearth 圖片縮圖計算
    function get_im_xy($im,$pixx,$pixy)
    {
        //等比縮圖
        $im_path = $im;
        $im_xy = getimagesize($im_path);
        $x = $im_xy[0];
        $y = $im_xy[1];
    
        if($im_xy[0] > $pixx && $pixx > 0 )//假若X軸大於設定長度才縮圖
        {
            $im_info[0] = round($pixx); //先做第一次以X軸為基準等比縮放
            $im_info[1] = round(($pixx * $y ) / $x) ;
        }
        else //假若X軸小於設定長度則用原尺寸
        {
            $im_info[0] = $x;
            $im_info[1] = $y;
        }
        if($im_info[1] > $pixy && $pixy > 0 )// 要是Y軸大於設定長度 就以Y軸基準把第一次縮放的比例再縮放
        {
            $im_info[0] = round(($pixy * $im_info[0] ) / $im_info[1]) ;
            $im_info[1]= round($pixy);
        }
        return $im_info;
    }
    
    //20140110 zoearth 圖片縮圖
    function resize_pic($im_path,$x=0,$y=0)
    {    
        if (!is_file($im_path))
        {return FALSE;}
        $im_info = getimagesize($im_path);
        if( $im_info[2] == 2 || $im_info[2] == 3 || $im_info[2] == 6)
        {
            $new_size = $this->get_im_xy($im_path,$x,$y);
            if($im_info[2] == 1)
            {
                $im = imagecreatefromgif($im_path);
                $new_im = imagecreatetruecolor($new_size[0],$new_size[1]);
            }
            if($im_info[2] == 2)
            {
                $im = imagecreatefromjpeg($im_path);
                $new_im = imagecreatetruecolor($new_size[0],$new_size[1]);
            }
            if($im_info[2] == 3)
            {
                $im = imagecreatefrompng($im_path);
                $new_im = imagecreatetruecolor($new_size[0],$new_size[1]);
            }
            if($im_info[2] == 6)
            {
                $im = ImageCreateFromBMP($im_path);
                $new_im = imagecreatetruecolor($new_size[0],$new_size[1]);
            }
            imagecopyresampled($new_im,$im,0,0,0,0,$new_size[0],$new_size[1],$im_info[0],$im_info[1]);
            
            //20140312 zoearth 寫入暫存
            ob_start();
            if($im_info[2] == 1)
            {
                $newImg = imagegif($new_im);
            }
            if($im_info[2] == 2)
            {
                $newImg = ImageJPEG($new_im);
            }
            if($im_info[2] == 3)
            {
                $newImg = ImagePNG($new_im);
            }
            if($im_info[2] == 6)
            {
                $newImg = ImageJPEG($new_im);
            }
            $output = ob_get_contents();
            ob_end_clean();
            header('Content-Length: ' . strlen($output));
            echo $output;
            ImageDestroy($im);
            ImageDestroy($new_im);
        }
        elseif($im_info[2] == 1)
        {
            $new_im = file_get_contents($im_path);
            header('Content-Length: ' . strlen($new_im));
            echo $new_im;
        }
        else
        {
            echo 'ERROR003';
            exit();
        }
    }
}