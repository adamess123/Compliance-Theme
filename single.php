<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="span12">
			<article>
				<h1><?php the_title();?></h1>
				<?php the_content();?>
			</article>
		</div>
	</div>
	<?=get_below_the_fold();?>
<?php get_footer();?>