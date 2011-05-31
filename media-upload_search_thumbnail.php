<?php if(!class_exists('FlickrPress')) die('Not initialize FlickrPress.');

wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');

add_action('admin_head', 'fp_add_scripts');

$body_id = 'media-upload';
wp_iframe('media_upload_search_form');

function fp_add_scripts() {
	echo "\n<link rel='stylesheet' href='".FlickrPress::getPluginUrl()."/css/media-upload_search_thumbnail.css?".time()."' media='all' type='text/css'/>";
	echo "\n<link rel='stylesheet' href='".FlickrPress::getPluginUrl()."/css/jquery.tag.css?".time()."' media='all' type='text/css'/>";
	echo "\n<script type='text/javascript' src='".FlickrPress::getPluginUrl()."/js/jquery.tag.js?".time()."'></script>";
	echo "\n<script type='text/javascript'>tb_pathToImage = '../../../wp-includes/js/thickbox/loadingAnimation.gif'; tb_closeImage='../../../wp-includes/js/thickbox/tb-close.png';</script>";

	echo "\n<script type='text/javascript' src='".FlickrPress::getPluginUrl()."/js/jquery.md5.js?".time()."'></script>";
	echo "\n<script type='text/javascript' src='".FlickrPress::getPluginUrl()."/js/jquery.flickr-client.js?".time()."'></script>";
	echo "\n<script type='text/javascript' src='".FlickrPress::getPluginUrl()."/js/search_thumbnail_ui.js?".time()."'></script>";
}

