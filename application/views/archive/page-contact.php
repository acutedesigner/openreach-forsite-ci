			<div class="content">
				
			<?php if(isset($page)): ?>
			
						<?php echo htmlspecialchars_decode($page->content); ?>

			<?php else: ?>
				<p>You have no contact information. Perhaps you should write some?</p>
			<?php endif; ?>
												
				<?php include('accreditation.php'); ?>
			</div>
				
