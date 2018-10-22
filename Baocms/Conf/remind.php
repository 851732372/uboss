<?php
						$dbconfigs = require  BASE_PATH.'/'.APP_NAME.'/Conf/config.php';
$remind =  array(
						    'SET_REMIND' => 0.6,
						);
						return array_merge($remind,$dbconfigs);
					?>