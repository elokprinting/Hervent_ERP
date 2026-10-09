<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function out($d,$s=200){http_response_code($s);echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
try{
 if(($_SERVER['REQUEST_METHOD']??'')!=='POST')out(['ok'=>false,'error'=>'Method tidak diizinkan.'],405);
 if(($_POST['action']??'')!=='upload_order_image')out(['ok'=>false,'error'=>'Action upload tidak valid.'],400);
 if(!isset($_FILES['image'])||$_FILES['image']['error']!==UPLOAD_ERR_OK)out(['ok'=>false,'error'=>'File gambar tidak diterima.'],400);
 $f=$_FILES['image']; if($f['size']>2*1024*1024)out(['ok'=>false,'error'=>'Ukuran gambar maksimal 2 MB.'],400);
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
 $map=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp']; if(!isset($map[$mime]))out(['ok'=>false,'error'=>'Format gambar harus JPG, PNG, atau WEBP.'],400);
 $dir=__DIR__.'/uploads'; if(!is_dir($dir)&&!mkdir($dir,0755,true))out(['ok'=>false,'error'=>'Folder uploads tidak dapat dibuat.'],500);
 $name='order_'.date('Ymd_His').'_'.bin2hex(random_bytes(4)).'.'.$map[$mime];
 if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name))out(['ok'=>false,'error'=>'Gagal menyimpan gambar.'],500);
 out(['ok'=>true,'path'=>'uploads/'.$name]);
}catch(Throwable $e){out(['ok'=>false,'error'=>'Upload error: '.$e->getMessage()],500);}
