<?php
include 'Upload.class.php';

$file = $_FILES['avator'];
//file_put_contents('./tmp.txt',json_encode($file));
$upload = new Upload();//上传工具类对象


if($save_path = $upload->up($file)){//上传成功
    echo <<<STR
    <script> 
     window.parent.document.getElementById('uploaded_img').src = "$save_path";
     window.parent.document.getElementById('loading').innerHTML = '上传成功'; 
     window.parent.document.getElementById('save_path').value = "$save_path"; 
    </script>
STR;
    
}else{
    $error = $upload->error();
    echo <<<STR
    <script>
     window.parent.document.getElementById('uploaded_img').src = "";
     window.parent.document.getElementById('loading').innerHTML = "上传失败: $error";
    </script>
STR;
      
}

