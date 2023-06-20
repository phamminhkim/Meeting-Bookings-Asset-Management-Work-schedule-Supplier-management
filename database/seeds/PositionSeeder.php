<?php

use Illuminate\Database\Seeder;
use App\Models\shared\Position;
use Illuminate\Support\Facades\DB;
class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $position = Position::where('name','CN Vận Hành Máy Ép Nhựa  - D')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Vận Hành Máy Ép Nhựa  - D', 'active'=>'1']);
        }
        $position = Position::where('name','NV Lao Động Tiền Lương')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Lao Động Tiền Lương', 'active'=>'1']);
        }
        $position = Position::where('name','TT Vận Hành Máy Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Vận Hành Máy Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','NV Xếp Dỡ Hàng Hoá')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Xếp Dỡ Hàng Hoá', 'active'=>'1']);
        }
        $position = Position::where('name','CN Lắp Ráp - D')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Lắp Ráp - D', 'active'=>'1']);
        }
        $position = Position::where('name','CN Đứng Máy (B) - D')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Đứng Máy (B) - D', 'active'=>'1']);
        }
        $position = Position::where('name','TT Bảo Trì Sửa Chữa Hệ Thống')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Bảo Trì Sửa Chữa Hệ Thống', 'active'=>'1']);
        }
        $position = Position::where('name','NV Vận Hành Máy Phụ Trợ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Vận Hành Máy Phụ Trợ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bảo Vệ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bảo Vệ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Truyền Thông')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Truyền Thông', 'active'=>'1']);
        }
        $position = Position::where('name','NV Phát Triển Thị Trường')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Phát Triển Thị Trường', 'active'=>'1']);
        }
        $position = Position::where('name','NV Sự Kiện')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Sự Kiện', 'active'=>'1']);
        }
        $position = Position::where('name','CN Sản Xuất')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Sản Xuất', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bảo Trì Sửa Chữa Hệ Thống')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bảo Trì Sửa Chữa Hệ Thống', 'active'=>'1']);
        }
        $position = Position::where('name','TP Kho Vận')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Kho Vận', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bán Hàng Phổ Thông Kênh GT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bán Hàng Phổ Thông Kênh GT', 'active'=>'1']);
        }
        $position = Position::where('name','CN Vận Hành Máy Ép Nhựa PE, PVC')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Vận Hành Máy Ép Nhựa PE, PVC', 'active'=>'1']);
        }
        $position = Position::where('name','NV Quản Trị Hệ Thống Tích Hợp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Quản Trị Hệ Thống Tích Hợp', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hành Chánh Tổng Hợp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hành Chánh Tổng Hợp', 'active'=>'1']);
        }
        $position = Position::where('name','TT Sản Xuất')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Sản Xuất', 'active'=>'1']);
        }
        $position = Position::where('name','QL Truyền Thông Toàn Cầu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Truyền Thông Toàn Cầu', 'active'=>'1']);
        }
        $position = Position::where('name','NV Điều Phối')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Điều Phối', 'active'=>'1']);
        }
        $position = Position::where('name','NV xây dựng cơ bản')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV xây dựng cơ bản', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hỗ Trợ Kỹ Thuật')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hỗ Trợ Kỹ Thuật', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hỗ Trợ Bán hàng Kênh MT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hỗ Trợ Bán hàng Kênh MT', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bán Hàng Chuyên Trách Kênh GT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bán Hàng Chuyên Trách Kênh GT', 'active'=>'1']);
        }
        $position = Position::where('name','Đại Diện Bán Hàng Kênh MT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Đại Diện Bán Hàng Kênh MT', 'active'=>'1']);
        }
        $position = Position::where('name','NV Quản Trị Web')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Quản Trị Web', 'active'=>'1']);
        }
        $position = Position::where('name','NV Tuyển Dụng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Tuyển Dụng', 'active'=>'1']);
        }
        $position = Position::where('name','NV Phát Triển Nguồn Nhân Lực')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Phát Triển Nguồn Nhân Lực', 'active'=>'1']);
        }
        $position = Position::where('name','TP Tuyển Dụng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Tuyển Dụng', 'active'=>'1']);
        }
        $position = Position::where('name','CN Vận Hành Máy Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Vận Hành Máy Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Xưởng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Xưởng', 'active'=>'1']);
        }
        $position = Position::where('name','CN Đứng Máy (A)')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Đứng Máy (A)', 'active'=>'1']);
        }
        $position = Position::where('name','CN Đứng Máy (C)')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Đứng Máy (C)', 'active'=>'1']);
        }
        $position = Position::where('name','CV Kế Toán Quản Trị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Kế Toán Quản Trị', 'active'=>'1']);
        }
        $position = Position::where('name','TT Chứng Từ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Chứng Từ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Chứng Từ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Chứng Từ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Giao Nhận')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Giao Nhận', 'active'=>'1']);
        }
        $position = Position::where('name','NV Giao Nhận & Thủ Tục Hải Quan')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Giao Nhận & Thủ Tục Hải Quan', 'active'=>'1']);
        }
        $position = Position::where('name','TT Điều Phối')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Điều Phối', 'active'=>'1']);
        }
        $position = Position::where('name','TP Chuỗi Cung Ứng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Chuỗi Cung Ứng', 'active'=>'1']);
        }
        $position = Position::where('name','TT Giao Nhận & Thủ Tục Hải Quan')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Giao Nhận & Thủ Tục Hải Quan', 'active'=>'1']);
        }
        $position = Position::where('name','PT GĐ Mua Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'PT GĐ Mua Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','CN Kiểm BTP & Vận Hành Máy Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Kiểm BTP & Vận Hành Máy Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','NT CN Lắp Ráp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT CN Lắp Ráp', 'active'=>'1']);
        }
        $position = Position::where('name','CN Lắp Ráp - A')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Lắp Ráp - A', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Tra Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Tra Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','CN Lắp Ráp - B')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Lắp Ráp - B', 'active'=>'1']);
        }
        $position = Position::where('name','TT BM Bút Gel')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT BM Bút Gel', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bán Hàng Trực Tuyến')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bán Hàng Trực Tuyến', 'active'=>'1']);
        }
        $position = Position::where('name','CN Đứng Máy (A) - D')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Đứng Máy (A) - D', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kế Hoạch Điều Độ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kế Hoạch Điều Độ', 'active'=>'1']);
        }
        $position = Position::where('name','CN Vận Hành Máy Ép Nhựa  - B')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Vận Hành Máy Ép Nhựa  - B', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Tra Chất Lượng SP Chuyền')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Tra Chất Lượng SP Chuyền', 'active'=>'1']);
        }
        $position = Position::where('name','NT CN Đứng Máy')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT CN Đứng Máy', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kỹ Thuật')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kỹ Thuật', 'active'=>'1']);
        }
        $position = Position::where('name','NV Đo Kiểm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Đo Kiểm', 'active'=>'1']);
        }
        $position = Position::where('name','NV Vận Hành Hệ Thống Xử Lý Nước Thải')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Vận Hành Hệ Thống Xử Lý Nước Thải', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Công Nghệ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Công Nghệ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Tra Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Tra Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','KTV Vận Hành Máy Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'KTV Vận Hành Máy Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','NV Gia Công Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Gia Công Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Tổng Hợp Thống Kê')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Tổng Hợp Thống Kê', 'active'=>'1']);
        }
        $position = Position::where('name','CN Lắp Ráp - C')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Lắp Ráp - C', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hóa Nghiệm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hóa Nghiệm', 'active'=>'1']);
        }
        $position = Position::where('name','TT Thiết Kế Mỹ Thuật')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Thiết Kế Mỹ Thuật', 'active'=>'1']);
        }
        $position = Position::where('name','CV Nghiên Cứu Thí Nghiệm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Nghiên Cứu Thí Nghiệm', 'active'=>'1']);
        }
        $position = Position::where('name','TP Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','NV Đánh Bóng Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Đánh Bóng Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Tuyển Dụng Đào Tạo')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Tuyển Dụng Đào Tạo', 'active'=>'1']);
        }
        $position = Position::where('name','CN Vận Hành Máy Xay Trộn Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CN Vận Hành Máy Xay Trộn Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','NT CN Vận Hành Máy Xay Trộn Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT CN Vận Hành Máy Xay Trộn Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','CV Quản Trị Hệ Thống Tích Hợp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Quản Trị Hệ Thống Tích Hợp', 'active'=>'1']);
        }
        $position = Position::where('name','TT Kiểm Soát Chất Lượng BTP & OEM')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Kiểm Soát Chất Lượng BTP & OEM', 'active'=>'1']);
        }
        $position = Position::where('name','QL Dự Án')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Dự Án', 'active'=>'1']);
        }
        $position = Position::where('name','Kế Toán Ngân Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Kế Toán Ngân Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Công Ty')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Công Ty', 'active'=>'1']);
        }
        $position = Position::where('name','NV Thử Khuôn & Vận Hành Máy Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Thử Khuôn & Vận Hành Máy Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','NV Nghiên Cứu Thí Nghiệm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Nghiên Cứu Thí Nghiệm', 'active'=>'1']);
        }
        $position = Position::where('name','TP Mua Hàng Nội Địa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Mua Hàng Nội Địa', 'active'=>'1']);
        }
        $position = Position::where('name','TT Bảo Trì Sửa Chữa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Bảo Trì Sửa Chữa', 'active'=>'1']);
        }
        $position = Position::where('name','TP Đảm Bảo Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Đảm Bảo Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','TT Quản Trị Ứng Dụng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Quản Trị Ứng Dụng', 'active'=>'1']);
        }
        $position = Position::where('name','TT Hoàn Thiện')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Hoàn Thiện', 'active'=>'1']);
        }
        $position = Position::where('name','NV Phụ Kho')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Phụ Kho', 'active'=>'1']);
        }
        $position = Position::where('name','KTV Bảo Trì Sửa Chữa Máy Móc Thiết Bị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'KTV Bảo Trì Sửa Chữa Máy Móc Thiết Bị', 'active'=>'1']);
        }
        $position = Position::where('name','TP Mua Hàng OEM')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Mua Hàng OEM', 'active'=>'1']);
        }
        $position = Position::where('name','Thủ Kho')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Thủ Kho', 'active'=>'1']);
        }
        $position = Position::where('name','NV Mua Hàng Nội Địa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Mua Hàng Nội Địa', 'active'=>'1']);
        }
        $position = Position::where('name','NV Lắp Ráp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Lắp Ráp', 'active'=>'1']);
        }
        $position = Position::where('name','TT Công Nghệ Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Công Nghệ Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV CNC')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV CNC', 'active'=>'1']);
        }
        $position = Position::where('name','TP Nhập Khẩu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Nhập Khẩu', 'active'=>'1']);
        }
        $position = Position::where('name','NV Vận Hành Máy Đầu Bút')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Vận Hành Máy Đầu Bút', 'active'=>'1']);
        }
        $position = Position::where('name','NV Mua Hàng Nhập Khẩu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Mua Hàng Nhập Khẩu', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bảo Trì Sửa Chữa Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bảo Trì Sửa Chữa Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','TP Sản Xuất Bút Viết')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Sản Xuất Bút Viết', 'active'=>'1']);
        }
        $position = Position::where('name','NV Thiết Kế Máy')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Thiết Kế Máy', 'active'=>'1']);
        }
        $position = Position::where('name','TP Kiểm Soát Nội Bộ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Kiểm Soát Nội Bộ', 'active'=>'1']);
        }
        $position = Position::where('name','TT Kiểm Tra Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Kiểm Tra Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','QL Tuyển Dụng Đào Tạo')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Tuyển Dụng Đào Tạo', 'active'=>'1']);
        }
        $position = Position::where('name','NV Công Nghệ Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Công Nghệ Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','TP Kiểm Soát Chất Lượng Chuyền')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Kiểm Soát Chất Lượng Chuyền', 'active'=>'1']);
        }
        $position = Position::where('name','TT Kiểm Soát Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Kiểm Soát Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','NT Gia Công')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Gia Công', 'active'=>'1']);
        }
        $position = Position::where('name','PT GĐ Tuân Thủ & Quản Trị Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'PT GĐ Tuân Thủ & Quản Trị Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Soát Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Soát Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kỹ Thuật Vận Hành Máy Ép Nhựa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kỹ Thuật Vận Hành Máy Ép Nhựa', 'active'=>'1']);
        }
        $position = Position::where('name','TT Bảo Vệ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Bảo Vệ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Thiết Kế Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Thiết Kế Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Xưởng Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Xưởng Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','Trưởng Ca Bảo Vệ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Trưởng Ca Bảo Vệ', 'active'=>'1']);
        }
        $position = Position::where('name','TP Phát Triển Phần Mềm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Phát Triển Phần Mềm', 'active'=>'1']);
        }
        $position = Position::where('name','NV Điện - Tự Động')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Điện - Tự Động', 'active'=>'1']);
        }
        $position = Position::where('name','NV Chế Tạo')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Chế Tạo', 'active'=>'1']);
        }
        $position = Position::where('name','TP Thiết Kế & Triển Khai Sản Phẩm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Thiết Kế & Triển Khai Sản Phẩm', 'active'=>'1']);
        }
        $position = Position::where('name','TP Bảo Trì Sửa Chữa Hệ Thống')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Bảo Trì Sửa Chữa Hệ Thống', 'active'=>'1']);
        }
        $position = Position::where('name','NV Thanh Toán Nhập Khẩu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Thanh Toán Nhập Khẩu', 'active'=>'1']);
        }
        $position = Position::where('name','NT Lắp Ráp Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Lắp Ráp Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Kế Hoạch Cung Ứng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Kế Hoạch Cung Ứng', 'active'=>'1']);
        }
        $position = Position::where('name','CV Pháp Chế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Pháp Chế', 'active'=>'1']);
        }
        $position = Position::where('name','TP Pháp Chế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Pháp Chế', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Tra Thử Nghiệm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Tra Thử Nghiệm', 'active'=>'1']);
        }
        $position = Position::where('name','NT Thiết Kế Máy')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Thiết Kế Máy', 'active'=>'1']);
        }
        $position = Position::where('name','TP R&D Công nghệ Hóa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP R&D Công nghệ Hóa', 'active'=>'1']);
        }
        $position = Position::where('name','TT Gia Công Chế Tạo Lắp Ráp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Gia Công Chế Tạo Lắp Ráp', 'active'=>'1']);
        }
        $position = Position::where('name','QL Thiết Kế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Thiết Kế', 'active'=>'1']);
        }
        $position = Position::where('name','CV Phân Tích Dữ Liệu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Phân Tích Dữ Liệu', 'active'=>'1']);
        }
        $position = Position::where('name','TT Bảo Trì Sửa Chữa Máy Móc Thiết Bị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Bảo Trì Sửa Chữa Máy Móc Thiết Bị', 'active'=>'1']);
        }
        $position = Position::where('name','NV Lễ Tân')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Lễ Tân', 'active'=>'1']);
        }
        $position = Position::where('name','NV QL Web & Kênh Truyền Thông')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV QL Web & Kênh Truyền Thông', 'active'=>'1']);
        }
        $position = Position::where('name','NT Bảo Trì Sửa Chữa Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Bảo Trì Sửa Chữa Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Nghiệm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Nghiệm', 'active'=>'1']);
        }
        $position = Position::where('name','NV Lập Trình')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Lập Trình', 'active'=>'1']);
        }
        $position = Position::where('name','NT Hoàn Thiện Lắp Ráp')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Hoàn Thiện Lắp Ráp', 'active'=>'1']);
        }
        $position = Position::where('name','NV Bán Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Bán Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','NT Thiết Kế Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Thiết Kế Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Dự Án')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Dự Án', 'active'=>'1']);
        }
        $position = Position::where('name','QL Thiết Kế Chế Tạo Máy')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Thiết Kế Chế Tạo Máy', 'active'=>'1']);
        }
        $position = Position::where('name','NV Giao Nhận Nhập Khẩu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Giao Nhận Nhập Khẩu', 'active'=>'1']);
        }
        $position = Position::where('name','NV Đóng Gói Và Hoàn Thiện Đầu Bút')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Đóng Gói Và Hoàn Thiện Đầu Bút', 'active'=>'1']);
        }
        $position = Position::where('name','NV Thiết Kế Mỹ Thuật')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Thiết Kế Mỹ Thuật', 'active'=>'1']);
        }
        $position = Position::where('name','TP Kiểm Soát Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Kiểm Soát Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Soát Chất Lượng Nhà Cung Ứng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Soát Chất Lượng Nhà Cung Ứng', 'active'=>'1']);
        }
        $position = Position::where('name','NT Chế Tạo')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NT Chế Tạo', 'active'=>'1']);
        }
        $position = Position::where('name','NV Triển Khai Sản Phẩm Mới & Điều Phối')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Triển Khai Sản Phẩm Mới & Điều Phối', 'active'=>'1']);
        }
        $position = Position::where('name','Kế Toán Công Nợ Nội Bộ Phải Trả')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Kế Toán Công Nợ Nội Bộ Phải Trả', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hỗ Trợ Nghiệp Vụ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hỗ Trợ Nghiệp Vụ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Điều Phối Kho Hàng Hóa Và Thành Phẩm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Điều Phối Kho Hàng Hóa Và Thành Phẩm', 'active'=>'1']);
        }
        $position = Position::where('name','NV Y Tế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Y Tế', 'active'=>'1']);
        }
        $position = Position::where('name','NV Quản Trị Ứng Dụng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Quản Trị Ứng Dụng', 'active'=>'1']);
        }
        $position = Position::where('name','QL Kỹ Thuật Và Điện - Tự Động')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Kỹ Thuật Và Điện - Tự Động', 'active'=>'1']);
        }
        $position = Position::where('name','QL Thiết Kế & Bảo Trì Sửa Chữa Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Thiết Kế & Bảo Trì Sửa Chữa Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kiểm Soát An Toàn Lao Động')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kiểm Soát An Toàn Lao Động', 'active'=>'1']);
        }
        $position = Position::where('name','CV Kiểm Soát Tuân Thủ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Kiểm Soát Tuân Thủ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Nghiệp Vụ CCƯ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Nghiệp Vụ CCƯ', 'active'=>'1']);
        }
        $position = Position::where('name','QL Kế Hoạch - Điều Độ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Kế Hoạch - Điều Độ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Giám Sát Chất Lượng Quốc Tế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Giám Sát Chất Lượng Quốc Tế', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Chuỗi Cung Ứng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Chuỗi Cung Ứng', 'active'=>'1']);
        }
        $position = Position::where('name','TT Thiết Kế Máy')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Thiết Kế Máy', 'active'=>'1']);
        }
        $position = Position::where('name','TP Hành Chánh Nhân Sự')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Hành Chánh Nhân Sự', 'active'=>'1']);
        }
        $position = Position::where('name','NV Lắp Ráp Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Lắp Ráp Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Nghiệp Vụ TCKT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Nghiệp Vụ TCKT', 'active'=>'1']);
        }
        $position = Position::where('name','TT Bảo Trì Sửa Chữa Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Bảo Trì Sửa Chữa Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','TT Gia Công')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Gia Công', 'active'=>'1']);
        }
        $position = Position::where('name','NV Đánh Giá Hệ Thống QL CLNCC')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Đánh Giá Hệ Thống QL CLNCC', 'active'=>'1']);
        }
        $position = Position::where('name','Kế Toán Thanh Toán')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Kế Toán Thanh Toán', 'active'=>'1']);
        }
        $position = Position::where('name','CV Mua Hàng Quốc Tế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Mua Hàng Quốc Tế', 'active'=>'1']);
        }
        $position = Position::where('name','TP Sản Xuất Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Sản Xuất Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Mua Hàng Dịch Vụ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Mua Hàng Dịch Vụ', 'active'=>'1']);
        }
        $position = Position::where('name','NV Mua Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Mua Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hành Chánh')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hành Chánh', 'active'=>'1']);
        }
        $position = Position::where('name','TP Bảo Trì Sửa Chữa MMTB')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Bảo Trì Sửa Chữa MMTB', 'active'=>'1']);
        }
        $position = Position::where('name','TT Thiết Kế Khuôn')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Thiết Kế Khuôn', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kế Toán Quản Trị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kế Toán Quản Trị', 'active'=>'1']);
        }
        $position = Position::where('name','TT Y Tế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Y Tế', 'active'=>'1']);
        }
        $position = Position::where('name','NV Nghiên Cứu Thiết Kế Sản Phẩm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Nghiên Cứu Thiết Kế Sản Phẩm', 'active'=>'1']);
        }
        $position = Position::where('name','QL Kho')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Kho', 'active'=>'1']);
        }
        $position = Position::where('name','Kế Toán Thuế & Giá Thành')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Kế Toán Thuế & Giá Thành', 'active'=>'1']);
        }
        $position = Position::where('name','TP Bán Hàng - Dự Án')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Bán Hàng - Dự Án', 'active'=>'1']);
        }
        $position = Position::where('name','NV Nghiệp Vụ Nhập Khẩu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Nghiệp Vụ Nhập Khẩu', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kế Toán')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kế Toán', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Tiếp Thị Toàn Cầu')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Tiếp Thị Toàn Cầu', 'active'=>'1']);
        }
        $position = Position::where('name','TL Chủ Tịch Hội Đồng Quản Trị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TL Chủ Tịch Hội Đồng Quản Trị', 'active'=>'1']);
        }
        $position = Position::where('name','KTV Pha Chế')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'KTV Pha Chế', 'active'=>'1']);
        }
        $position = Position::where('name','NV Hành Chánh Nhân Sự')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Hành Chánh Nhân Sự', 'active'=>'1']);
        }
        $position = Position::where('name','QL Mua Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Mua Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','TP Tiếp thị Thương Mại')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Tiếp thị Thương Mại', 'active'=>'1']);
        }
        $position = Position::where('name','NV QL Chương Trình Bán Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV QL Chương Trình Bán Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','PT GĐ Tiếp Thị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'PT GĐ Tiếp Thị', 'active'=>'1']);
        }
        $position = Position::where('name','Thư Ký PT GĐ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Thư Ký PT GĐ', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Phát Triển Kinh Doanh')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Phát Triển Kinh Doanh', 'active'=>'1']);
        }
        $position = Position::where('name','PT GĐ Tài Chính Kế Toán')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'PT GĐ Tài Chính Kế Toán', 'active'=>'1']);
        }
        $position = Position::where('name','Cố Vấn Chiến Lược Kinh Doanh')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Cố Vấn Chiến Lược Kinh Doanh', 'active'=>'1']);
        }
        $position = Position::where('name','Tổng GĐ')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Tổng GĐ', 'active'=>'1']);
        }
        $position = Position::where('name','CV Dự Án Phân Phối Ngoài')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Dự Án Phân Phối Ngoài', 'active'=>'1']);
        }
        $position = Position::where('name','Giám Sát Bán Hàng Kênh GT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Giám Sát Bán Hàng Kênh GT', 'active'=>'1']);
        }
        $position = Position::where('name','PT GĐ Nhân Lực Và Văn Hóa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'PT GĐ Nhân Lực Và Văn Hóa', 'active'=>'1']);
        }
        $position = Position::where('name','TN Phát Triển Thị Trường')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TN Phát Triển Thị Trường', 'active'=>'1']);
        }
        $position = Position::where('name','TL QL Nhãn Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TL QL Nhãn Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','QL Phát Triển Dự Án')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QL Phát Triển Dự Án', 'active'=>'1']);
        }
        $position = Position::where('name','NV Kỹ Thuật Hóa')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Kỹ Thuật Hóa', 'active'=>'1']);
        }
        $position = Position::where('name','TP QL Nhãn Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP QL Nhãn Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','TP QL Sản Phẩm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP QL Sản Phẩm', 'active'=>'1']);
        }
        $position = Position::where('name','CV Tài Chính')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Tài Chính', 'active'=>'1']);
        }
        $position = Position::where('name','NV Giao Nhận Sản Xuất')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'NV Giao Nhận Sản Xuất', 'active'=>'1']);
        }
        $position = Position::where('name','Kế Toán Bán Hàng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Kế Toán Bán Hàng', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Bán Hàng Kênh GT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Bán Hàng Kênh GT', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Bán Hàng Kênh MT')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Bán Hàng Kênh MT', 'active'=>'1']);
        }
        $position = Position::where('name','TP Điều Phối')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Điều Phối', 'active'=>'1']);
        }
        $position = Position::where('name','TP Quản Trị Hệ Thống')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Quản Trị Hệ Thống', 'active'=>'1']);
        }
        $position = Position::where('name','Tổng GĐ Điều Hành')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'Tổng GĐ Điều Hành', 'active'=>'1']);
        }
        $position = Position::where('name','TP Kế Toán Quản Trị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Kế Toán Quản Trị', 'active'=>'1']);
        }
        $position = Position::where('name','GĐ Tiếp Thị')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'GĐ Tiếp Thị', 'active'=>'1']);
        }
        $position = Position::where('name','TP Nhân Sự')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Nhân Sự', 'active'=>'1']);
        }
        $position = Position::where('name','TP Phát Triển Thị Trường')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Phát Triển Thị Trường', 'active'=>'1']);
        }
        $position = Position::where('name','CV Quan Hệ Cổ Đông')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'CV Quan Hệ Cổ Đông', 'active'=>'1']);
        }
        $position = Position::where('name','TP Quản Trị Chất Lượng')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TP Quản Trị Chất Lượng', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Phân Xưởng Đùn, Thổi')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Phân Xưởng Đùn, Thổi', 'active'=>'1']);
        }
        $position = Position::where('name','TT Hành Chánh')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'TT Hành Chánh', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Phân Xưởng Keo và MP/CP')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Phân Xưởng Keo và MP/CP', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Phân Xưởng Ép I')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Phân Xưởng Ép I', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Phân Xưởng File')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Phân Xưởng File', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Phân Xưởng Gôm')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Phân Xưởng Gôm', 'active'=>'1']);
        }
        $position = Position::where('name','QĐ Phân Xưởng Ráp Bút')->get();
        if(!$position || $position->count()==0){
            Position::create([ 'name' => 'QĐ Phân Xưởng Ráp Bút', 'active'=>'1']);
        }
    }
}
