				<aside>

					<?php if(isset($sidebar_articles)): ?>
						<?php foreach ($sidebar_articles as $article): if($article['id'] != $current_article): ?>
						
						<div class="side-list">
							<div class="thumbnail">
								<?php if(isset($article['filename'])): ?><img src="<?php echo base_url('media').'/'.$article['filename'].$article['ext']; ?>" alt="" ><?php endif; ?>

							</div>						
							<h3><a href="<?php echo base_url('archive/content/'.$edition_id).'/'.$article['friendly_title']; ?>"><?php echo $article['title']; ?></a></h3>
						</div>	
					
						<?php endif; endforeach; ?>
					<?php endif; ?>
						
				</aside>
