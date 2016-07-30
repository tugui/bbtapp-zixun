<?php
    	header("Content-type:text/html;charset=utf-8");

        $temp1 = '<!DOCTYPE html><html lang="zh-cn"><head><title>';
        $temp2 = '</title><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/></head><body><div class="main"><div class="header"><p>文章类型:';
        $temp9 = '<h3 class="pageTitle">';
        $temp3 = '</h3><p class="subTitle"><span class="nowDate">日期:';
        $temp4 = '</span><span class="writer">作者:';
        $temp5 = '</span></p><p>摘要:</p><p>';
        $temp6 = '</p></div><div class="content">';
        $temp7 = '</div><div class="footer" style="margin-top:10px;">';
        $temp8 = '</div></div></body></html>';
        $temp10 = '<div id="img"><img alt="封面缩略图" src="';

        $textType = $_POST['textType'];
        $data = '';

        if(isset($_POST['pageTitle'], 
          $_POST['writer'], 
          $_POST['nowDate'], 
          $_POST['content'], 
          $_POST['writerData'],
          $_POST['summary']) && 
          $_POST['textType'] != '' && 
          $_POST['pageTitle'] != '' && 
          $_POST['writer'] != '' && 
          $_POST['nowDate'] != '' && 
          $_POST['content'] != '' && 
          $_POST['writerData'] != '' && 
          $_POST['summary'] != ''){

          $pageTitle = $_POST['pageTitle'];
          $writer = $_POST['writer'];
          $nowDate = $_POST['nowDate'];
          $content = $_POST['content'];
          $writerData = $_POST['writerData'];
          $summary = $_POST['summary'];

          $content = str_replace('"','\"',$content);

          if ($textType === "dailySoup") {
            $article = 'http://218.192.166.167/api/protype/dailySoup/'.$pageTitle.'.html';

            $result = $temp1.$pageTitle.$temp2.'每日一文'.'</p>'.$temp9.$pageTitle.$temp3.$nowDate.$temp4.$writer.$temp5.$summary.$temp6.$content.$temp7.$writerData.$temp8;
            if (file_exists('http://218.192.166.167/api/protype/dailySoup/'.iconv('UTF-8', 'GBK', $pageTitle).'.html')) {
                echo '已存在此文章';
                return;
            }
            // saveFile('http://218.192.166.167/api/protype/dailySoup/'.iconv('UTF-8', 'GBK', $pageTitle).'.html',$result);
            
            

            $data = '{"date":"'.$nowDate.'","title":"'.$pageTitle.'","author":"'.$writer.'","article":"'.$content.'","summary":"'.$summary.'","authorIntroduce":"'.$writerData.'"}';

          } else if ($textType === "schoolInformation") {

            if (isset($_POST['picture']) && $_POST['picture'] != '') {
              $picture = $_POST['picture'];

            } else {
              echo "填写不全";
              return;
            }

            $article = 'http://218.192.166.167/api/protype/schoolInformation/'.$pageTitle.'.html';

            $result = $temp1.$pageTitle.$temp2.'校内咨询'.'</p>'.$temp10.$picture.'"/>'.$temp9.$pageTitle.$temp3.$nowDate.$temp4.$writer.$temp5.$summary.$temp6.$content.$temp7.$writerData.$temp8;
            if (file_exists('http://218.192.166.167/api/protype/schoolInformation/'.iconv('UTF-8', 'GBK', $pageTitle).'.html')) {
                echo '已存在此文章';
                return;
            }
            // saveFile('http://218.192.166.167/api/protype/schoolInformation/'.iconv('UTF-8', 'GBK', $pageTitle).'.html',$result);

          $data = '{"date":"'.$nowDate.'","title":"'.$pageTitle.'","author":"'.$writer.'","article":"'.$content.'","summary":"'.$summary.'","picture":"'.$picture.'"}';
          }

          // echo 'data是'.$data;

          $searchStr = array('.jpg\" title=\"', '.png\" title=\"', '.jpeg\" title=\"', '.gif\" title=\"', '.bmp\" title=\"');
          $replaceStr = array('.jpg?imageView2/0/w/200\" title=\"', '.png?imageView2/0/w/200\" title=\"', '.jpeg?imageView2/0/w/200\" title=\"', '.gif?imageView2/0/w/200\" title=\"', '.bmp?imageView2/0/w/200\" title=\"');

          $dataAfter = str_replace($searchStr, $replaceStr, $data);
          // echo 'dataAfter是'.$dataAfter;


          $url = 'http://218.192.166.167/api/protype.php?table='.$textType.'&method=save&data='.$dataAfter;
          $json = file_get_contents($url);
          // echo $json;
          echo '已存';

        }else{
            echo '填写不全';
        }
        
        
        function saveFile($fileName, $text) {
         if (!$fileName || !$text){
            return false;
         }
         if (makeDir(dirname($fileName))) {
             if ($fp = fopen($fileName, "w")) {
                 if (@fwrite($fp, $text)) {
                     fclose($fp);
                     return $fileName;
                 } else {
                     fclose($fp);
                     return false;
                 } 
             } 
         } 
         return false;
     } 
     
     function makeDir($dir) {
         $mode = "0777";
         if (!$dir){return false;}
 
         if(!file_exists($dir)) {
             return mkdir($dir,$mode,true);
         } else {
             return true;
         }
     }

?>
