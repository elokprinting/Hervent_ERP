<?php
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');require __DIR__.'/db.php';
function out($d,$s=200){http_response_code($s);echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
function body(){ $d=json_decode(file_get_contents('php://input'),true);return is_array($d)?$d:array();}
function did($pdo,$name){$s=$pdo->prepare("SELECT id FROM divisions WHERE name=? LIMIT 1");$s->execute(array($name?:'HerVent'));$x=$s->fetchColumn();return $x?(int)$x:1;}
function catalog($pdo){
 $q=$pdo->query("SELECT c.name category,s.name subcategory,pc.supplier_code,pc.hv_code FROM product_categories c JOIN product_subcategories s ON s.category_id=c.id LEFT JOIN product_codes pc ON pc.subcategory_id=s.id ORDER BY c.name,s.name,pc.id");
 $o=array();foreach($q as $r){$c=$r['category'];$s=$r['subcategory'];if(!isset($o[$c]))$o[$c]=array();if(!isset($o[$c][$s]))$o[$c][$s]=array();if($r['supplier_code']!==null)$o[$c][$s][]=array($r['supplier_code'],$r['hv_code']);}return $o;
}
function catId($pdo,$name){$s=$pdo->prepare("SELECT id FROM product_categories WHERE name=?");$s->execute(array($name));$x=$s->fetchColumn();if($x)return (int)$x;$pdo->prepare("INSERT INTO product_categories(name) VALUES(?)")->execute(array($name));return (int)$pdo->lastInsertId();}
function subId($pdo,$cat,$sub){$cid=catId($pdo,$cat);$s=$pdo->prepare("SELECT id FROM product_subcategories WHERE category_id=? AND name=?");$s->execute(array($cid,$sub));$x=$s->fetchColumn();if($x)return (int)$x;$pdo->prepare("INSERT INTO product_subcategories(category_id,name) VALUES(?,?)")->execute(array($cid,$sub));return (int)$pdo->lastInsertId();}
function codeId($pdo,$sid,$sup,$hv){$s=$pdo->prepare("SELECT id FROM product_codes WHERE subcategory_id=? AND supplier_code=? AND hv_code=?");$s->execute(array($sid,$sup,$hv));$x=$s->fetchColumn();if($x)return (int)$x;$pdo->prepare("INSERT INTO product_codes(subcategory_id,supplier_code,hv_code) VALUES(?,?,?)")->execute(array($sid,$sup,$hv));return (int)$pdo->lastInsertId();}
try{
 $d=($_SERVER['REQUEST_METHOD']??'GET')==='POST'?body():$_GET;$a=$d['action']??($_GET['action']??'bootstrap');
 if($a==='bootstrap'){
   $sql="SELECT p.external_id id,p.name nama,c.name kategori,s.name subKategori,pc.supplier_code kodeSupplier,pc.hv_code kodeHV,v.external_id vendorId,v.name vendor,p.cost_price hargaModal,p.profit_margin profit,p.selling_price hargaJual,p.quantity,p.image_path image,d.name divisi FROM products p LEFT JOIN divisions d ON d.id=p.division_id LEFT JOIN vendors v ON v.id=p.vendor_id LEFT JOIN product_subcategories s ON s.id=p.subcategory_id LEFT JOIN product_categories c ON c.id=s.category_id LEFT JOIN product_codes pc ON pc.id=p.product_code_id ORDER BY p.name";
   $products=$pdo->query($sql)->fetchAll();foreach($products as &$p){$p['hargaModal']=(float)$p['hargaModal'];$p['profit']=(float)$p['profit'];$p['hargaJual']=(float)$p['hargaJual'];$p['quantity']=(int)$p['quantity'];}unset($p);
   $vendors=$pdo->query("SELECT v.external_id id,v.name nama,v.contact kontak,v.address alamat,d.name divisi FROM vendors v LEFT JOIN divisions d ON d.id=v.division_id ORDER BY v.name")->fetchAll();
   out(array('ok'=>true,'products'=>$products,'vendors'=>$vendors,'productCatalog'=>catalog($pdo)));
 }
 if($a==='save_vendor'){
   $v=$d['vendor']??array();$ext=$v['id']??'';$name=trim((string)($v['nama']??''));if($ext===''||$name==='')out(array('ok'=>false,'error'=>'ID dan nama vendor wajib diisi.'),400);
   $pdo->prepare("INSERT INTO vendors(external_id,division_id,name,contact,address) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE division_id=VALUES(division_id),name=VALUES(name),contact=VALUES(contact),address=VALUES(address)")->execute(array($ext,did($pdo,$v['divisi']??'HerVent'),$name,$v['kontak']??'',$v['alamat']??''));out(array('ok'=>true));
 }
 if($a==='delete_vendor'){$pdo->prepare("DELETE FROM vendors WHERE external_id=?")->execute(array($d['id']??''));out(array('ok'=>true));}
 if($a==='save_master'){
   $type=$d['type']??'';$cat=trim((string)($d['category']??''));$sub=trim((string)($d['subcategory']??''));$sup=trim((string)($d['supplierCode']??''));$hv=trim((string)($d['hvCode']??''));
   if($cat===''||$sub==='')out(array('ok'=>false,'error'=>'Kategori dan sub kategori wajib diisi.'),400);$sid=subId($pdo,$cat,$sub);
   if($type==='category'||$type==='code'){if($sup===''||$hv==='')out(array('ok'=>false,'error'=>'Kode supplier dan kode HV wajib diisi.'),400);codeId($pdo,$sid,$sup,$hv);}
   out(array('ok'=>true));
 }
 if($a==='edit_master'){
   $type=$d['type']??'';
   $oldCat=trim((string)($d['oldCategory']??''));$oldSub=trim((string)($d['oldSubcategory']??''));$oldSup=trim((string)($d['oldSupplierCode']??''));
   $cat=trim((string)($d['category']??''));$sub=trim((string)($d['subcategory']??''));$sup=trim((string)($d['supplierCode']??''));$hv=trim((string)($d['hvCode']??''));
   $pdo->beginTransaction();
   try{
     if($type==='category'){
       if($oldCat===''||$cat==='')throw new Exception('Kategori lama dan kategori baru wajib diisi.');
       $q=$pdo->prepare("SELECT id FROM product_categories WHERE name=? LIMIT 1");$q->execute(array($oldCat));$id=$q->fetchColumn();if(!$id)throw new Exception('Kategori yang akan diedit tidak ditemukan.');
       $q=$pdo->prepare("SELECT id FROM product_categories WHERE name=? AND id<>? LIMIT 1");$q->execute(array($cat,$id));if($q->fetchColumn())throw new Exception('Nama kategori tersebut sudah digunakan.');
       $pdo->prepare("UPDATE product_categories SET name=? WHERE id=?")->execute(array($cat,$id));
     }elseif($type==='subcategory'){
       if($cat===''||$oldSub===''||$sub==='')throw new Exception('Kategori, sub kategori lama dan sub kategori baru wajib diisi.');
       $q=$pdo->prepare("SELECT id FROM product_categories WHERE name=? LIMIT 1");$q->execute(array($cat));$cid=$q->fetchColumn();if(!$cid)throw new Exception('Kategori tidak ditemukan.');
       $q=$pdo->prepare("SELECT id FROM product_subcategories WHERE category_id=? AND name=? LIMIT 1");$q->execute(array($cid,$oldSub));$sid=$q->fetchColumn();if(!$sid)throw new Exception('Sub kategori yang akan diedit tidak ditemukan.');
       $q=$pdo->prepare("SELECT id FROM product_subcategories WHERE category_id=? AND name=? AND id<>? LIMIT 1");$q->execute(array($cid,$sub,$sid));if($q->fetchColumn())throw new Exception('Nama sub kategori tersebut sudah digunakan pada kategori ini.');
       $pdo->prepare("UPDATE product_subcategories SET name=? WHERE id=?")->execute(array($sub,$sid));
     }elseif($type==='code'){
       if($cat===''||$sub===''||$oldSup===''||$sup===''||$hv==='')throw new Exception('Kategori, sub kategori, kode supplier lama, kode supplier baru dan kode HV wajib diisi.');
       $q=$pdo->prepare("SELECT s.id FROM product_subcategories s JOIN product_categories c ON c.id=s.category_id WHERE c.name=? AND s.name=? LIMIT 1");$q->execute(array($cat,$sub));$sid=$q->fetchColumn();if(!$sid)throw new Exception('Sub kategori tidak ditemukan.');
       $q=$pdo->prepare("SELECT id FROM product_codes WHERE subcategory_id=? AND supplier_code=? LIMIT 1");$q->execute(array($sid,$oldSup));$pcid=$q->fetchColumn();if(!$pcid)throw new Exception('Kode supplier yang akan diedit tidak ditemukan.');
       $q=$pdo->prepare("SELECT id FROM product_codes WHERE subcategory_id=? AND supplier_code=? AND id<>? LIMIT 1");$q->execute(array($sid,$sup,$pcid));if($q->fetchColumn())throw new Exception('Kode supplier tersebut sudah digunakan pada sub kategori ini.');
       $pdo->prepare("UPDATE product_codes SET supplier_code=?,hv_code=? WHERE id=?")->execute(array($sup,$hv,$pcid));
     }else{throw new Exception('Tipe master tidak valid.');}
     $pdo->commit();out(array('ok'=>true));
   }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();throw $e;}
 }
 if($a==='save_product'){
   $p=$d['product']??array();$ext=$p['id']??'';$name=trim((string)($p['nama']??''));if($ext===''||$name==='')out(array('ok'=>false,'error'=>'ID dan nama produk wajib diisi.'),400);
   $sid=null;$cid=null;if(!empty($p['kategori'])&&!empty($p['subKategori'])){$sid=subId($pdo,$p['kategori'],$p['subKategori']);if(($p['kodeSupplier']??'')!==''||($p['kodeHV']??'')!=='')$cid=codeId($pdo,$sid,$p['kodeSupplier']??'',$p['kodeHV']??'');}
   $vid=null;if(!empty($p['vendorId'])){$s=$pdo->prepare("SELECT id FROM vendors WHERE external_id=?");$s->execute(array($p['vendorId']));$vid=$s->fetchColumn()?:null;}
   $sql="INSERT INTO products(external_id,division_id,vendor_id,subcategory_id,product_code_id,name,cost_price,profit_margin,selling_price,quantity,image_path) VALUES(?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE division_id=VALUES(division_id),vendor_id=VALUES(vendor_id),subcategory_id=VALUES(subcategory_id),product_code_id=VALUES(product_code_id),name=VALUES(name),cost_price=VALUES(cost_price),profit_margin=VALUES(profit_margin),selling_price=VALUES(selling_price),quantity=VALUES(quantity),image_path=VALUES(image_path)";
   $pdo->prepare($sql)->execute(array($ext,did($pdo,$p['divisi']??'HerVent'),$vid,$sid,$cid,$name,(float)($p['hargaModal']??0),(float)($p['profit']??.3),(float)($p['hargaJual']??0),(int)($p['quantity']??0),$p['image']??''));out(array('ok'=>true));
 }
 if($a==='update_stock'){$pdo->prepare("UPDATE products SET quantity=? WHERE external_id=?")->execute(array((int)($d['quantity']??0),$d['id']??''));out(array('ok'=>true));}
 if($a==='delete_product'){$pdo->prepare("DELETE FROM products WHERE external_id=?")->execute(array($d['id']??''));out(array('ok'=>true));}
 out(array('ok'=>false,'error'=>'Action product tidak dikenal.'),400);
}catch(Throwable $e){out(array('ok'=>false,'error'=>'Server error: '.$e->getMessage()),500);}
