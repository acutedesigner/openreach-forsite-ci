				<article>
					<h1>Current offers</h1>

					<!-- CURRENT OFFERS -->

				<?php if(isset($current_offers)): ?>
					<?php foreach ($current_offers as $result): ?>

					<div class="currentoffers">
					<a name="<?php echo $result['id']; ?>"></a>
						<header>
							<?php if(isset($result['filename'])): ?><img src="<?php echo base_url('media').'/'.$result['filename'].$result['ext']; ?>" alt="" ><?php endif; ?>
							<h2><?php echo $result['title']; ?></h2>
						</header>						
						
						<?php echo $result['content']; ?>
						
					</div>
				
					<?php endforeach; ?>
				<?php endif; ?>

					<div class="info-box">
						<img src="<?php echo $this->template_url; ?>img/info-qmark.png" alt="info-qmark" width="66" height="66">
						<p>If you’re interested in any of these offers or invitations your <strong>Sales &amp; Relationship Manager</strong> can help. Relevant terms and conditions are also published on the Openreach portal.</p>
					</div>

				</article>
