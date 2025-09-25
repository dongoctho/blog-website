<?php
use Fuel\Core\Arr;
use Fuel\Core\Uri;
use Fuel\Core\Input;
?>
<?php if (!empty($categories)): ?>
<div class="container mb-5">
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<h3 class="text-center mb-4 fw-bold text-dark">Danh mục bài viết</h3>
			<div class="category-grid" id="categoryGrid">
				<?php 
					$selected = (int)($category_id ?? 0); 
					$index = 0; 
					$colorPalettes = [
						['#ffb3ba', '#ff9a9e'], // Light orange/pink
						['#bae1ff', '#a8edea'], // Light blue
						['#ffb3e6', '#ff9a9e'], // Light pink
						['#ffffba', '#ffecd2'], // Light yellow
						['#baffc9', '#a8edea'], // Light teal-green
						['#baffc9', '#a8edea'], // Light green
						['#ffb3e6', '#e6b3ff'], // Light purple-pink
						['#e6b3ff', '#d1b3ff'], // Light lavender
						['#ffd1dc', '#ffb3ba'], // Soft pink
						['#c7ceea', '#b3d9ff'], // Soft blue
						['#f0e6ff', '#e6d1ff'], // Soft purple
						['#fff0e6', '#ffe6cc'], // Soft orange
						['#e6f3ff', '#d1e7ff'], // Soft sky blue
						['#f0fff0', '#e6ffe6'], // Soft mint
						['#ffe6f0', '#ffd1e6'], // Soft rose
						['#f5f0ff', '#e6d1ff'], // Soft violet
					];
				?>
				<?php foreach ($categories as $cat): ?>
					<?php 
						$index++; 
						$colorIndex = ($cat->id - 1) % count($colorPalettes);
						$colors = $colorPalettes[$colorIndex];
						$hoverColors = [
							$colors[0], // Màu hover nhẹ nhàng
							$colors[1]
						];
						$activeColors = [
							$colors[0], // Màu active
							$colors[1]
						];
					?>
					<button type="button" 
						class="category-tile <?php echo ($selected === (int)$cat->id) ? 'active' : ''; ?>" 
						data-category-id="<?php echo (int)$cat->id; ?>"
						style="display: <?php echo $index <= 4 ? 'flex' : 'none'; ?>; background: #ffffff; border: 2px solid <?php echo $colors[0]; ?>; color: #333;"
						data-colors='<?php echo json_encode($colors); ?>'
						data-hover-colors='<?php echo json_encode($hoverColors); ?>'
						data-active-colors='<?php echo json_encode($activeColors); ?>'>
						<span class="category-name"><?php echo e($cat->name); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
			<?php if (count($categories) > 4): ?>
				<div class="text-center mt-3">
					<button type="button" class="btn btn-outline-primary btn-sm" id="loadMoreCategories">
						<i class="bi bi-plus-circle"></i> Xem thêm danh mục
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (!empty($featured_posts)): ?>
<div class="container mb-5">
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<h3 class="text-center mb-4 fw-bold text-dark">
				<i class="bi bi-star-fill text-warning me-2"></i>Bài viết nổi bật
			</h3>
			<div class="row">
				<?php foreach ($featured_posts as $post): ?>
					<div class="col-lg-3 col-md-6 mb-4">
						<div class="card h-100 featured-post-card clickable-card" onclick="window.location.href='<?php echo Uri::create('post/view/' . $post->id); ?>'">
							<?php if (!empty($post->images)): ?>
								<div class="card-img-top clickable-image" style="height: 180px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('<?php if ($img = reset($post->images)) echo $img->get_url(); ?>') center/cover; cursor: pointer; border-radius: 13px 13px 0 0;">
									<div class="position-absolute top-0 start-0 m-2">
										<span class="badge bg-warning text-dark">
											<i class="bi bi-eye"></i> <?php echo number_format($post->views ?? 0); ?>
										</span>
									</div>
									<div class="position-absolute top-0 end-0 m-2">
										<span class="badge bg-primary">Nổi bật</span>
									</div>
								</div>
							<?php else: ?>
								<div class="card-img-top bg-gradient clickable-image" style="height: 180px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); cursor: pointer;">
									<div class="d-flex align-items-center justify-content-center h-100">
										<i class="bi bi-star-fill text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
									</div>
									<div class="position-absolute top-0 start-0 m-2">
										<span class="badge bg-warning text-dark">
											<i class="bi bi-eye"></i> <?php echo number_format($post->views ?? 0); ?>
										</span>
									</div>
									<div class="position-absolute top-0 end-0 m-2">
										<span class="badge bg-primary">Nổi bật</span>
									</div>
								</div>
							<?php endif; ?>
							
							<div class="card-body d-flex flex-column">
								<h6 class="card-title fw-bold">
									<a href="<?php echo Uri::create('post/view/' . $post->id); ?>" 
									   class="text-decoration-none text-dark stretched-link"
									   style="cursor: pointer;">
										<?php echo e($post->title); ?>
									</a>
								</h6>
								
								<div class="post-meta mb-2" style="position: relative; z-index: 2;">
									<small class="text-muted">
										<i class="bi bi-person-circle"></i> <?php echo e($post->user->username ?? 'Anonymous'); ?>
										<span class="mx-1">•</span>
										<i class="bi bi-calendar-date"></i> <?php echo date('d/m/Y', strtotime($post->created_at)); ?>
									</small>
								</div>

								<div class="d-flex justify-content-between align-items-center mt-auto" style="position: relative; z-index: 3;">
									<small class="text-muted">
										<i class="bi bi-eye"></i> <?php echo number_format($post->views ?? 0); ?>
									</small>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- Search Section -->
