		<div class="content">
			
			<!-- NEWS ARTICLES -->
		
			<?php if(isset($pages)): ?>
						<div class="list-news">
							
							<h3 class="heading">Our News</h3>
				<?php foreach( $pages as $page ): ?>
				<?php if($page->friendly_title == 'news'): next($page); else: ?>
							<article>
								<h2><?php echo anchor('news/'.url_title($page->title, 'dash', TRUE), $page->title); ?></h2>
								<p class="article-date"><?php $timestamp = strtotime($page->date_created); echo date("Y.m.d", $timestamp); ?></p>
								<?php echo word_limiter($page->content, 50, '...</p>'); ?>
								<p><?php echo anchor('news/'.url_title($page->title, 'dash', TRUE), "Read More", "Class='read-more'"); ?></p>
							</article>
							
							<hr/>
			
				<?php endif; ?>		
				<?php endforeach; ?>
							<?php echo $this->pagination->create_links(); ?>
						</div>
			<?php else: ?>	
				<p>You have no news. Perhaps you should write some?</p>
			<?php endif; ?>

			<?php include('accreditation.php'); ?>
		
		</div>