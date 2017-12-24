<?php
function writeLog($msg){ 
	$filename = "/usr/share/nginx/php/post_cards/log/debug.log";
        $res = array(); 
        $res['msg'] = $msg; 
        $res['logtime'] = date("Y-m-d H:i:s",time()); 
 
        //如果日志文件超过了指定大小则备份日志文件 
        if(file_exists($filename) && (abs(filesize($filename)) > 1024000)){ 
            $newfilename = dirname($filename).'/'.time().'-'.basename($filename); 
            rename($filename, $newfilename); 
        } 
 
        //如果是新建的日志文件，去掉内容中的第一个字符逗号 
        if(file_exists($filename) && abs(filesize($filename))>0){ 
            $content = json_encode($res)."\n"; 
        }else{ 
            $content = json_encode($res)."\n"; 
        } 
 
        //往日志文件内容后面追加日志内容 
        file_put_contents($filename, $content, FILE_APPEND); 
    } 

?>
