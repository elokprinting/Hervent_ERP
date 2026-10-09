<?php
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');require __DIR__.'/db.php';
function out($d,$s=200){http_response_code($s);echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
function body(){ $d=json_decode(file_get_contents('php://input'),true);return is_array($d)?$d:array();}
try{
 $pdo->exec("CREATE TABLE IF NOT EXISTS print_type_product_map (print_type_id BIGINT UNSIGNED NOT NULL PRIMARY KEY, product_id BIGINT UNSIGNED NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, KEY idx_ptpm_product(product_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
 $d=($_SERVER['REQUEST_METHOD']??'GET')==='POST'?body():$_GET;$a=$d['action']??($_GET['action']??'list');
 if($a==='list'){
   $sql="SELECT pt.external_id id,pt.name nama,pt.min_qty qty,pt.price harga,p.external_id productId,p.name namaProduk FROM print_types pt LEFT JOIN print_type_product_map m ON m.print_type_id=pt.id LEFT JOIN products p ON p.id=m.product_id ORDER BY pt.name,pt.min_qty";
   $r=$pdo->query($sql)->fetchAll();foreach($r as &$x){$x['qty']=(int)$x['qty'];$x['harga']=(float)$x['harga'];}unset($x);out(array('ok'=>true,'printTypes'=>$r));
 }
 if($a==='save'){
   $x=$d['printType']??array();$ext=$x['id']??'';$name=trim((string)($x['nama']??''));$pext=$x['productId']??'';if($ext===''||$name===''||$pext==='')out(array('ok'=>false,'error'=>'Item dan Jenis Cetak wajib diisi.'),400);
   $s=$pdo->prepare("SELECT id FROM products WHERE external_id=?");$s->execute(array($pext));$pid=$s->fetchColumn();if(!$pid)out(array('ok'=>false,'error'=>'Produk tidak ditemukan.'),400);
   $pdo->prepare("INSERT INTO print_types(external_id,name,min_qty,price) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name),min_qty=VALUES(min_qty),price=VALUES(price)")->execute(array($ext,$name,max(1,(int)($x['qty']??1)),(float)($x['harga']??0)));
   $s=$pdo->prepare("SELECT id FROM print_types WHERE external_id=?");$s->execute(array($ext));$ptid=(int)$s->fetchColumn();
   $pdo->prepare("INSERT INTO print_type_product_map(print_type_id,product_id) VALUES(?,?) ON DUPLICATE KEY UPDATE product_id=VALUES(product_id)")->execute(array($ptid,$pid));out(array('ok'=>true));
 }
 if($a==='delete'){$s=$pdo->prepare("SELECT id FROM print_types WHERE external_id=?");$s->execute(array($d['id']??''));$id=$s->fetchColumn();if($id){$pdo->prepare("DELETE FROM print_type_product_map WHERE print_type_id=?")->execute(array($id));$pdo->prepare("DELETE FROM print_types WHERE id=?")->execute(array($id));}out(array('ok'=>true));}
 out(array('ok'=>false,'error'=>'Action jenis cetak tidak dikenal.'),400);
}catch(Throwable $e){out(array('ok'=>false,'error'=>'Server error: '.$e->getMessage()),500);}
