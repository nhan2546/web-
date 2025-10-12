<?php
// GiaoDien/QuanTri/san_pham/quan_ly_don_hang_admin.php

// header (đúng bố cục bạn có)
include __DIR__ . '/../dau_trang_admin.php';

// Kết nối DB & Model
require_once __DIR__ . '/../../../db_connect.php';     // chỉnh nếu file db nằm nơi khác
require_once __DIR__ . '/../../../DieuKhien/donhang_model.php';

$model = new DonHangModel($connect);

// filters
$q = $_GET['q'] ?? '';
$status = $_GET['status'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10; $offset = ($page-1)*$limit; $total=0;

$rows = $model->getOrders($q,$status,$from,$to,$limit,$offset,$total);
$pages = max(1, ceil($total/$limit));
$opts=['pending'=>'Chờ xử lý','processing'=>'Đang xử lý','shipped'=>'Đã gửi','delivered'=>'Đã giao','cancelled'=>'Đã hủy'];
?>

<h1>Quản lý đơn hàng</h1>

<form method="get" class="admin-card" style="padding:15px; display:grid; grid-template-columns: repeat(6,1fr); gap:10px; align-items:end;">
  <input type="hidden" name="act" value="donhang">
  <div><label>Từ khóa</label><input class="form-control" name="q" value="<?=htmlspecialchars($q)?>" placeholder="Mã đơn / Tên / SĐT"></div>
  <div>
    <label>Trạng thái</label>
    <select class="form-control" name="status">
      <option value="">Tất cả</option>
      <?php foreach($opts as $k=>$v){ $sel=$status===$k?'selected':''; echo "<option $sel value='$k'>$v</option>"; } ?>
    </select>
  </div>
  <div><label>Từ ngày</label><input class="form-control" type="date" name="from" value="<?=htmlspecialchars($from)?>"></div>
  <div><label>Đến ngày</label><input class="form-control" type="date" name="to" value="<?=htmlspecialchars($to)?>"></div>
  <div><button class="btn btn-primary" type="submit">Lọc</button></div>
  <div><a class="btn btn-info" href="?act=donhang">Xóa lọc</a></div>
</form>

<div class="admin-card">
  <div class="admin-card-body" style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã đơn</th><th>Khách hàng</th><th>SĐT</th><th>Ngày tạo</th>
          <th>Tổng tiền</th><th>Trạng thái</th><th style="width:180px">Thao tác</th>
        </tr>
      </thead>
      <tbody>
      <?php if(!$rows): ?>
        <tr><td colspan="7">Không có đơn phù hợp.</td></tr>
      <?php else: foreach($rows as $r): ?>
        <tr>
          <td>#<?=htmlspecialchars($r['code'])?></td>
          <td><?=htmlspecialchars($r['name'])?></td>
          <td><?=htmlspecialchars($r['phone'])?></td>
          <td><?=date('d/m/Y H:i', strtotime($r['created_at']))?></td>
          <td><?=number_format($r['total_amount'],0,',','.')?>₫</td>
          <td>
            <form class="frm-status" method="post" action="/web-/DieuKhien/donhang_update_trangthai.php" style="display:flex; gap:6px; align-items:center">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <select name="status" class="form-control" style="min-width:130px">
                <?php foreach($opts as $k=>$v){ $sel=$r['status']===$k?'selected':''; echo "<option $sel value='$k'>$v</option>"; } ?>
              </select>
              <button class="btn btn-info" type="submit">Lưu</button>
            </form>
          </td>
          <td>
            <button class="btn btn-info btn-detail" data-id="<?=$r['id']?>">Xem</button>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Phân trang -->
<div style="display:flex; gap:8px; justify-content:flex-end; align-items:center;">
  <?php for($i=1;$i<=$pages;$i++): 
    $url = "?act=donhang&q=".urlencode($q)."&status=$status&from=$from&to=$to&page=$i"; ?>
    <a class="btn <?= $i==$page?'btn-primary':'btn-info' ?>" href="<?=$url?>"><?=$i?></a>
  <?php endfor; ?>
</div>

<!-- Modal chi tiết -->
<div id="order-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="width:min(800px,90vw); background:#fff; border-radius:10px; padding:20px; max-height:80vh; overflow:auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
      <h3>Chi tiết đơn</h3>
      <button id="modal-close" class="btn btn-info">Đóng</button>
    </div>
    <div id="order-modal-body">Đang tải…</div>
  </div>
</div>

<script>
// xem chi tiết
document.querySelectorAll('.btn-detail').forEach(btn=>{
  btn.addEventListener('click', async ()=>{
    const id = btn.dataset.id;
    const modal = document.getElementById('order-modal');
    const body = document.getElementById('order-modal-body');
    body.innerHTML = 'Đang tải…';
    modal.style.display='flex';
    const res = await fetch('/web-/DieuKhien/donhang_chitiet.php?id='+id);
    body.innerHTML = await res.text();
  });
});
document.getElementById('modal-close').onclick=()=>document.getElementById('order-modal').style.display='none';

// lưu trạng thái ajax
document.querySelectorAll('.frm-status').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const fd = new FormData(f);
    const r = await fetch(f.action, {method:'POST', body:fd});
    const t = await r.text();
    alert(t.trim()==='OK'?'Đã lưu trạng thái!':t);
  });
});
</script>

<?php include __DIR__ . '/../chan_trang_admin.php'; ?>
