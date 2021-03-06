<?php
/**
* Portfolio element actions used by Response Pro.
*
* Author: Tyler Cunningham
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response Pro
* @since 1.0
*/

/**
* response Portfolio Section actions
*/
add_action( 'response_portfolio_element', 'response_portfolio_element_content' );

function response_portfolio_element_content() {	
	global $options, $post, $themeslug, $root, $wp_query;

	$image = get_post_meta($post->ID, 'portfolio_image' , true);

	if (is_page()){
		$category = get_post_meta($post->ID, $themeslug.'_portfolio_category' , true);
		$num = get_post_meta($post->ID, $themeslug.'_portfolio_row_number' , true);
		$title_enable = get_post_meta($post->ID, $themeslug.'_portfolio_title_toggle' , true);
		$title = get_post_meta($post->ID, $themeslug.'_portfolio_title' , true);;
	} else {
		$category = $options->get($themeslug.'_portfolio_category');
		$num = $options->get($themeslug.'_portfolio_number');
		$title_enable = $options->get($themeslug.'_portfolio_title_toggle');
		$title = $options->get($themeslug.'_portfolio_title');
	}

	if ($num == '1' OR $num == 'key2') {
		$number = 'four';
		$numb = 3;
	} else if ($num == '2' OR $num == 'key3') {
		$number = 'six';
		$numb = 2;
	} else {
		$number = 'three';
		$numb = 4;
	}

	$title = ($title != '') ? $title : 'Portfolio';	
	$title_output = ($title_enable == 'on' OR $title_enable == '1') ? "<h1 class='portfolio_title'>$title</h1>" : '';
	?>

<div id="portfolio" class="container">
	<div class="row">
		
	<?php 
	$args = array( 'numberposts' => -1, 'post_type' => $themeslug.'_portfolio_images', 'portfolio_categories' => $category );
	$portfolio_posts = get_posts( $args );
	
	if ( !empty($portfolio_posts) ) :
		$out = " <div id='gallery' class='twelve columns'>$title_output<ul>"; 

		$counter = 1;
		
		foreach( $portfolio_posts as $post ) : setup_postdata($post);
			
			/* Post-specific variables */	
	    	$image = get_post_meta($post->ID, $themeslug.'_portfolio_image' , true);
	    	$title = get_the_title() ;	    
				$custom_portfolio_url_toggle = get_post_meta($post->ID, 'custom_portfolio_url_toggle' , true);
				$custom_portfolio_url = get_post_meta($post->ID, 'custom_portfolio_url' , true);
			
			/* setting variables for custom portfolio url  */
			if( $custom_portfolio_url_toggle == "on" && $custom_portfolio_url != "")
			{
				$link = $custom_portfolio_url;
				$class = "custom_portfolio_url";
			}	
			else
			{
				$link = $image;
				$class = "slide";
			}

	     	/* Markup for portfolio */
	    	$out .= "
	    		<li id='portfolio_wrap' class='$number columns $class'>
	    			<a href='$link' title='$title' class='$class'>
						<img src='$image'  alt='$title'/>
						<div class='portfolio_caption'>$title</div>
					</a>
	    		</li>";
	    	/* End slide markup */
				
				if( $counter %$numb == 0 )
					{
						$out .= "<div class='gallery-clear'></div>";
					}	
	    	
	    	$counter++;
	    endforeach; wp_reset_postdata();
	    
	    $out .= "</ul></div>";
	    	
	else:
	
		$out .= "	
	    		<div id='gallery' class='twelve columns'><ul>
	      			<li id='portfolio_wrap' class='three columns'>
	    				<a href='$root/images/pro/portfolio.jpg' title='Image 1' class='slide'><img src='$root/images/pro/portfolio.jpg'  alt='Image 1'/>
	    					<div class='portfolio_caption'>Image 1</div>
	    				</a>
	    			</li>
	    		
	  	    		<li id='portfolio_wrap' class='three columns'>
	    				<a href='$root/images/pro/portfolio.jpg' title='Image 2' class='slide'><img src='$root/images/pro/portfolio.jpg'  alt='Image 2'/>
	    					<div class='portfolio_caption'>Image 2</div>
	    				</a>
	    			</li>
	    		
					<li id='portfolio_wrap' class='three columns'>
	    				<a href='$root/images/pro/portfolio.jpg' title='Image 3' class='slide'><img src='$root/images/pro/portfolio.jpg'  alt='Image 3'/>
	    					<div class='portfolio_caption'>Image 3</div>
	    				</a>
	    			</li>
	    			<li id='portfolio_wrap' class='three columns'>
	    				<a href='$root/images/pro/portfolio.jpg' title='Image 3'><img src='$root/images/pro/portfolio.jpg'  alt='Image 3'/>
	    					<div class='portfolio_caption'>Image 3</div>
	    				</a>
	    			</li>
	    	 	</ul></div>";
	endif;
/* End slide creation */

/* Begin Portfolio javascript */ 
    
    $out .= <<<OUT
 <script type="text/javascript">
 	jQuery(document).ready(function ($) {
    $(function() {
        if( $(window).width() > 800 ) {
        $('#gallery a.slide').lightBox({
    			imageLoading:			'$root/images/portfolio/lightbox-ico-loading.gif',		
					imageBtnPrev:			'$root/images/portfolio/lightbox-btn-prev.gif',			
					imageBtnNext:			'$root/images/portfolio/lightbox-btn-next.gif',			
					imageBtnClose:			'$root/images/portfolio/lightbox-btn-close.gif',		
					imageBlank:				'$root/images/portfolio/lightbox-blank.gif'
	 			});
			}
			if( $(window).width() < 800 ) {
				$('#gallery a.slide').click(function(e){
					e.preventDefault();
				});
			}
    });
    });
    </script>
OUT;

/* End Portfolio javascript */ 

echo $out;

/* END */ 
?>
	
	</div>
</div>

<?php
}
?>