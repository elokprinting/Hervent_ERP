<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require __DIR__.'/db.php';

function out($d,$s=200){ http_response_code($s); echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit; }
function body(){ $d=json_decode(file_get_contents('php://input'),true); return is_array($d)?$d:array(); }
function dt($v){ if(!$v)return null; $v=str_replace('T',' ',(string)$v); return strlen($v)==16?$v.':00':$v; }
function divId($pdo,$name){
  $name=trim((string)$name); if($name==='')$name='HerVent';
  $s=$pdo->prepare('SELECT id FROM divisions WHERE name=? LIMIT 1');$s->execute(array($name));$id=$s->fetchColumn();
  if($id)return (int)$id;
  $code=strtoupper(substr(preg_replace('/[^A-Za-z]/','',$name),0,2));
  $s=$pdo->prepare('INSERT INTO divisions(name,code) VALUES(?,?)');$s->execute(array($name,$code));return (int)$pdo->lastInsertId();
}
function productPk($pdo,$external,$name=''){
  if($external!==''){ $s=$pdo->prepare('SELECT id FROM products WHERE external_id=? LIMIT 1');$s->execute(array($external));$x=$s->fetchColumn();if($x)return (int)$x; }
  if($name!==''){ $s=$pdo->prepare('SELECT id FROM products WHERE name=? LIMIT 1');$s->execute(array($name));$x=$s->fetchColumn();if($x)return (int)$x; }
  return null;
}
function usersGet($pdo){
 $q=$pdo->query("SELECT u.external_id AS id,u.username,u.password,d.name AS divisi,u.role,u.jabatan,u.kode_sc AS kodeSC FROM users u LEFT JOIN divisions d ON d.id=u.division_id ORDER BY u.id");
 return $q->fetchAll();
}
function divisionsGet($pdo){ return $pdo->query("SELECT name FROM divisions ORDER BY id")->fetchAll(PDO::FETCH_COLUMN); }
function ordersGet($pdo){
 $rows=$pdo->query("SELECT l.*,d.name AS divisi FROM leads l LEFT JOIN divisions d ON d.id=l.division_id ORDER BY l.id")->fetchAll();
 $out=array();
 foreach($rows as $r){
   $leadId=(int)$r['id'];
   $si=$pdo->prepare("SELECT li.*,p.external_id AS product_external FROM lead_items li LEFT JOIN products p ON p.id=li.product_id WHERE li.lead_id=? ORDER BY li.sort_order,li.id");$si->execute(array($leadId));
   $items=array();
   foreach($si->fetchAll() as $it)$items[]=array(
      'uid'=>$it['uid'],'productId'=>$it['product_external'],'namaProduk'=>$it['product_name'],'quantity'=>(int)$it['quantity'],
      'jenisCetak'=>$it['print_type'],'hargaProduk'=>(float)$it['product_price'],'hargaJenisCetak'=>(float)$it['print_price'],
      'minQtyJenisCetak'=>(int)$it['min_print_qty'],'hargaJenisCetakAktif'=>(bool)$it['print_price_active'],
      'hargaJualHV'=>(float)$it['hv_selling_price'],'total'=>(float)$it['line_total'],'image'=>$it['image_path']?:''
   );
   $st=$pdo->prepare("SELECT stage,status FROM production_timeline WHERE lead_id=? ORDER BY sort_order,id");$st->execute(array($leadId));$timeline=$st->fetchAll();
   $sc=$pdo->prepare("SELECT process_name FROM lead_custom_processes WHERE lead_id=? ORDER BY sort_order,id");$sc->execute(array($leadId));$custom=$sc->fetchAll(PDO::FETCH_COLUMN);
   $sr=$pdo->prepare("SELECT p.external_id AS productId,s.quantity FROM stock_reservations s JOIN products p ON p.id=s.product_id WHERE s.lead_id=?");$sr->execute(array($leadId));$reserv=$sr->fetchAll();
   $out[]=array(
    'id'=>$r['external_id'],'divisi'=>$r['divisi']?:'HerVent','brand'=>$r['brand']?:'HerVent','createdBy'=>$r['created_by'],'userName'=>$r['user_name'],
    'namaProject'=>$r['project_name'],'namaCustomer'=>$r['customer_name'],'namaPerusahaan'=>$r['customer_company'],
    'kontakCustomer'=>$r['customer_contact'],'alamatCustomer'=>$r['customer_address'],'emailCustomer'=>$r['customer_email'],
    'leadType'=>$r['lead_type'],'pm'=>$r['pm'],'kodeSC'=>$r['kode_sc'],'designer'=>$r['designer'],'warna'=>$r['color'],
    'packaging'=>$r['packaging'],'sumberLead'=>$r['lead_source'],'kebutuhan'=>$r['requirement_text'],
    'tanggalOrder'=>$r['order_date'],'deadline'=>$r['deadline'],'noPO'=>$r['po_number'],'ekspedisi'=>$r['expedition'],
    'noJO'=>$r['jo_number'],'jobOrderNo'=>$r['job_order_number'],'faNo'=>$r['fa_number'],'invoiceDP'=>$r['invoice_dp'],
    'invoicePelunasan'=>$r['invoice_settlement'],'ongkir'=>(float)$r['shipping_cost'],'shippingCost'=>(float)$r['shipping_cost'],
    'dpPercent'=>(float)$r['dp_percent'],'dpAmount'=>(float)$r['dp_amount'],'hargaSubtotal'=>(float)$r['subtotal'],
    'hargaTotal'=>(float)$r['total'],'catatan'=>$r['notes'],'leadStatus'=>$r['lead_status'],'cancelReason'=>$r['cancel_reason'],
    'cancelledAt'=>$r['cancelled_at'],'closedAt'=>$r['closed_at'],'closedBy'=>$r['closed_by']??null,'fuVendorDone'=>(bool)$r['fu_vendor_done'],'fuVendorLastAt'=>$r['fu_vendor_last_at']??null,
    'fuCustomerDone'=>(bool)$r['fu_customer_done'],'fuCustomerLastAt'=>$r['fu_customer_last_at']??null,'sequenceNo'=>$r['sequence_no'],'createdAt'=>$r['created_at'],
    'items'=>$items,'timeline'=>$timeline,'customProcesses'=>$custom,'stockReservation'=>$reserv
   );
 }
 return $out;
}
function belanjaGet($pdo){
 $rows=$pdo->query("SELECT s.*,p.external_id AS product_external FROM stock_movements s LEFT JOIN products p ON p.id=s.product_id ORDER BY s.id")->fetchAll();
 $out=array();foreach($rows as $r)$out[]=array(
   'id'=>$r['external_id'],'divisi'=>'HerVent','nama'=>$r['product_name'],'productId'=>$r['product_external'],
   'quantity'=>(int)$r['quantity'],'hargaModal'=>(float)$r['unit_cost'],'jumlah'=>(float)$r['total_amount'],
   'tanggal'=>$r['movement_date'],'vendor'=>$r['vendor_name'],'keterangan'=>$r['description'],'createdBy'=>$r['created_by']
 ); return $out;
}
function usersSet($pdo,$arr){
 foreach($arr as $u){
   $ext=(string)($u['id']??'');$username=trim((string)($u['username']??''));if($username==='')continue;
   if($ext==='')$ext='USR'.strtoupper(substr(md5($username.microtime(true)),0,8));
   $did=divId($pdo,$u['divisi']??'HerVent');
   $s=$pdo->prepare("INSERT INTO users(external_id,username,password,division_id,role,jabatan,kode_sc) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE username=VALUES(username),password=VALUES(password),division_id=VALUES(division_id),role=VALUES(role),jabatan=VALUES(jabatan),kode_sc=VALUES(kode_sc)");
   $s->execute(array($ext,$username,(string)($u['password']??''),$did,$u['role']??$u['jabatan']??'',$u['jabatan']??$u['role']??'',$u['kodeSC']??$u['kode_sc']??''));
 }
}
function divisionsSet($pdo,$arr){ foreach($arr as $name){ if(is_string($name)&&trim($name)!=='')divId($pdo,$name); } }
function ordersSet($pdo,$arr){
 $pdo->beginTransaction();
 try{
  // Payload hervent_orders selalu berisi snapshot lengkap. Catat external_id
  // agar row yang dihapus di frontend juga benar-benar dihapus dari MySQL.
  $incoming=array();
  foreach($arr as $row){
   $eid=trim((string)($row['id']??''));
   if($eid!=='')$incoming[$eid]=true;
  }
  foreach($arr as $o){
   $ext=(string)($o['id']??'');if($ext==='')continue;
   $did=divId($pdo,$o['divisi']??'HerVent');
   $brand=trim((string)($o['brand']??'HerVent'));if($brand==='')$brand='HerVent';
   $sql="INSERT INTO leads(external_id,division_id,brand,created_by,user_name,project_name,customer_name,customer_company,customer_contact,customer_address,customer_email,lead_type,pm,kode_sc,designer,color,packaging,lead_source,requirement_text,order_date,deadline,po_number,expedition,jo_number,job_order_number,fa_number,invoice_dp,invoice_settlement,shipping_cost,dp_percent,dp_amount,subtotal,total,notes,lead_status,cancel_reason,cancelled_at,closed_at,closed_by,fu_vendor_done,fu_vendor_last_at,fu_customer_done,fu_customer_last_at,sequence_no,created_at)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,COALESCE(?,NOW()))
    ON DUPLICATE KEY UPDATE division_id=VALUES(division_id),brand=VALUES(brand),created_by=VALUES(created_by),user_name=VALUES(user_name),project_name=VALUES(project_name),customer_name=VALUES(customer_name),customer_company=VALUES(customer_company),customer_contact=VALUES(customer_contact),customer_address=VALUES(customer_address),customer_email=VALUES(customer_email),lead_type=VALUES(lead_type),pm=VALUES(pm),kode_sc=VALUES(kode_sc),designer=VALUES(designer),color=VALUES(color),packaging=VALUES(packaging),lead_source=VALUES(lead_source),requirement_text=VALUES(requirement_text),order_date=VALUES(order_date),deadline=VALUES(deadline),po_number=VALUES(po_number),expedition=VALUES(expedition),jo_number=VALUES(jo_number),job_order_number=VALUES(job_order_number),fa_number=VALUES(fa_number),invoice_dp=VALUES(invoice_dp),invoice_settlement=VALUES(invoice_settlement),shipping_cost=VALUES(shipping_cost),dp_percent=VALUES(dp_percent),dp_amount=VALUES(dp_amount),subtotal=VALUES(subtotal),total=VALUES(total),notes=VALUES(notes),lead_status=VALUES(lead_status),cancel_reason=VALUES(cancel_reason),cancelled_at=VALUES(cancelled_at),closed_at=VALUES(closed_at),closed_by=VALUES(closed_by),fu_vendor_done=VALUES(fu_vendor_done),fu_vendor_last_at=VALUES(fu_vendor_last_at),fu_customer_done=VALUES(fu_customer_done),fu_customer_last_at=VALUES(fu_customer_last_at),sequence_no=VALUES(sequence_no)";
   $vals=array($ext,$did,$brand,$o['createdBy']??null,$o['userName']??$o['createdBy']??null,$o['namaProject']??'',$o['namaCustomer']??'',$o['namaPerusahaan']??null,$o['kontakCustomer']??null,$o['alamatCustomer']??null,$o['emailCustomer']??null,$o['leadType']??null,$o['pm']??null,$o['kodeSC']??null,$o['designer']??null,$o['warna']??null,$o['packaging']??null,$o['sumberLead']??null,$o['kebutuhan']??null,dt($o['tanggalOrder']??null),dt($o['deadline']??null),$o['noPO']??null,$o['ekspedisi']??null,$o['noJO']??null,$o['jobOrderNo']??$o['noJO']??null,$o['faNo']??null,$o['invoiceDP']??null,$o['invoicePelunasan']??null,(float)($o['ongkir']??$o['shippingCost']??0),(float)($o['dpPercent']??50),(float)($o['dpAmount']??0),(float)($o['hargaSubtotal']??0),(float)($o['hargaTotal']??0),$o['catatan']??null,$o['leadStatus']??'Lead',$o['cancelReason']??null,dt($o['cancelledAt']??null),dt($o['closedAt']??null),$o['closedBy']??null,!empty($o['fuVendorDone'])?1:0,dt($o['fuVendorLastAt']??null),!empty($o['fuCustomerDone'])?1:0,dt($o['fuCustomerLastAt']??null),$o['sequenceNo']??null,dt($o['createdAt']??null));
   $pdo->prepare($sql)->execute($vals);
   $s=$pdo->prepare("SELECT id FROM leads WHERE external_id=?");$s->execute(array($ext));$lid=(int)$s->fetchColumn();
   foreach(array('lead_items','production_timeline','lead_custom_processes','stock_reservations') as $tbl){$pdo->prepare("DELETE FROM ".$tbl." WHERE lead_id=?")->execute(array($lid));}
   $n=0;foreach(($o['items']??array()) as $it){$pid=productPk($pdo,$it['productId']??'',$it['namaProduk']??'');$pdo->prepare("INSERT INTO lead_items(lead_id,uid,product_id,product_name,quantity,print_type,product_price,print_price,min_print_qty,print_price_active,hv_selling_price,line_total,image_path,sort_order) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($lid,$it['uid']??('it'.$n),$pid,$it['namaProduk']??'',(int)($it['quantity']??0),$it['jenisCetak']??'',(float)($it['hargaProduk']??0),(float)($it['hargaJenisCetak']??0),(int)($it['minQtyJenisCetak']??1),!empty($it['hargaJenisCetakAktif'])?1:0,(float)($it['hargaJualHV']??0),(float)($it['total']??0),$it['image']??'', $n++));}
   $n=0;foreach(($o['timeline']??array()) as $t){$pdo->prepare("INSERT INTO production_timeline(lead_id,stage,status,sort_order) VALUES(?,?,?,?)")->execute(array($lid,$t['stage']??'',$t['status']??'Belum Mulai',$n++));}
   $n=0;foreach(($o['customProcesses']??array()) as $c){$pdo->prepare("INSERT INTO lead_custom_processes(lead_id,process_name,sort_order) VALUES(?,?,?)")->execute(array($lid,$c,$n++));}
   foreach(($o['stockReservation']??array()) as $r){$pid=productPk($pdo,$r['productId']??'','');if($pid)$pdo->prepare("INSERT INTO stock_reservations(lead_id,product_id,quantity) VALUES(?,?,?)")->execute(array($lid,$pid,(int)($r['quantity']??0)));}
  }
  // Hapus lead database yang tidak lagi ada pada snapshot frontend.
  $existing=$pdo->query("SELECT id,external_id FROM leads")->fetchAll();
  foreach($existing as $row){
   $eid=(string)($row['external_id']??'');
   if($eid!=='' && !isset($incoming[$eid])){
    $lid=(int)$row['id'];
    foreach(array('lead_items','production_timeline','lead_custom_processes','stock_reservations') as $tbl){
     $pdo->prepare("DELETE FROM ".$tbl." WHERE lead_id=?")->execute(array($lid));
    }
    $pdo->prepare("DELETE FROM leads WHERE id=?")->execute(array($lid));
   }
  }
  $pdo->commit();
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();throw $e;}
}

function omzetGet($pdo){
 $rows=$pdo->query("SELECT year,month,period_key,scope_type,scope_key,omzet,target,updated_at FROM omzet_monthly ORDER BY year,month,scope_type,scope_key")->fetchAll();
 $out=array();
 foreach($rows as $r)$out[]=array(
   'year'=>(int)$r['year'],
   'month'=>(int)$r['month'],
   'periodKey'=>$r['period_key'],
   'scopeType'=>$r['scope_type'],
   'scopeKey'=>$r['scope_key'],
   'omzet'=>(float)$r['omzet'],
   'target'=>(float)$r['target'],
   'updatedAt'=>$r['updated_at']
 );
 return $out;
}
function omzetSet($pdo,$arr){
 $sql="INSERT INTO omzet_monthly(year,month,period_key,scope_type,scope_key,omzet,target) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE period_key=VALUES(period_key),omzet=VALUES(omzet),target=VALUES(target),updated_at=CURRENT_TIMESTAMP";
 $st=$pdo->prepare($sql);
 foreach($arr as $r){
   $year=(int)($r['year']??0);$month=(int)($r['month']??0);
   if($year<2000 || $month<1 || $month>12)continue;
   $scopeType=(string)($r['scopeType']??'team');
   if(!in_array($scopeType,array('team','user'),true))$scopeType='team';
   $scopeKey=trim((string)($r['scopeKey']??'all'));if($scopeKey==='')$scopeKey='all';
   $periodKey=(string)($r['periodKey']??($year.'-'.str_pad((string)$month,2,'0',STR_PAD_LEFT)));
   $st->execute(array($year,$month,$periodKey,$scopeType,$scopeKey,(float)($r['omzet']??0),(float)($r['target']??0)));
 }
}

function belanjaSet($pdo,$arr){
 foreach($arr as $b){
  $ext=(string)($b['id']??'');if($ext==='')continue;$did=divId($pdo,$b['divisi']??'HerVent');$pid=productPk($pdo,$b['productId']??'',$b['nama']??'');
  $sql="INSERT INTO stock_movements(external_id,division_id,product_id,product_name,movement_type,quantity,unit_cost,total_amount,movement_date,vendor_name,description,source,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE division_id=VALUES(division_id),product_id=VALUES(product_id),product_name=VALUES(product_name),quantity=VALUES(quantity),unit_cost=VALUES(unit_cost),total_amount=VALUES(total_amount),movement_date=VALUES(movement_date),vendor_name=VALUES(vendor_name),description=VALUES(description),created_by=VALUES(created_by)";
  $pdo->prepare($sql)->execute(array($ext,$did,$pid,$b['nama']??'', 'IN',(int)($b['quantity']??0),(float)($b['hargaModal']??0),(float)($b['jumlah']??0),$b['tanggal']??date('Y-m-d'),$b['vendor']??null,$b['keterangan']??null,'frontend_belanja',$b['createdBy']??null));
 }
}
try{
 $method=$_SERVER['REQUEST_METHOD']??'GET';$d=$method==='POST'?body():$_GET;$action=$d['action']??($_GET['action']??'');
 if($action==='get'){
   $key=(string)($d['key']??$_GET['key']??'');
   if($key==='hervent_users')out(array('ok'=>true,'value'=>usersGet($pdo)));
   if($key==='hervent_divisions')out(array('ok'=>true,'value'=>divisionsGet($pdo)));
   if($key==='hervent_orders')out(array('ok'=>true,'value'=>ordersGet($pdo)));
   if($key==='hervent_belanja')out(array('ok'=>true,'value'=>belanjaGet($pdo)));
   if($key==='hervent_omzet_monthly')out(array('ok'=>true,'value'=>omzetGet($pdo)));
   out(array('ok'=>true,'value'=>null));
 }
 if($action==='set'){
   $key=(string)($d['key']??'');$v=$d['value']??array();if(!is_array($v))$v=array();
   if($key==='hervent_users')usersSet($pdo,$v);
   elseif($key==='hervent_divisions')divisionsSet($pdo,$v);
   elseif($key==='hervent_orders')ordersSet($pdo,$v);
   elseif($key==='hervent_belanja')belanjaSet($pdo,$v);
   elseif($key==='hervent_omzet_monthly')omzetSet($pdo,$v);
   out(array('ok'=>true));
 }
 out(array('ok'=>false,'error'=>'Action tidak dikenal.'),400);
}catch(Throwable $e){out(array('ok'=>false,'error'=>'Server error: '.$e->getMessage()),500);}
