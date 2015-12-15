<!DOCTYPE HTML>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Openreach - <?= $title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon(s) in the root directory -->

	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template_assets/css/main.css" />
	    <script type="text/javascript" src="<?php echo base_url(); ?>template_assets/js/vendor/modernizr-2.8.3.min.js"></script>

<!--
        <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-XXXXXX', 'auto');
		  ga('send', 'pageview');
		</script>
-->
    </head>
    <body>
		<!--[if (gte IE 6)&(lte IE 8)]>
			<link href="<?php echo $this->template_url; ?>css/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
			<noscript><link rel="stylesheet" href="css/ie.css" /></noscript>
		<![endif]-->
        <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		<div class="wrapper">
			<header>
				<div class="container">

					<ul>
						<li>
						<a href="http://www.openreach.co.uk" target="_blank"><img class="openreach-logo" src="<?php echo $this->template_url; ?>img/openreach-logo-header.jpg" srcset="<?php echo $this->template_url; ?>img/openreach-logo-header.jpg 1x, <?php echo $this->template_url; ?>img/openreach-logo-header-2x.jpg 2x" alt="Openreach Logo" ></a></li>
						<li><div class="tab"><?php echo $edition_title ?></div></li>
					</ul>
					<a href="<?php echo base_url(); ?>"><img class="brand-logo" src="<?php echo $this->template_url; ?>img/forsite-logo.jpg" srcset="<?php echo $this->template_url; ?>img/forsite-logo.jpg 1x, <?php echo $this->template_url; ?>img/forsite-logo@2x.jpg 2x" >
</a>
				</div>
			</header>
			<div class="header-band">
				<div class="container">
					News and views
				</div>
			</div>
			<nav>
				<div class="menu-container">
					<a class="to-main-nav" href="#main-nav">Menu <i class="fa fa-bars"></i></a>
					<ul>
						<?php if(isset($articles_array)): ?>
						<li <?php if(isset($link_newsletter)): echo 'class="current-menu-parent"'; endif; ?> >
							<a href="<?php echo base_url(); ?>">Latest issue articles</a>
							<ul>
							<?php foreach ($articles_array as $article): ?>
									<li><a href="<?php echo base_url('issue-'.$latest_issue->scalar).'/'.$article['type'].'/'.$article['friendly_title']; ?>"><?php echo $article['title']; ?></a></li>
							<?php endforeach; ?>
							</ul>

						</li>
						<?php endif; ?>
						<?php if(isset($previous_issues_menu)): ?>
						<li>
							<a href="#">Previous issues</a>
							<ul>
							<?php foreach ($previous_issues_menu as $key): ?>
								<li>
									<a href="<?php echo base_url('issue-'.$key['ancestor']['issue'])?>"><?php echo $key['ancestor']['title']; ?></a>
									<ul>
									<?php foreach($key['articles'] as $article): ?>
										<li><a href="<?php echo base_url('issue-'.$key['ancestor']['issue']).'/'.$article['type'].'/'.$article['friendly_title']; ?>"><?php echo $article['title']; ?></a></li>
									<?php endforeach; ?>
									</ul>
								</li>

							<?php endforeach; ?>
							</ul>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</nav>
			<section class="container">