function media_upload_search_form() {
	$alignes = array(
		'None'=>'none',
		'Left'=>'left',
		'Center'=>'center',
		'Right'=>'right',
	);
	$targets = array(
		'None'=>'',
		'New Window'=>'_blank',
	);
	$sorts = array(
		'Posted ASC' => 'date-posted-asc',
		'Posted DESC' => 'date-posted-desc',
		'Taken ASC' => 'date-taken-asc',
		'Taken DESC' => 'date-taken-desc',
		'Interestingness ASC' => 'interestingness-asc',
		'Interestingness DESC' => 'interestingness-desc',
	);
	
	$sort = FlickrPress::getDefaultSort();
?>

<?php include_once dirname(__FILE__).'/inc.header_tab.php' ?>

<div id="params" style="display: none;">
	<input type="hidden" name="api_key" id="api_key" value="<?php echo FlickrPress::getApiKey() ?>" />
	<input type="hidden" name="api_secret" id="api_secret" value="<?php echo FlickrPress::getApiSecret() ?>" />
	<input type="hidden" name="user_id" id="user_id" value="<?php echo FlickrPress::getUserId() ?>" />
	<input type="hidden" name="oauth_token" id="oauth_token" value="<?php echo FlickrPress::getOAuthToken() ?>" />
</div>

<div id="search-form">
	<input type="hidden" name="post_id" id="post_id" value="<?php echo $_GET['post_id'] ?>" />
	<input type="hidden" name="type" id="type" value="<?php echo $_GET['type'] ?>" />
	<input type="hidden" name="mode" id="mode" value="<?php echo $_GET['mode'] ?>" />
	<input type="hidden" name="TB_iframe" id="TB_iframe" value="<?php echo $_GET['TB_iframe'] ?>" />
	
	<div id="search-header">
		<p>
			<input type="radio" name="filter[type]" value="recent" class="search-type" id="filter-type-recent" checked="checked"/><label for="filter-type-recent"><?php echo __('Recent upload') ?></label>
			<input type="radio" name="filter[type]" value="photosets" class="search-type" id="filter-type-photosets"/><label for="filter-type-photosets"><?php echo __('Photosets') ?></label>
			<input type="radio" name="filter[type]" value="advanced" class="search-type" id="filter-type-advanced"/><label for="filter-type-advanced"><?php echo __('Advanced') ?></label>
		</p>
		<div id="sort-search-form">
			<p class="field-row">
				<span class="field-label"><?php echo __('Sort:') ?></span>
				<select name="filter[sort]">
				<?php foreach ($sorts as $name => $val) { ?>
					<option value="<?php echo $val ?>" <?php if ($val==$sort) {echo 'selected="selected"';} ?>><?php echo $name ?></option>
				<?php } ?>
				</select>
			</p>
		</div>
		<div id="advanced-search-form" class="search-form-off">
			<p class="field-row"><span class="field-label"><?php echo __('Keyword:') ?></span><input type="text" name="filter[keyword]" value="<?php echo $filter['keyword'] ?>" size="50"/></p>
			<p class="field-row"><span class="field-label"><?php echo __('Tags:') ?></span><input type="text" name="filter[tags]" value="<?php echo $filter['tags'] ?>" size="50" id="filter-tags" autocomplete="off"/></p>
		</div>
		<div id="photosets-search-form" class="search-form-off">
			<p class="field-row"><span class="field-label"><?php echo __('Photosets:') ?></span>
				<select name="filter[photoset]">
					<?php foreach ($photosets['photoset'] as $photoset) { ?>
						<?php $selected = $filter['photoset'] == $photoset['id'] ? " selected='selected'" : ""; ?>
						<option value="<?php echo $photoset['id'] ?>"<?php echo $selected ?>><?php echo $photoset['title'] ?></option>
					<?php } ?>
				</select>
			</p>
		</div>

		<p><input type="button" value="<?php echo __('Search') ?>" class="button" id="search-btn"/></p>

	</div>
	
	<div class="pager-container clearfix"></div>
	<div id="search-results"></div>
	<br clear="all"/>
	<div class="pager-container"></div>
	
<div id="inline-settings-content-container" style="display: none;">
	<table class="describe">
<?php /*
		<thead class="media-item-info" id="media-head-<?php echo $photo['id'] ?>">
			<tr valign="top">
				<td class="A1B1"
					<p><a href="#"><img id="inline-thumbnail" class="thumbnail" src=""/></a></p>
				</td>
				<td>
					<p><strong><?php echo __('ID:') ?><span id="inline-id">[ID]</span></strong></p>
					<p><strong><?php echo __('File name:') ?><span id="inline-filename">[FILENAME]</span></strong></p>
				</td>
			</tr>
		</thead>
*/ ?>
		<tbody>
			<tr class="post_title">
				<th valign="top" scope="row" class="label"><label for="inline-title"><span class="alignleft"><?php echo __('Title')?></span><span class="alignright"><br class="clear"></label></th>
				<td class="field"><input type="text" class="text" id="inline-title" name="inline-title" value="" aria-required="true"></td>
			</tr>
			<tr class="url">
				<th valign="top" scope="row" class="label"><label for="inline-url"><span class="alignleft"><?php echo __('Link URL')?></span><br class="clear"></label></th>
				<td class="field">
					<input type="text" id="inline-url" class="text urlfield" name="inline-url" value=""><br>
					<button type="button" class="button urlnone" title=""><?php echo __('None') ?></button>
					<button type="button" class="button urlfile" id="inline-url-file" title=""><?php echo __('File URL') ?></button>
					<button type="button" class="button urlpage" id="inline-url-page" title=""><?php echo __('Page URL') ?></button>
					<p class="help"><?php echo __('Enter a link URL or click above for presets.') ?></p>
				</td>
			</tr>
			<tr class="target">
				<th valign="top" scope="row" class="label"><label for="inline-target"><span class="alignleft"><?php echo __('Link Target')?></span><br class="clear"></label></th>
				<td class="field">
					<?php foreach ($targets as $label => $target) { ?>
					<?php $checked = FlickrPress::getDefaultTarget()==$target ? " checked='checked'" : '' ?>
						<input type="radio" name="inline-target" id="inline-link-target-<?php echo $target ?>" value="<?php echo $target ?>" <?php echo $checked ?>/><label for="inline-link-target-<?php echo $target ?>" class="link-target-<?php echo $target ?>-label"><?php echo __($label) ?></label>
					<?php } ?>
				</td>
			</tr>
			<tr class="align">
				<th valign="top" scope="row" class="label"><label for="inline-align"><span class="alignleft"><?php echo __('Alignment')?></span><br class="clear"></label></th>
				<td class="field">
					<?php foreach ($alignes as $label => $align) { ?>
						<?php $checked = FlickrPress::getDefaultAlign()==$align ? " checked='checked'" : '' ?>
						<input type="radio" name="inline-align" id="inline-image-align-<?php echo $align ?>" value="<?php echo $align ?>"<?php echo $checked?> /><label for="inline-image-align-<?php echo $align ?>" class="align image-align-<?php echo $align ?>-label"/><?php echo __($label) ?></label>
					<?php } ?>
				</td>
			</tr>
			<tr class="image-size">
				<th valign="top" scope="row" class="label"><label for=""><span class="alignleft"><?php echo __('Size')?></span><br class="clear"></label></th>
				<td class="field">
					<?php foreach(FlickrPress::$SIZES as $size => $url) { ?>
						<?php $checked = FlickrPress::getDefaultSize()==$size ? " checked='checked'" : '' ?>
						<div class="image-size-item"><input name="inline-image-size" value="<?php echo $photo[$url] ?>" type="radio" id="inline-image-size-<?php echo $size ?>"<?php echo $checked?>/><label for="inline-image-size-<?php echo $size ?>"><?php echo __(FlickrPress::$SIZE_LABELS[$size]) ?></label></div>
					<?php } ?>
				</td>
			</tr>
			<tr class="submit">
				<td></td>
				<td class="savesend">
					<input type="submit" id="inline-ins-btn" class="button" name="send" value="<?php echo __('Insert into Post')?>">
				</td>
			</tr>
		</tbody>
	</table>
</div>

</div>
	
</form>

<?php
}