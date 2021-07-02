{*
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


{if $form_submitted eq 1}
	<div class="alert alert-success" role="alert">
		{l s='Settings were updated successfuly.' mod='tiphiopsprometheus'}<br />
	</div>
{/if}


<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Usage' mod='tiphiopsprometheus'}</h3>

	{l s='This module allows you to export metrics from your prestashop shop in the standard prometheus format. You can scrap theses metrics from any prometheus install you have, just like you would any other importer.' mod='tiphiopsprometheus'}

	{l s='For security reasons, and to avoid exposing your data to the whole world, you are required to use basic auth. You can - and should - customize the credentials using the form below.' mod='tiphiopsprometheus'}

	{l s='To use this expoter, simply add the following line to your prometheus.yml config file:' mod='tiphiopsprometheus'}

	<br />

	<code>
		<pre>  - job_name: 'your-job-name'
    static_configs:
    - targets: ['{$hostname}:443']
	scheme: https
    metrics_path: '{$metrics_path}'
    basic_auth:
      username: '&lt;Your Basic Auth Username&gt;'
      password: '&lt;Your Basic Auth Password&gt;'</pre>
	</code>
</div>
