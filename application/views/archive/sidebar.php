				<aside>
					<?php if(isset($sidebar_articles)): ?>
						<?php foreach ($sidebar_articles as $article): if($article['id'] != $current_article): ?>
						
						<div class="side-list">
							<div class="thumbnail">
								<?php if(isset($article['filename'])): ?><img src="<?php echo base_url('media').'/'.$article['filename'].$article['ext']; ?>" alt="" ><?php endif; ?>

							</div>						
							<?php if($article['tag_name'] !=""): ?><p class="article-tag"><?php echo $article['tag_name']; ?></p><?php endif; ?>
							<h3><a href="<?php echo base_url('archive/content/'.$edition_id).'/'.$article['friendly_title']; ?>"><?php echo $article['title']; ?></a></h3>
						</div>	
					
						<?php endif; endforeach; ?>
					<?php endif; ?>
					
					<?php if(isset($sidebar_offers)): ?>
					<!-- CURRENT OFFERS -->
					<div class="currentoffers">
						<h2>
							Current offers<br/>
							<small>and latest trial updates</small>
						</h2>
						<ul class="right_column">
							<?php foreach ($sidebar_offers as $result): ?>
							<li><a href="<?php echo base_url('archive/current_offers/'.$edition_id)."/#".$result['id'] ?>"><?php echo $result['title']; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
						
				</aside>
