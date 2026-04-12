# Giải thích: Hệ thống Album ảnh & Quản lý Biến thể (6 ảnh)

Tài liệu này giải thích cách tôi đã nâng cấp hệ thống của bạn để hỗ trợ 6 hình ảnh (1 chính + 5 phụ), tăng giới hạn dung lượng và cách quản lý chúng.

---

## 1. Hệ thống Hình ảnh (6 ảnh)

### 1.1. Cấu trúc lưu trữ
- **Ảnh đại diện**: Lưu tại cột `image` trong bảng `products`. Đây là ảnh hiển thị chính ở danh sách sản phẩm.
- **Album ảnh phụ (Gallery)**: Lưu tại bảng mới `product_images`. Bảng này liên kết với sản phẩm qua `product_id`.
- **Dung lượng**: Tôi đã nâng giới hạn lên **10MB/mỗi ảnh** trong code xử lý.

### 1.2. Upload ảnh mới (Trang Create & Edit)
- Tôi sử dụng thẻ `<input type="file" multiple>` để bạn có thể chọn cùng lúc 5 ảnh phụ từ máy tính.
- Giao diện có phần **Preview** (Xem trước) để bạn biết mình đã chọn những ảnh nào trước khi bấm Lưu.
- Chế độ chọn ảnh là **Click to Upload** (nhấn để chọn) đơn giản, không dùng kéo thả để giữ vẻ tự nhiên nhất cho đồ án của bạn.

---

## 2. Quản lý ảnh trong trang Chỉnh sửa (Edit)
Đây là phần quan trọng bạn yêu cầu:
- **Xem ảnh cũ**: Trang Edit sẽ hiện ra 5 ô ảnh phụ hiện tại của sản phẩm.
- **Xóa ảnh cũ**: Mỗi ảnh phụ sẽ có một nút **X** màu đỏ ở góc. 
    - Khi bạn nhấn vào đó, tôi sử dụng công nghệ **AJAX** (chạy ngầm). 
    - Hệ thống sẽ xóa file ảnh trên ổ cứng và xóa bản ghi trong database ngay lập tức mà không cần tải lại toàn bộ trang.
- **Thay ảnh mới**: Bạn chỉ cần chọn ảnh mới ở ô bên dưới và bấm "Cập nhật sản phẩm".

---

## 3. Quản lý Biến thể (Variants)
- **Cập nhật thông minh**: Khi bạn sửa sản phẩm, tôi đã thiết lập cơ chế xóa các biến thể cũ và lưu lại các biến thể mới bạn vừa nhập. Điều này giúp tránh việc bị loãn dữ liệu hoặc lỗi SKU.
- **SKU tự động**: Vẫn giữ nguyên logic cũ, SKU sẽ tự sinh ra theo `slug-size` để bạn quản lý kho hàng dễ dàng hơn.

---

## 4. Cách kiểm tra
1. Bạn hãy thử vào một sản phẩm đã có, nhấn **Sửa**.
2. Thử xóa một ảnh phụ bất kỳ (nhấn nút X đỏ).
3. Chọn thêm 1-2 ảnh phụ mới.
4. Bấm **Cập nhật** và xem thành quả.

> [!TIP]
> **Mẹo**: Nếu bạn muốn tăng số lượng ảnh lên 10 hay 20 ảnh, bạn chỉ cần sửa con số `max:5` thành số bạn muốn trong file `ProductController.php`. Hệ thống sẽ tự động thích ứng.
