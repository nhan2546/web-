<?php
// Các biến như $danh_sach_don_hang, $status_filter, $search_term đã được controller chuẩn bị
// Chúng ta chỉ cần sử dụng chúng ở đây.
$opts=['pending'=>'Chờ xác nhận','confirmed'=>'Xác nhận đơn hàng','shipping'=>'Đang giao','delivered'=>'Đã giao','success'=>'Thành công','cancelled'=>'Đã hủy'];
?>

<h1>Quản lý đơn hàng</h1>

<form method="get" class="admin-card" style="padding:15px; display:grid; grid-template-columns: repeat(6,1fr); gap:10px; align-items:end;">
  <input type="hidden" name="act" value="ds_donhang">
  <div><label>Từ khóa</label><input class="form-control" name="search" value="<?=htmlspecialchars($search_term ?? '')?>" placeholder="Mã đơn / Tên khách hàng"></div>
  <div>
    <label>Trạng thái</label>
    <select class="form-control" name="status">
      <option value="">Tất cả</option>
      <?php foreach($opts as $k=>$v){ $sel=($status_filter ?? '')===$k?'selected':''; echo "<option $sel value='$k'>$v</option>"; } ?>
    </select>
  </div>
  <div><label>Từ ngày</label><input class="form-control" type="date" name="from" value=""></div>
  <div><label>Đến ngày</label><input class="form-control" type="date" name="to" value=""></div>
  <div><button class="btn btn-primary" type="submit">Lọc</button></div>
  <div><a class="btn btn-info" href="?act=donhang">Xóa lọc</a></div>
</form>

<div class="admin-card">
  <div class="admin-card-body" style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã đơn</th><th>Khách hàng</th><th>Ngày tạo</th>
          <th>Tổng tiền</th><th>Trạng thái</th><th style="width:180px">Thao tác</th>
        </tr>
      </thead>
      <tbody>
      <?php if(empty($danh_sach_don_hang)): ?>
        <tr><td colspan="7">Không có đơn phù hợp.</td></tr>
      <?php else: foreach($danh_sach_don_hang as $don_hang): ?>
        <tr>
          <td>#<?=htmlspecialchars($don_hang['id'])?></td>
          <td><?=htmlspecialchars($don_hang['customer_name'])?></td>
          <td><?=date('d/m/Y H:i', strtotime($don_hang['order_date']))?></td>
          <td><?=number_format($don_hang['total_amount'],0,',','.')?>₫</td>
          <td>
            <form class="frm-status" method="post" action="admin.php?act=capnhat_trangthai_donhang" style="display:flex; gap:6px; align-items:center">
              <input type="hidden" name="id" value="<?=$don_hang['id']?>">
              <select name="status" class="form-control" style="min-width:130px">
                <?php foreach($opts as $k=>$v){ $sel=$don_hang['status']===$k?'selected':''; echo "<option $sel value='$k'>$v</option>"; } ?>
              </select>
              <button class="btn btn-info" type="submit">Lưu</button>
            </form>
          </td>
          <td>
            <a href="admin.php?act=ct_donhang&id=<?=$don_hang['id']?>" class="btn btn-info">Xem</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Phân trang -->
<div class="admin-pagination">
    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <ul class="pagination-list">
            <!-- Nút Previous -->
            <?php if ($current_page > 1): ?>
                <li><a href="?act=ds_donhang&page=<?= $current_page - 1 ?>&<?= http_build_query(array_diff_key($_GET, ['page'=>'', 'act'=>''])) ?>" class="pagination-link">‹</a></li>
            <?php endif; ?>

            <!-- Các nút số trang -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li>
                    <a href="?act=ds_donhang&page=<?= $i ?>&<?= http_build_query(array_diff_key($_GET, ['page'=>'', 'act'=>''])) ?>" 
                       class="pagination-link <?= ($i == $current_page) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Nút Next -->
            <?php if ($current_page < $total_pages): ?>
                <li><a href="?act=ds_donhang&page=<?= $current_page + 1 ?>&<?= http_build_query(array_diff_key($_GET, ['page'=>'', 'act'=>''])) ?>" class="pagination-link">›</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
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