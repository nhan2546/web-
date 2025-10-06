<div class="container cart-section">
    <h2 class="mb-4">Giỏ Hàng Của Bạn</h2>
    <div class="row">
        <!-- Cột danh sách sản phẩm -->
        <div class="col-lg-8">
            <form action="index.php?act=thanh_toan" method="POST" id="cart-form">
                <div class="cart-items-container">
                    <table class="table cart-items-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"><input type="checkbox" id="select-all"></th>
                                <th colspan="2">Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Tổng cộng</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ví dụ sản phẩm trong giỏ hàng -->
                            <!-- TODO: Thay thế bằng vòng lặp để hiển thị sản phẩm từ session -->
                            <tr>
                                <td class="text-center align-middle"><input type="checkbox" class="product-checkbox" name="selected_products[]" value="1"></td>
                                <td style="width: 80px;">
                                    <img src="https://via.placeholder.com/80" alt="Iphone 14 Plus" class="cart-product-image">
                                </td>
                                <td class="align-middle">
                                    <div class="cart-product-info">
                                        <a href="#" class="cart-product-name">Iphone 14 Plus</a>
                                        <div class="cart-product-price">11.000.000 VND</div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="quantity-controls">
                                        <button type="button" class="btn btn-sm btn-secondary">-</button>
                                        <input type="text" value="1" class="form-control form-control-sm text-center" readonly>
                                        <button type="button" class="btn btn-sm btn-secondary">+</button>
                                    </div>
                                </td>
                                <td class="align-middle text-end">11.000.000 VND</td>
                                <td class="align-middle text-center">
                                    <button type="button" class="btn btn-sm btn-danger">&times;</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle"><input type="checkbox" class="product-checkbox" name="selected_products[]" value="2"></td>
                                <td><img src="https://via.placeholder.com/80" alt="Ốp lưng" class="cart-product-image"></td>
                                <td class="align-middle">
                                    <div class="cart-product-info">
                                        <a href="#" class="cart-product-name">Ốp lưng Silicon</a>
                                        <div class="cart-product-price">200.000 VND</div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="quantity-controls">
                                        <button type="button" class="btn btn-sm btn-secondary">-</button>
                                        <input type="text" value="1" class="form-control form-control-sm text-center" readonly>
                                        <button type="button" class="btn btn-sm btn-secondary">+</button>
                                    </div>
                                </td>
                                <td class="align-middle text-end">200.000 VND</td>
                                <td class="align-middle text-center">
                                    <button type="button" class="btn btn-sm btn-danger">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <!-- Cột tóm tắt đơn hàng -->
        <div class="col-lg-4">
            <div class="cart-summary">
                <h4 class="mb-3">Tóm tắt đơn hàng</h4>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính</span>
                    <span>11.200.000 VND</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Tổng cộng</span>
                    <span>11.200.000 VND</span>
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" form="cart-form" class="btn btn-primary btn-lg">Tiến hành Thanh Toán</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');

        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });
</script>