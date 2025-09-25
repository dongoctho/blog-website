<?php

namespace Fuel\Migrations;

class Insert_sample_data
{
	public function up()
	{
		\DB::insert('roles')->set(array(
			'name' => 'admin',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('roles')->set(array(
			'name' => 'editor',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('roles')->set(array(
			'name' => 'client',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('users')->set(array(
			'username' => 'admin',
			'email' => 'admin@example.com',
			'password' => \Auth::hash_password('123456'),
			'role_id' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('users')->set(array(
			'username' => 'editor',
			'email' => 'editor@example.com',
			'password' => \Auth::hash_password('123456'),
			'role_id' => 2,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('categories')->set(array(
			'name' => 'Công nghệ',
			'parent_id' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('categories')->set(array(
			'name' => 'Du lịch',
			'parent_id' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('categories')->set(array(
			'name' => 'Ẩm thực',
			'parent_id' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('categories')->set(array(
			'name' => 'Kinh doanh',
			'parent_id' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('categories')->set(array(
			'name' => 'PHP',
			'parent_id' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('categories')->set(array(
			'name' => 'JavaScript',
			'parent_id' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Chào mừng đến với Blog CMS',
			'slug' => 'chao-mung-den-voi-blog-cms',
			'content' => 'Đây là bài viết đầu tiên trên hệ thống Blog CMS được xây dựng bằng FuelPHP. 

Hệ thống này cung cấp các tính năng:
- Tạo và quản lý bài viết
- Upload và quản lý hình ảnh
- Phân loại bài viết theo danh mục
- Giao diện responsive và hiện đại
- Tìm kiếm bài viết
- SEO friendly URLs

Bạn có thể bắt đầu viết bài mới bằng cách click vào nút "Viết bài mới" ở góc trên cùng.',
			'user_id' => 1,
			'category_id' => 1,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Hướng dẫn sử dụng FuelPHP Framework',
			'slug' => 'huong-dan-su-dung-fuelphp-framework',
			'content' => 'FuelPHP là một framework PHP linh hoạt và mạnh mẽ, được thiết kế để phát triển ứng dụng web nhanh chóng và hiệu quả.

<h2>Đặc điểm nổi bật:</h2>

1. Flexible Architecture: Kiến trúc linh hoạt cho phép tùy chỉnh theo nhu cầu
2. ORM mạnh mẽ: Hệ thống ORM tích hợp giúp thao tác database dễ dàng
3. Security: Bảo mật tốt với các tính năng validation và CSRF protection
4. RESTful: Hỗ trợ tốt cho việc xây dựng RESTful APIs
5. Community: Cộng đồng hỗ trợ tích cực

<h2>Installation:</h2>
```bash
composer create-project fuel/fuel myproject
```

Framework này rất phù hợp cho các dự án từ nhỏ đến vừa và lớn.',
			'user_id' => 1,
			'category_id' => 5,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
			'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Du lịch Việt Nam: Những điểm đến không thể bỏ qua',
			'slug' => 'du-lich-viet-nam-nhung-diem-den-khong-the-bo-qua',
			'content' => 'Việt Nam là một đất nước với nhiều cảnh đẹp thiên nhiên và di sản văn hóa phong phú. Dưới đây là những điểm đến bạn không nên bỏ lỡ khi du lịch Việt Nam:

<h2>Miền Bắc:</h2>
- **Hạ Long**: Vịnh Hạ Long với hàng nghìn hòn đảo đá vôi
- **Sapa**: Ruộng bậc thang và văn hóa dân tộc
- **Hà Nội**: Thủ đô ngàn năm văn hiến

<h2>Miền Trung:</h2>
- **Hội An**: Phố cổ với kiến trúc độc đáo
- **Huế**: Cố đô với nhiều di tích lịch sử
- **Đà Nẵng**: Thành phố biển hiện đại

<h2>Miền Nam:</h2>
- **TP.HCM**: Thành phố năng động nhất cả nước
- **Đồng bằng sông Cửu Long**: Miệt vườn Nam Bộ
- **Phú Quốc**: Đảo ngọc với bãi biển tuyệt đẹp

Mỗi vùng miền đều có nét đẹp riêng và món ăn đặc trưng, tạo nên sự đa dạng văn hóa độc đáo của Việt Nam.',
			'user_id' => 2,
			'category_id' => 2,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
			'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Lập trình JavaScript hiện đại với ES6+',
			'slug' => 'lap-trinh-javascript-hien-dai-voi-es6',
			'content' => 'JavaScript ES6+ mang đến nhiều tính năng mới giúp code sạch hơn và hiệu quả hơn.

<h2>Các tính năng chính:</h2>

<h3>Arrow Functions</h3>
```javascript
const sum = (a, b) => a + b;
```

<h3>Template Literals</h3>
```javascript
const message = `Hello ${name}!`;
```

<h3>Destructuring</h3>
```javascript
const {name, age} = user;
const [first, second] = array;
```

<h3>Promises và Async/Await</h3>
```javascript
async function fetchData() {
    const response = await fetch("/api/data");
    return response.json();
}
```

<h3>Classes</h3>
```javascript
class User {
    constructor(name) {
        this.name = name;
    }
}
```

Những tính năng này giúp JavaScript trở nên mạnh mẽ và dễ sử dụng hơn rất nhiều.',
			'user_id' => 2,
			'category_id' => 6,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
			'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Khám phá ẩm thực Việt Nam: Phở Hà Nội',
			'slug' => 'kham-pha-am-thuc-viet-nam-pho-ha-noi',
			'content' => 'Phở là món ăn tiêu biểu của Việt Nam, đặc biệt là Phở Hà Nội với hương vị đặc trưng.

<h2>Lịch sử của Phở:</h2>
Phở được cho là xuất hiện từ đầu thế kỷ 20 tại Hà Nội, sau đó lan rộng ra cả nước.

<h2>Nguyên liệu chính:</h2>
- **Bánh phở**: Làm từ bột gạo, cắt thành sợi mỏng
- **Nước dùng**: Ninh từ xương bò trong nhiều giờ
- **Thịt bò**: Thái lát mỏng, có thể tái hoặc chín
- **Rau thơm**: Hành lá, ngò, hành tây

<h2>Cách ăn đúng điệu:</h2>
1. Nêm nếm với nước mắm, tương ớt
2. Thêm rau thơm theo sở thích
3. Ăn nóng khi vừa được phục vụ
4. Không được cắt nhỏ bánh phở

Phở không chỉ là món ăn mà còn là nét văn hóa đặc trưng của người Việt.',
			'user_id' => 1,
			'category_id' => 3,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
			'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Khởi nghiệp thành công: 7 bước cơ bản',
			'slug' => 'khoi-nghiep-thanh-cong-7-buoc-co-ban',
			'content' => 'Khởi nghiệp là hành trình đầy thách thức nhưng cũng rất thú vị. Dưới đây là 7 bước cơ bản:

<h2>1. Xác định ý tưởng kinh doanh</h2>
- Tìm hiểu thị trường
- Phân tích đối thủ cạnh tranh
- Xác định giá trị cốt lõi

<h2>2. Lập kế hoạch kinh doanh</h2>
- Mô hình kinh doanh
- Chiến lược marketing
- Dự báo tài chính

<h2>3. Tìm nguồn vốn</h2>
- Vốn tự có
- Đầu tư từ bạn bè, gia đình
- Quỹ đầu tư mạo hiểm

<h2>4. Xây dựng đội ngũ</h2>
- Tuyển dụng nhân tài
- Xây dựng văn hóa công ty
- Phân chia equity hợp lý

<h2>5. Phát triển sản phẩm</h2>
- MVP (Minimum Viable Product)
- Thu thập feedback
- Cải thiện liên tục

<h2>6. Marketing và bán hàng</h2>
- Xây dựng thương hiệu
- Digital marketing
- Chăm sóc khách hàng

<h2>7. Mở rộng quy mô</h2>
- Tối ưu hóa vận hành
- Mở rộng thị trường
- Phát triển sản phẩm mới

Thành công không đến trong một đêm, hãy kiên trì và học hỏi không ngừng!',
			'user_id' => 2,
			'category_id' => 4,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
			'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Cẩm nang du lịch bụi Đông Nam Á',
			'slug' => 'cam-nang-du-lich-bui-dong-nam-a',
			'content' => 'Du lịch bụi Đông Nam Á là trải nghiệm tuyệt vời cho những ai yêu thích khám phá.

<h2>Lộ trình gợi ý (30 ngày):</h2>

<h3>Tuần 1: Thái Lan</h3>
- **Bangkok**: Chùa Wat Pho, Floating Market
- **Chiang Mai**: Old City, Night Bazaar
- **Phuket**: Bãi biển Patong, Phi Phi Islands

<h3>Tuần 2: Campuchia</h3>
- **Siem Reap**: Angkor Wat, Angkor Thom
- **Phnom Penh**: Royal Palace, Mekong River

<h3>Tuần 3: Việt Nam</h3>
- **TP.HCM**: Bến Thành Market, Củ Chi Tunnels
- **Hội An**: Ancient Town, Japanese Bridge
- **Hà Nội**: Old Quarter, Hoan Kiem Lake

<h3>Tuần 4: Lào</h3>
- **Luang Prabang**: Waterfalls, Night Market
- **Vientiane**: Pha That Luang, Buddha Park

<h2>Tips quan trọng:</h2>
- Mang theo passport có hạn trên 6 tháng
- Tiêm vaccine cần thiết
- Mua bảo hiểm du lịch
- Học một số từ cơ bản của địa phương
- Luôn mang theo tiền mặt

<h2>Chi phí ước tính:</h2>
- **Budget**: $30-50/ngày
- **Mid-range**: $50-100/ngày  
- **Luxury**: $100+/ngày

Đông Nam Á là điểm đến lý tưởng cho backpacker với chi phí hợp lý và văn hóa đa dạng!',
			'user_id' => 1,
			'category_id' => 2,
			'is_published' => 1,
			'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
			'updated_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
		))->execute();

		\DB::insert('posts')->set(array(
			'title' => 'Bài viết nháp - Đang phát triển',
			'slug' => 'bai-viet-nhap-dang-phat-trien',
			'content' => 'Đây là một bài viết đang trong quá trình phát triển. Nội dung sẽ được cập nhật sau.',
			'user_id' => 1,
			'category_id' => 1,
			'is_published' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		))->execute();
	}

	public function down()
	{
		// Delete sample data
		\DB::delete('posts')->where('id', 'IN', array(1, 2, 3, 4, 5, 6, 7))->execute();
		\DB::delete('categories')->where('id', 'IN', array(1, 2, 3, 4, 5, 6))->execute();
		\DB::delete('users')->where('id', 'IN', array(1, 2))->execute();
	}
}
