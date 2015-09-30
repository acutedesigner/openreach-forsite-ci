<!-- INTRO ROW -->

<!-- MAIN COLUMN -->

<div class="row">

<div class="eight columns">
	<div class="cream-block">

<?php if(isset($page)): ?>

			<h1><?php echo $page->title; ?></h1>
			<p class="date"><?php echo date("Y.m.d", strtotime($page->created)); ?></p>
			<?php echo $page->content; ?>
	
<?php else: ?>
	<p>You have no news. Perhaps you should write some?</p>
<?php endif; ?>
		
	<ul class="block-grid mobile three-up">
		<li><div class="services-link"><a href="#"><img src="http://placehold.it/190x120">New services for products</a></div></li>
		<li><div class="services-link"><a href="#"><img src="http://placehold.it/190x120">New services for products</a></div></li>
		<li><div class="services-link"><a href="#"><img src="http://placehold.it/190x120">New services for products</a></div></li>
		<li><div class="services-link"><a href="#"><img src="http://placehold.it/190x120">New services for products</a></div></li>
		<li><div class="services-link"><a href="#"><img src="http://placehold.it/190x120">New services for products</a></div></li>
		<li><div class="services-link"><a href="#"><img src="http://placehold.it/190x120">New services for products</a></div></li>
	</ul>

	</div>
</div>