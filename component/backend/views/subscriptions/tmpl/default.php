<?php
/**
 *  @package AkeebaSubs
 *  @copyright Copyright (c)2010-2011 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

FOFTemplateUtils::addCSS('media://com_akeebasubs/css/backend.css?'.AKEEBASUBS_VERSIONHASH);
FOFTemplateUtils::addJS('media://com_akeebasubs/js/backend.js?'.AKEEBASUBS_VERSIONHASH);
FOFTemplateUtils::addJS('media://com_akeebasubs/js/akeebajq.js?'.AKEEBASUBS_VERSIONHASH);
FOFTemplateUtils::addJS('media://com_akeebasubs/js/blockui.js?'.AKEEBASUBS_VERSIONHASH);

JHTML::_('behavior.tooltip');

$this->loadHelper('select');
$this->loadHelper('format');
?>
<form action="index.php" method="post" name="adminForm">
<input type="hidden" name="option" value="com_akeebasubs" />
<input type="hidden" name="view" value="subscriptions" />
<input type="hidden" id="task" name="task" value="com_akeebasubs" />
<input type="hidden" name="hidemainmenu" id="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>" />
<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>" />
<input type="hidden" name="<?php echo JUtility::getToken();?>" value="1" />

<table class="adminlist">
	<thead>
		<tr>
			<th width="16px"></th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_COMMON_ID', 'akeebasubs_subscription_id', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTIONS_LEVEL', 'akeebasubs_level_id', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTIONS_USER', 'user_id', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="30px">
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTIONS_STATE', 'state', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="60px">
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTIONS_AMOUNT', 'gross_amount', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="120px">
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTIONS_PUBLISH_UP', 'publish_up', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="120px">
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTIONS_PUBLISH_DOWN', 'publish_down', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="120px">
				<?php echo JHTML::_('grid.sort', 'COM_AKEEBASUBS_SUBSCRIPTION_CREATED_ON', 'created_on', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="8%">
				<?php if(version_compare(JVERSION,'1.6.0','ge')):?>
				<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'enabled', $this->lists->order_Dir, $this->lists->order); ?>
				<?php else: ?>
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'enabled', $this->lists->order_Dir, $this->lists->order); ?>
				<?php endif; ?>
			</th>
		</tr>
		<tr>
			<td>
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ) + 1; ?>);" />
			</td>
			<td></td>
			<td>
				<?php echo AkeebasubsHelperSelect::subscriptionlevels($this->getModel()->getState('level',''), 'level', array('onchange'=>'this.form.submit();')) ?>
			</td>
			<td>
				<input type="text" name="search" id="search"
					value="<?php echo $this->escape($this->getModel()->getState('search',''));?>"
					class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();">
					<?php echo JText::_('Go'); ?>
				</button>
				<button onclick="document.adminForm.search.value='';this.form.submit();">
					<?php echo JText::_('Reset'); ?>
				</button>
			</td>
			<td>
				<?php echo AkeebasubsHelperSelect::paystates($this->getModel()->getState('paystate',''), 'paystate', array('onchange'=>'this.form.submit();')) ?>
				
				<input type="text" name="paykey" id="paykey"
					value="<?php echo $this->escape($this->getModel()->getState('paykey',''));?>"
					class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();">
					<?php echo JText::_('Go'); ?>
				</button>
				<button onclick="document.adminForm.paykey.value='';this.form.submit();">
					<?php echo JText::_('Reset'); ?>
				</button>
			</td>
			<td></td>
			<td><?php echo JHTML::_('calendar', $this->getModel()->getState('publish_up',''), 'publish_up', 'publish_up', '%Y-%m-%d', array('onchange' => 'this.form.submit();')); ?></td>
			<td><?php echo JHTML::_('calendar', $this->getModel()->getState('publish_down',''), 'publish_down', 'publish_down', '%Y-%m-%d', array('onchange' => 'this.form.submit();')); ?></td>
			<td><?php echo JHTML::_('calendar', $this->getModel()->getState('since',''), 'since', 'since', '%Y-%m-%d', array('onchange' => 'this.form.submit();')); ?></td>
			<td>
				<?php echo AkeebasubsHelperSelect::published($this->getModel()->getState('enabled',''), 'enabled', array('onchange'=>'this.form.submit();')) ?>
			</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="20">
				<?php if($this->pagination->total > 0) echo $this->pagination->getListFooter() ?>	
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php if(count($this->items)): ?>
		<?php $m = 1; $i = 0; ?>
		<?php foreach($this->items as $subscription):?>
		<?php
			$m = 1 - $m;
			$email = trim($subscription->email);
			$email = strtolower($email);
			$gravatarHash = md5($email);
			$rowClass = ($subscription->enabled) ? '' : 'expired';
			$subscription->published = $subscription->enabled;
			
			$users = FOFModel::getTmpInstance('Users','AkeebasubsModel')
				->user_id($subscription->user_id)
				->getList();
			if(empty($users)) {
				$user_id = 0;
			} else {
				foreach($users as $user) {
					$user_id = $user->akeebasubs_user_id;
					break;
				}
			}
		?>
		<tr class="row<?php echo $m?> <?php echo $rowClass?>">
			<td align="center">
				<?php echo JHTML::_('grid.id', $i, $subscription->akeebasubs_subscription_id); ?>
			</td>
			<td align="left">
				<span class="editlinktip hasTip" title="#<?php echo (int)$subscription->akeebasubs_subscription_id?>::<?php echo JText::_('COM_AKEEBASUBS_SUBSCRIPTION_EDIT_TOOLTIP')?>">
					<a href="index.php?option=com_akeebasubs&view=subscription&id=<?php echo $subscription->akeebasubs_subscription_id ?>" class="title">
						<strong><?php echo sprintf('%05u', (int)$subscription->akeebasubs_subscription_id)?></strong>
	    			</a>
    			</span>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo $this->escape($subscription->title); ?>::<?php echo JText::_('COM_AKEEBASUBS_SUBSCRIPTION_LEVEL_EDIT_TOOLTIP')?>">
					<img src="<?php echo JURI::base(); ?><?php echo version_compare(JVERSION,'1.6.0','ge') ? '../images/' :'../images/stories/' ?><?php echo $subscription->image;?>" width="32" height="32" class="sublevelpic" />
					<a href="index.php?option=com_akeebasubs&id=<?php echo $subscription->akeebasubs_level_id ?>" class="subslevel">
    					<?php echo $this->escape($subscription->title)?>
    				</a>
    			</span>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo $this->escape($subscription->username) ?>::<?php echo JText::_('COM_AKEEBASUBS_SUBSCRIPTION_USER_EDIT_TOOLTIP')?>">
					<?php if(AkeebasubsHelperCparams::getParam('gravatar')):?>
						<?php if(JURI::getInstance()->getScheme() == 'http'): ?>
							<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($subscription->email))?>.jpg?s=32&d=mm" align="left" class="gravatar"  />
						<?php else: ?>
							<img src="https://secure.gravatar.com/avatar/<?php echo md5(strtolower($subscription->email))?>.jpg?s=32&d=mm" align="left" class="gravatar"  />
						<?php endif; ?>
					<?php endif; ?>
					<a href="index.php?option=com_akeebasubs&view=user&id=<?php echo $user_id ?>" class="title">	
					<strong><?php echo $this->escape($subscription->username)?></strong>
					<span class="small">[<?php echo $subscription->user_id?>]</span>
					<br/>
					<?php echo $this->escape($subscription->name)?>
					<? if(!empty($subscription->business_name)):?>
					<br/>
					<?php echo $this->escape($subscription->business_name)?>
					&bull;
					<?php echo $this->escape($subscription->vatnumber)?>
					<?php endif; ?>
					<br/>
					<?php echo $this->escape($subscription->email)?>
					</a>
				</span>
			</td>
			<td class="akeebasubs-subscription-paymentstatus">
				<span class="akeebasubs-payment akeebasubs-payment-<?php echo strtolower($subscription->state) ?> hasTip"
					title="<?php echo JText::_('COM_AKEEBASUBS_SUBSCRIPTION_STATE_'.$subscription->state)?>::<?php echo $subscription->processor?> &bull; <?php echo $subscription->processor_key?>"></span>
			</td>
			<td class="akeebasubs-subscription-amount">
				<?php if($subscription->net_amount > 0): ?>
				<span class="akeebasubs-subscription-netamount">
				<?php echo sprintf('%2.2f', (float)$subscription->net_amount) ?> <?php echo AkeebasubsHelperCparams::getParam('currencysymbol','€')?>
				</span>
				<span class="akeebasubs-subscription-taxamount">
				<?php echo sprintf('%2.2f', (float)$subscription->tax_amount) ?> <?php echo AkeebasubsHelperCparams::getParam('currencysymbol','€')?>
				</span>
				<?php endif; ?>
				<span class="akeebasubs-subscription-grossamount">
				<?php echo sprintf('%2.2f', (float)$subscription->gross_amount) ?> <?php echo AkeebasubsHelperCparams::getParam('currencysymbol','€')?>
				</span>
			</td>
			<td>
				<?php echo AkeebasubsHelperFormat::date($subscription->publish_up, '%Y-%m-%d %H:%M') ?>
			</td>
			<td>
				<?php echo AkeebasubsHelperFormat::date($subscription->publish_down, '%Y-%m-%d %H:%M') ?>
			</td>
			<td>
				<?php echo AkeebasubsHelperFormat::date($subscription->created_on, '%Y-%m-%d %H:%M') ?>
			</td>
			<td align="center">
				<?php echo JHTML::_('grid.published', $subscription, $i); ?>
			</td>
		</tr>
		<?php endforeach;?>
		<?php else: ?>
		<tr>
			<td colspan="20">
				<?php echo JText::_('COM_AKEEBASUBS_COMMON_NORECORDS') ?>
			</td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>
</form>

<div id="refreshMessage" style="display:none">
	<h3><?php echo JText::_('COM_AKEEBASUBS_SUBSCRIPTIONS_SUBREFRESH_TITLE');?></h3>
	<p><img id="asriSpinner" src="<?php echo JURI::base()?>../media/com_akeebasubs/images/throbber.gif" align="center" /></p>
	<p><span id="asriPercent">0</span><?php echo JText::_('COM_AKEEBASUBS_SUBSCRIPTIONS_SUBREFRESH_PROGRESS')?></p>
</div>

<script type="text/javascript">
var akeebasubs_token = "<?php echo JUtility::getToken();?>";

(function($) {
	$(document).ready(function(){
		// TODO!
		//$('#toolbar-subrefresh').click(akeebasubs_refresh_integrations);
	});
})(akeeba.jQuery);

window.addEvent('domready', function() {
	// TODO!
});

</script>