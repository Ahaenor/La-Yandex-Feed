<?php
/**
 * RSS2 Feed Template for Yandex.News translation.
 *
 * @package WordPress
 */
 
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?>';
?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media
="http://search.yahoo.com/mrss/" version="2.0">
<channel>
<title><?php bloginfo_rss('name');?></title>
<link><?php bloginfo_rss('url') ?></link>
<description><?php bloginfo_rss("description") ?></description>
<?php
	$logo = get_option('layf_feed_logo', '');
	if(!empty($logo)):
?>
<image>
<url><?php echo esc_url($logo);?></url>
<title><?php bloginfo_rss('name');?></title>
<link><?php bloginfo_rss('url') ?></link>
</image>
<?php endif;?>
<?php while( have_posts()) : the_post(); ?>
<item>
<title><?php the_title_rss() ?></title>
<link><?php the_permalink_rss() ?></link>
<pdalink><?php the_permalink_rss() ?></pdalink>
<description><?php the_excerpt_rss(); ?></description>
<?php
	$layf_author = apply_filters('layf_author', get_the_author()); 
	if(!empty($layf_author)):
?>
<author><?php echo $layf_author; ?></author>
<?php endif;?>
<?php
	$layf_category = apply_filters('layf_category', get_the_category_list(','));
	if(!empty($layf_category)):
?>
<category><?php echo $layf_category;?></category>
<?php endif;?>
<?php
	$enclosure = La_Yandex_Feed_Core::item_enclosure();
	if(!empty($enclosure)): foreach($enclosure as $i => $img):
?>
<enclosure url="<?php echo esc_url($img['url']);?>" type="<?php echo esc_attr($img['mime']);?>"/>
<?php endforeach; endif;?>
<?php
	$related = La_Yandex_Feed_Core::item_related();
	if(!empty($related)):
?>
<yandex:related>
<?php foreach($related as $i => $link): ?>
<link url="<?php echo esc_url($link['url']);?>"><?php echo apply_filters('layf_related_link_text', $link['text']);?></link>
<?php endforeach;?>
</yandex:related>	
<?php	
	endif;
	
	$gmt_offset = get_option('gmt_offset');
	$gmt_offset = ($gmt_offset > 9) ? $gmt_offset.'00' : ('0'.$gmt_offset.'00');
?>
<pubDate><?php echo mysql2date('D, d M Y H:i:s +'.$gmt_offset, get_date_from_gmt(get_post_time('Y-m-d H:i:s', true)), false); ?></pubDate>
<yandex:full-text><?php the_content_feed('rss2'); ?></yandex:full-text>
</item>
<?php endwhile; ?>
</channel>
</rss>