<?php 
	$popupDisplayStyle = 'none';
	if ($showPopup)
	{
		$popupDisplayStyle = 'block';
	}

	/*
	if (!isset($popupTitle))
	{
		$popupTitle = 'This Is My Title';
	}
	*/
	if (!isset($popupImage))
	{
		$popupImage = '';
	}
	if (!isset($popupType))
	{
		$popupType = Model_Constants::POPUP_POTION;
	}
?>
<div id="popup" class="redText" style="width:320px;padding:8px;display:<?php echo $popupDisplayStyle; ?>;top:90px;left:180px;position:absolute;z-index:10;border:2px dashed #FF0000;">
	<div style="float:right;margin-top:40px;width:210px;">
        <div style="font-size:15px;"><?php echo $popupTitle ?></div>
	</div>
	<div id="popup_body" style="float:left;width:100px;">
		<div style="margin-bottom:5px;">
			<?php echo $ascii; ?>
		</div>
		<div>
			<div style="margin-left:130px;float:left;padding:20px;">
				<a onclick="popup_continue();return false;" href="#" class="redText">CONTINUE</a>
			</div>
		</div>
	</div>
</div>