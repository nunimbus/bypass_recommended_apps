<?php

namespace OCA\BypassRecommendedApps\Middleware;

use OCP\AppFramework\Middleware;
use OCP\AppFramework\Http\Response;
use OC\Core\Controller\RecommendedAppsController;
use OC;

class RecommendedAppsControllerMiddleware extends Middleware {
	public function __construct() {
	}

	public function beforeOutput($controller, $methodName, $output){
		return $output;
	}

	public function beforeController($controller, $methodName) {
	}

	public function afterController($controller, $methodName, Response $response): Response {
		if (! ($controller instanceof RecommendedAppsController)) {
			return $response;
		}

		$response->addHeader('Location', '/');
		$response->setStatus(302);

		\OC::$server->getAppManager()->disableApp('bypass_recommended_apps');
		$installer = OC::$server->get('OC\Installer');
		$installer->removeApp('bypass_recommended_apps');
	
		return $response;
	}
}