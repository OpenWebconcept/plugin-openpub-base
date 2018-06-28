<?php

namespace OWC\OpenPub\Base\Template;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->plugin->loader->addAction('template_redirect', $this, 'redirectAllButAdmin', 10);
	}

	public function redirectAllButAdmin()
	{
		if ( ! ( is_admin() || wp_doing_ajax() || is_feed() ) ) {

			if ( wp_redirect('https://www.openwebconcept.nl/') ) {
				exit();
			};
		}
	}
}