<div class="search-wrapper mb-4">
	<form method="GET" action="<?php echo Uri::create('post'); ?>" class="search-form" id="filterForm">
		<div class="input-group">
			<input type="text" class="form-control" name="search" 
					placeholder="Tìm kiếm bài viết..." id="searchInput"
					value="<?php echo e(Input::get('search', '')); ?>">
			<input type="hidden" name="category" id="categoryInput" value="<?php echo (int)($category_id ?? 0); ?>">
			<button class="btn btn-outline-secondary" type="submit">
				<i class="bi bi-search"></i>
			</button>
		</div>
	</form>
</div>

<?php if (!empty($posts)): ?>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<h3 class="text-center mb-4 fw-bold text-dark">
				<i class="bi bi-newspaper me-2"></i>Tất cả bài viết
			</h3>
			<div class="row">
				<?php foreach ($posts as $post): ?>
					<div class="col-lg-3 col-md-6 mb-4">
						<div class="card h-100 post-card clickable-card" onclick="window.location.href='<?php echo Uri::create('post/view/' . $post->id); ?>'">
							<?php if (!empty($post->images)): ?>
								<div class="card-img-top clickable-image" style="height: 200px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('<?php if ($img = reset($post->images)) echo $img->get_url(); ?>') center/cover; cursor: pointer; border-radius: 13px 13px 0 0;">
									<div class="position-absolute top-0 start-0 m-2">
										<span class="category-badge">
											<?php 
											$categories = $post->get_categories();
											if (!empty($categories)) {
												echo e($categories[0]->name);
												if (count($categories) > 1) {
													echo ' +' . (count($categories) - 1);
												}
											} else {
												echo 'Không có danh mục';
											}
											?>
										</span>
									</div>
								</div>
							<?php else: ?>
								<div class="card-img-top bg-gradient clickable-image" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;">
									<div class="d-flex align-items-center justify-content-center h-100">
										<i class="bi bi-file-text text-white" style="font-size: 3rem; opacity: 0.7;"></i>
									</div>
									<div class="position-absolute top-0 start-0 m-2">
										<span class="category-badge">
											<?php 
											$categories = $post->get_categories();
											if (!empty($categories)) {
												echo e($categories[0]->name);
												if (count($categories) > 1) {
													echo ' +' . (count($categories) - 1);
												}
											} else {
												echo 'Không có danh mục';
											}
											?>
										</span>
									</div>
								</div>
							<?php endif; ?>
							
							<div class="card-body d-flex flex-column">
								<h5 class="card-title">
									<a href="<?php echo Uri::create('post/view/' . $post->id); ?>" 
									   class="text-decoration-none text-dark fw-bold stretched-link"
									   style="cursor: pointer;">
										<?php echo e($post->title); ?>
									</a>
								</h5>
								
								<div class="post-meta mb-2" style="position: relative; z-index: 2;">
									<small>
										<i class="bi bi-person-circle"></i> <?php echo e($post->user->username ?? 'Anonymous'); ?>
										<span class="mx-2">•</span>
										<i class="bi bi-calendar-date"></i> <?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?>
										<span class="mx-2">•</span>
										<i class="bi bi-eye"></i> <?php echo number_format($post->views ?? 0); ?>
										<?php if (!empty($post->images)): ?>
											<span class="mx-2">•</span>
											<i class="bi bi-images"></i> <?php echo count($post->images); ?> ảnh
										<?php endif; ?>
									</small>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (isset($pagination)): ?>
				<div class="d-flex justify-content-center mt-4">
					<?php echo htmlspecialchars_decode($pagination); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php else: ?>
	<div class="text-center py-5">
		<div class="mb-4">
			<i class="bi bi-file-earmark-text" style="font-size: 4rem; color: #6c757d; opacity: 0.5;"></i>
		</div>
		<h4 class="text-muted">Chưa có bài viết nào!</h4>
	</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const searchInput = document.getElementById('searchInput');
	const searchForm = document.getElementById('filterForm');
	const postCards = document.querySelectorAll('.post-card');
	const categoryTiles = document.querySelectorAll('.category-tile');
	const categoryInput = document.getElementById('categoryInput');
	const loadMoreBtn = document.getElementById('loadMoreCategories');
	let shownCount = 4;
	
	// Enter key để submit form
	searchInput.addEventListener('keypress', function(e) {
		if (e.key === 'Enter') {
			searchForm.submit();
		}
	});

	// Chọn danh mục để filter
	categoryTiles.forEach(function(tile) {
		const colors = JSON.parse(tile.getAttribute('data-colors'));
		const hoverColors = JSON.parse(tile.getAttribute('data-hover-colors'));
		const activeColors = JSON.parse(tile.getAttribute('data-active-colors'));
		
		// Hover effects
		tile.addEventListener('mouseenter', function() {
			if (!this.classList.contains('active')) {
				this.style.borderColor = hoverColors[0];
				this.style.borderWidth = '3px';
			}
		});
		
		tile.addEventListener('mouseleave', function() {
			if (!this.classList.contains('active')) {
				this.style.borderColor = colors[0];
				this.style.borderWidth = '2px';
			}
		});
		
		// Click events
		tile.addEventListener('click', function() {
			const id = this.getAttribute('data-category-id');
			if (categoryInput.value == id) {
				// Bỏ chọn nếu click lại cùng danh mục
				categoryInput.value = '';
				this.classList.remove('active');
				this.style.background = '#ffffff';
				this.style.borderColor = colors[0];
				this.style.borderWidth = '2px';
				this.style.color = '#333';
			} else {
				categoryTiles.forEach(t => {
					const tColors = JSON.parse(t.getAttribute('data-colors'));
					t.classList.remove('active');
					t.style.background = '#ffffff';
					t.style.borderColor = tColors[0];
					t.style.borderWidth = '2px';
					t.style.color = '#333';
				});
				categoryInput.value = id;
				this.classList.add('active');
				this.style.background = `linear-gradient(135deg, ${activeColors[0]}, ${activeColors[1]})`;
				this.style.borderColor = 'transparent';
				this.style.color = '#fff';
			}
			searchForm.submit();
		});
	});

	// Load more categories
	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', function() {
			const tiles = Array.from(document.querySelectorAll('.category-tile'));
			const nextTiles = tiles.slice(shownCount, shownCount + 4);
			nextTiles.forEach(tile => {
				tile.style.display = 'flex';
				// Re-apply event listeners for new tiles
				const colors = JSON.parse(tile.getAttribute('data-colors'));
				const hoverColors = JSON.parse(tile.getAttribute('data-hover-colors'));
				const activeColors = JSON.parse(tile.getAttribute('data-active-colors'));
				
				// Remove existing listeners to avoid duplicates
				tile.replaceWith(tile.cloneNode(true));
				const newTile = document.querySelector(`[data-category-id="${tile.getAttribute('data-category-id')}"]`);
				
				// Add new event listeners
				newTile.addEventListener('mouseenter', function() {
					if (!this.classList.contains('active')) {
						this.style.borderColor = hoverColors[0];
						this.style.borderWidth = '3px';
					}
				});
				
				newTile.addEventListener('mouseleave', function() {
					if (!this.classList.contains('active')) {
						this.style.borderColor = colors[0];
						this.style.borderWidth = '2px';
					}
				});
				
				newTile.addEventListener('click', function() {
					const id = this.getAttribute('data-category-id');
					if (categoryInput.value == id) {
						categoryInput.value = '';
						this.classList.remove('active');
						this.style.background = '#ffffff';
						this.style.borderColor = colors[0];
						this.style.borderWidth = '2px';
						this.style.color = '#333';
					} else {
						categoryTiles.forEach(t => {
							const tColors = JSON.parse(t.getAttribute('data-colors'));
							t.classList.remove('active');
							t.style.background = '#ffffff';
							t.style.borderColor = tColors[0];
							t.style.borderWidth = '2px';
							t.style.color = '#333';
						});
						categoryInput.value = id;
						this.classList.add('active');
						this.style.background = `linear-gradient(135deg, ${activeColors[0]}, ${activeColors[1]})`;
						this.style.borderColor = 'transparent';
						this.style.color = '#fff';
					}
					searchForm.submit();
				});
			});
			shownCount += nextTiles.length;
			if (shownCount >= tiles.length) {
				loadMoreBtn.style.display = 'none';
			}
		});
	}
	
	postCards.forEach(function(card) {
		card.addEventListener('mouseenter', function() {
			this.style.transform = 'translateY(-5px)';
		});
		
		card.addEventListener('mouseleave', function() {
			this.style.transform = 'translateY(0)';
		});
	});
});
</script>