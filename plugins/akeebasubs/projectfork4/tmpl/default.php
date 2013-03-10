<?php defined('_JEXEC') or die();

$opts[] = JHTML::_('select.option', '0', JText::_('JNo'));
$opts[] = JHTML::_('select.option', '1', JText::_('JYes'));
?>
<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label for="params_cb_autoauthids" class="control-label">
				<?php echo JText::_('PLG_AKEEBASUBS_PROJECTFORK4_ADD_TITLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->getSelectField($level, 'add') ?>
				<span class="help-block">
					<?php echo JText::_('PLG_AKEEBASUBS_PROJECTFORK4_ADD_DESCRIPTION2') ?>
				</span>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label for="params_cb_autoauthdeids" class="control-label">
				<?php echo JText::_('PLG_AKEEBASUBS_PROJECTFORK4_REMOVE_TITLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->getSelectField($level, 'remove') ?>
				<span class="help-block">
					<?php echo JText::_('PLG_AKEEBASUBS_PROJECTFORK4_REMOVE_DESCRIPTION2') ?>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="alert alert-warning">
	<p><?php echo JText::_('PLG_AKEEBASUBS_PROJECTFORK4_USAGENOTE'); ?></p>
</div>