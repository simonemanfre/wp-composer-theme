<?php
?>

    </main>
    <footer class="c-site-footer">
        <div class="c-site-footer__content l-container">
            <?php if(function_exists('get_field') && get_field('contact_footer', 'option')):
                the_field('contact_footer', 'option'); 
            endif; ?>
        </div>
    </footer>

	<?php wp_footer(); ?>
	
	<?php if(function_exists('get_field') && get_field('html_footer', 'option')):
        the_field('html_footer', 'option'); 
    endif; ?>   

</body>
</html>