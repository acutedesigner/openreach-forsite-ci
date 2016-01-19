			</section>									
	
			<div class="push"></div>
		</div>
		
		<footer>
			<div class="container">
				<ul>
					<li><a href="http://www.openreach.co.uk" target="_blank">Openreach.co.uk</a></li>
					<li><a href=" https://www.ciz-openreach.co.uk/" target="_blank">CIZ</a></li>
					<li><a href="https://www.openreach.co.uk/orpg/home/aboutus/disclaimer.do" target="_blank">Disclaimer</a></li>
					<li><a href="https://www.openreach.co.uk/orpg/home/aboutus/termsandconditions.do" target="_blank">Terms &amp; Conditions</a></li>
					<li><a href="http://home.bt.com/pages/navigation/privacypolicy.html" target="_blank">Privacy Policy</a></li>
					<li><a href="mailto:customer-feedback@openreach.co.uk" target="_blank">Contact us</a></li>
					<?php if(isset($footer_menu)): ?>
						<?php foreach ($footer_menu as $result): ?>
						
							<li><a href="<?php echo base_url($result->friendly_title); ?>"><?php echo $result->title; ?></a></li>
						
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>				
			</div>
			<div class="container copyright">
				<p>&copy; British Telecommunications plc <?php echo date('YY'); ?>. All rights reserved.</p>
			</div>
		</footer>
	
	
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="<?php echo $this->template_url; ?>js/plugins.js"></script>
        <script src="<?php echo $this->template_url; ?>js/main.js"></script>
        <script src="<?php echo $this->template_url; ?>js/vendor/jquery.cookie.js"></script>
        <script src="<?php echo $this->template_url; ?>js/vendor/jquery.cookiecuttr.js"></script>
        <link rel="stylesheet" href="<?php echo $this->template_url; ?>js/vendor/cookiecuttr.css" title="Cookie Cutter" type="text/css" media="screen" charset="utf-8">
		<script>
						
		// You will need this for the menu toggle
		jQuery(document).ready(function($){
		
			$.cookieCuttr({
				cookieAnalytics: false,
				cookiePolicyLink: "/cookie-policy/",
				cookieNotificationLocationBottom: true,
				cookieMessage: 'We use cookies on this website, you can <a href="{{cookiePolicyLink}}" title="read about our cookies">read about them here</a>.',
				cookieAcceptButtonText: "CONTINUE"
			});
		
			//------ MAIN MENU ------//
			$('.to-main-nav').click(function(e) {
				e.preventDefault();
				$('.menu-container ul').slideToggle();
			});
		
		});
		
		</script>        

    </body>
</html>