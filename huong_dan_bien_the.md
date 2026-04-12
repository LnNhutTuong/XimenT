# Giải thích: Cách hoạt động của Biến thể & SKU tự động

Tài liệu này giải thích logic lập trình mà tôi đã cài đặt để giúp bạn quản lý sản phẩm cùng với các biến thể (size, giá, kho) một cách tự động.

---

## 1. Logic nhập liệu tại Giao diện (Frontend)
Trong file `create.blade.php`, tôi đã sử dụng kỹ thuật **Dynamic Form Array** (Mảng form động):
- **Cấu trúc tên input**: Thay vì đặt tên `size_id`, `price`, tôi đặt là `variants[index][size_id]`. Trong đó `index` là số thứ tự (0, 1, 2...). 
- **Tại sao dùng mảng?**: Khi bạn nhấn "Thêm biến thể", Javascript sẽ tạo ra một dòng mới với `index` tăng dần. Khi gửi lên server, Laravel sẽ nhận được một mảng dữ liệu dễ dàng xử lý bằng vòng lặp.
- **Ràng buộc**: Tôi đã khóa nút "Xóa" nếu chỉ còn 1 dòng duy nhất, đảm bảo sản phẩm luôn có ít nhất 1 biến thể.

---

## 2. Logic xử lý tại Server (Backend)
Trong `ProductController@store`, quy trình diễn ra như sau:

### 2.1. Validation (Kiểm tra dữ liệu)
Tôi sử dụng ký tự `*` để kiểm tra tất cả các phần tử trong mảng:
```php
'variants.*.size_id' => 'required|exists:sizes,id',
```
Dòng này nghĩa là: "Mọi biến thể gửi lên đều bắt buộc phải có `size_id` hợp lệ trong bảng `sizes`".

### 2.2. Tiến trình lưu dữ liệu (Database Transaction)
Tôi sử dụng `DB::beginTransaction()` để đảm bảo tính toàn vẹn dữ liệu:
- Nếu lưu Sản phẩm thành công nhưng lưu Biến thể bị lỗi -> Hệ thống sẽ tự động hủy bỏ (Rollback) Sản phẩm đó để tránh dữ liệu rác.

### 2.3. Sinh mã SKU tự động
Đây là phần bạn yêu cầu. SKU được tạo ra bằng cách ghép:
**`Slug của sản phẩm` + `-` + `Slug của tên Kích cỡ`**

Ví dụ: 
- Tên sản phẩm: "Giày Nike" -> Slug: `giay-nike`
- Kích cỡ: "40"
- **SKU tự sinh**: `giay-nike-40`

---

## 3. Cách hiển thị và tính giá
- **Giá tham khảo**: Lưu ở bảng `products`, chủ yếu dùng để hiển thị "Giá từ...".
- **Giá bán thực tế**: Lưu ở bảng `product_variants`. Khi khách hàng chọn 1 size cụ thể, bạn nên dùng giá này để tính tiền.

---

> [!NOTE]
> **Lưu ý**: Nếu sau này bạn muốn đổi quy tắc sinh SKU (ví dụ thêm mã màu, hoặc dùng chữ IN HOA), bạn chỉ cần sửa 1 dòng duy nhất trong hàm `store` của `ProductController`.
