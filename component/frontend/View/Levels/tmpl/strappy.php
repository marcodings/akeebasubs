<?php
/**
 *  @package AkeebaSubs
 *  @copyright Copyright (c)2010-2015 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die();

use \Akeeba\Subscriptions\Admin\Helper\ComponentParams;
use \Akeeba\Subscriptions\Admin\Helper\Image;
use \Akeeba\Subscriptions\Admin\Helper\Message;

/** @var \Akeeba\Subscriptions\Site\View\Levels\Html $this */

$discounts = array();
?>

<div id="akeebasubs" class="levels">

<?php echo $this->getContainer()->template->loadPosition('akeebasubscriptionslistheader')?>

<?php $max = count($this->items); $width = count($this->items) ? (100/count($this->items)) : '100' ?>

	<table class="table table-striped table-condensed table-bordered">
		<tr>
		<?php foreach($this->items as $level):?>
			<td class="akeebasubs-strappy-level" width="<?php echo $width?>%">
				<a href="<?php echo \JRoute::_('index.php?option=com_akeebasubs&view=level&layout=default&format=html&slug='.$level->slug)?>" class="akeebasubs-strappy-level-link">
					<?php echo $this->escape($level->title)?>
				</a>
			</td>
		<?php endforeach ?>
		</tr>
		<tr>
		<?php foreach($this->items as $level):
			$signupFee = 0;

			if (!in_array($level->akeebasubs_level_id, $this->subIDs) && ($this->includeSignup != 0))
			{
				$signupFee = (float)$level->signupfee;
			}

			$vatRule = $this->taxModel->getTaxRule($level->akeebasubs_level_id, $this->taxParams['country'], $this->taxParams['state'], $this->taxParams['city'], $this->taxParams['vies']);
			$vatMultiplier = (100 + (float)$vatRule->taxrate) / 100;

			if ($this->includeDiscount)
			{
				/** @var \Akeeba\Subscriptions\Site\Model\Subscribe $subscribeModel */
				$subscribeModel = $this->getContainer()->factory->model('Subscribe')->savestate(0);
				$subscribeModel->setState('id', $level->akeebasubs_level_id);
				$subValidation = $subscribeModel->validatePrice(true);
				$discount = $subValidation->discount;
				$levelPrice = $level->price - $discount;

				$formatedPriceD = sprintf('%1.02F', $level->price);
				$dotposD = strpos($formatedPriceD, '.');
				$price_integerD = substr($formatedPriceD,0,$dotposD);
				$price_fractionalD = substr($formatedPriceD,$dotposD+1);
			}
			else
			{
				$discount = 0;
				$levelPrice = $level->price;
			}

			$discounts[$level->akeebasubs_level_id] = $discount;

			if ($this->includeSignup == 1)
			{
				if (($levelPrice + $signupFee) < 0)
				{
					$levelPrice = -$signupFee;
				}

				$formatedPrice = sprintf('%1.02F', ($levelPrice + $signupFee) * $vatMultiplier);
				$levelPrice += $signupFee;
			}
			else
			{
				if ($levelPrice < 0)
				{
					$levelPrice = 0;
				}

				$formatedPrice = sprintf('%1.02F', ($levelPrice) * $vatMultiplier);
			}

			$dotpos = strpos($formatedPrice, '.');
			$price_integer = substr($formatedPrice,0,$dotpos);
			$price_fractional = substr($formatedPrice,$dotpos+1);?>
			<td class="akeebasubs-strappy-price">
				<?php if($this->renderAsFree && ($levelPrice < 0.01)):?>
				<?php echo JText::_('COM_AKEEBASUBS_LEVEL_LBL_FREE') ?>
				<?php else: ?>
				<?php if(ComponentParams::getParam('currencypos','before') == 'before'): ?><span class="akeebasubs-strappy-price-currency"><?php echo ComponentParams::getParam('currencysymbol','€')?></span><?php endif; ?><span class="akeebasubs-strappy-price-integer"><?php echo $price_integer ?></span><?php if((int)$price_fractional > 0): ?><span class="akeebasubs-strappy-price-separator">.</span><span class="akeebasubs-strappy-price-decimal"><?php echo $price_fractional ?></span><?php endif; ?><?php if(ComponentParams::getParam('currencypos','before') == 'after'): ?><span class="akeebasubs-strappy-price-currency"><?php echo ComponentParams::getParam('currencysymbol','€')?></span><?php endif; ?>
				<?php endif; ?>
				<?php if (((float)$vatRule->taxrate > 0.01) && ($levelPrice > 0.01)): ?>
					<div class="akeebasubs-strappy-taxnotice">
						<?php echo JText::sprintf('COM_AKEEBASUBS_LEVELS_INCLUDESVAT', (float)$vatRule->taxrate); ?>
					</div>
				<?php endif; ?>
			</td>
		<?php endforeach ?>
		</tr>

		<?php if ($this->includeDiscount): ?>
		<tr>
			<?php foreach($this->items as $level):
				$discount = 0;

				if (array_key_exists($level->akeebasubs_level_id, $discounts))
				{
					$discount = (float)$discounts[$level->akeebasubs_level_id];
				}

				$vatRule = $this->taxModel->getTaxRule($level->akeebasubs_level_id, $this->taxParams['country'], $this->taxParams['state'], $this->taxParams['city'], $this->taxParams['vies']);
				$vatMultiplier = (100 + (float)$vatRule->taxrate) / 100;

				$formatedPrice = sprintf('%1.02F', $level->price * $vatMultiplier);
				$dotpos = strpos($formatedPrice, '.');
				$price_integer = substr($formatedPrice,0,$dotpos);
				$price_fractional = substr($formatedPrice,$dotpos+1);
				?>
				<td class="akeebasubs-strappy-prediscount">
					<?php if(abs($discount) >= 0.01): ?>
						<span class="akeebasubs-strappy-prediscount-label">
						<?php echo JText::_('COM_AKEEBASUBS_LEVEL_FIELD_PREDISCOUNT'); ?>
						</span>
						<s>
						<?php if(ComponentParams::getParam('currencypos','before') == 'before'): ?><span class="akeebasubs-strappy-price-currency"><?php echo ComponentParams::getParam('currencysymbol','€')?></span><?php endif; ?><span class="akeebasubs-strappy-price-integer"><?php echo $price_integer ?></span><?php if((int)$price_fractional > 0): ?><span class="akeebasubs-strappy-price-separator">.</span><span class="akeebasubs-strappy-price-decimal"><?php echo $price_fractional ?></span><?php endif; ?><?php if(ComponentParams::getParam('currencypos','before') == 'after'): ?><span class="akeebasubs-strappy-price-currency"><?php echo ComponentParams::getParam('currencysymbol','€')?></span><?php endif; ?>
						</s>
					<?php endif; ?>
				</td>
			<?php endforeach ?>
		</tr>
		<?php endif; ?>

		<?php if ($this->includeSignup == 2): ?>
		<tr>
			<?php foreach($this->items as $level):
				$signupFee = 0;
				if (!in_array($level->akeebasubs_level_id, $this->subIDs))
				{
					$signupFee = (float)$level->signupfee;
				}

				$vatRule = $this->taxModel->getTaxRule($level->akeebasubs_level_id, $this->taxParams['country'], $this->taxParams['state'], $this->taxParams['city'], $this->taxParams['vies']);
				$vatMultiplier = (100 + (float)$vatRule->taxrate) / 100;

				$formatedPrice = sprintf('%1.02F', $signupFee * $vatMultiplier);
				$dotpos = strpos($formatedPrice, '.');
				$price_integer = substr($formatedPrice,0,$dotpos);
				$price_fractional = substr($formatedPrice,$dotpos+1);
			?>
			<td class="akeebasubs-strappy-signupfee">
				<?php if(abs($signupFee) >= 0.01): ?>
				<?php echo JText::_('COM_AKEEBASUBS_LEVEL_FIELD_SIGNUPFEE_LIST'); ?>
				<?php if(ComponentParams::getParam('currencypos','before') == 'before'): ?><span class="akeebasubs-strappy-price-currency"><?php echo ComponentParams::getParam('currencysymbol','€')?></span><?php endif; ?><span class="akeebasubs-strappy-price-integer"><?php echo $price_integer ?></span><?php if((int)$price_fractional > 0): ?><span class="akeebasubs-strappy-price-separator">.</span><span class="akeebasubs-strappy-price-decimal"><?php echo $price_fractional ?></span><?php endif; ?><?php if(ComponentParams::getParam('currencypos','before') == 'after'): ?><span class="akeebasubs-strappy-price-currency"><?php echo ComponentParams::getParam('currencysymbol','€')?></span><?php endif; ?>
				<?php endif; ?>
			</td>
			<?php endforeach ?>
		</tr>
		<?php endif; ?>

		<tr>
		<?php foreach($this->items as $level):?>
			<td class="akeebasubs-strappy-image">
				<img src="<?php echo Image::getURL($level->image)?>" />
			</td>
		<?php endforeach ?>
		</tr>
		<tr>
		<?php foreach($this->items as $level):?>
			<td class="akeebasubs-strappy-description">
				<?php echo JHTML::_('content.prepare', Message::processLanguage($level->description) );?>
			</td>
		<?php endforeach ?>
		</tr>
		<tr>
		<?php foreach($this->items as $level):?>
			<td class="akeebasubs-strappy-subscribe">
				<button
					class="btn btn-inverse btn-primary"
					onclick="window.location='<?php echo \JRoute::_('index.php?option=com_akeebasubs&view=level&slug='.$level->slug.'&format=html&layout=default')?>'">
					<?php echo JText::_('COM_AKEEBASUBS_LEVELS_SUBSCRIBE')?>
				</button>
			</td>
		<?php endforeach ?>
		</tr>
	</table>

<?php echo $this->getContainer()->template->loadPosition('akeebasubscriptionslistfooter')?>
</div>