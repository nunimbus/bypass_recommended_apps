<?php
/**
 * @copyright Copyright (c) 2022, Andrew Summers
 *
 * @author Andrew Summers
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\BypassRecommendedApps\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OC\AppFramework\Middleware\MiddlewareDispatcher;
use Psr\Container\ContainerInterface;
use OCA\BypassRecommendedApps\Middleware\RecommendedAppsControllerMiddleware;

class Application extends App implements IBootstrap {

	public const APP_ID = 'bypass_recommended_apps';

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);

		// Register middleware to the 'core' app
		$coreContainer = \OC::$server->getRegisteredAppContainer('core');

		$coreContainer->registerService(MiddlewareDispatcher::class, function (ContainerInterface $c) {
			$dispatcher = new MiddlewareDispatcher();

			$dispatcher->registerMiddleware(
				$c->get(OCA\BypassRecommendedApps\Middleware\RecommendedAppsControllerMiddleware::class)
			);

			return $dispatcher;
		});

		\OC::$server->get('OC\AppFramework\Middleware\MiddlewareDispatcher')->registerMiddleware(new RecommendedAppsControllerMiddleware());
	}

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context): void {
	}
}
