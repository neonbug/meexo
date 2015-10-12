<?php namespace Neonbug\Common\Repositories;

class GoogleAnalyticsRepository {
	
	protected $service_account_email;
	protected $key_file_location;
	protected $api_client;
	
	function __construct($service_account_email, $key_file_location)
	{
		$this->service_account_email = $service_account_email;
		$this->key_file_location     = $key_file_location;
	}
	
	protected function getApiClient()
	{
		if ($this->api_client == null)
		{
			$service_account_email = $this->service_account_email;
			$key_file_location = $this->key_file_location;
			
			$client = new \Google_Client();
			$client->setApplicationName("HelloAnalytics");
			$analytics = new \Google_Service_Analytics($client);
			
			$key = file_get_contents($key_file_location);
			$cred = new \Google_Auth_AssertionCredentials(
				$service_account_email,
				array(\Google_Service_Analytics::ANALYTICS_READONLY),
				$key
			);
			$client->setAssertionCredentials($cred);
			if ($client->getAuth()->isAccessTokenExpired())
			{
				$client->getAuth()->refreshTokenWithAssertion($cred);
			}
			
			$this->api_client = $analytics;
		}
		
		return $this->api_client;
	}
	
	protected function getFirstProfileId(&$analytics)
	{
		$accounts = $analytics->management_accounts->listManagementAccounts();
		
		if (sizeof($accounts->getItems()) > 0)
		{
			$items = $accounts->getItems();
			$first_account_id = $items[0]->getId();
			
			$properties = $analytics->management_webproperties->listManagementWebproperties($first_account_id);
			
			if (sizeof($properties->getItems()) > 0)
			{
				$items = $properties->getItems();
				$first_property_id = $items[0]->getId();
				
				$profiles = $analytics->management_profiles
					->listManagementProfiles($first_account_id, $first_property_id);
				
				if (sizeof($profiles->getItems()) > 0)
				{
					$items = $profiles->getItems();
					
					return $items[0]->getId();
				} else {
					throw new Exception('No views (profiles) found for this user.');
				}
			} else {
				throw new Exception('No properties found for this user.');
			}
		} else {
			throw new Exception('No accounts found for this user.');
		}
	}
	
	//41534519 -> 73241329
	protected function getProfileIdByTrackingId(&$analytics, $tracking_id)
	{
		$accounts = $analytics->management_accounts->listManagementAccounts();
		
		foreach ($accounts->getItems() as $item)
		{
			$account_id = $item->getId();
			if ($account_id != $tracking_id) continue;
			
			$properties = $analytics->management_webproperties->listManagementWebproperties($account_id);
			
			if (sizeof($properties->getItems()) > 0)
			{
				$items = $properties->getItems();
				$first_property_id = $items[0]->getId();
				
				$profiles = $analytics->management_profiles
					->listManagementProfiles($account_id, $first_property_id);
				
				if (sizeof($profiles->getItems()) > 0)
				{
					$items = $profiles->getItems();
					return $items[0]->getId();
				}
			}
		}
		
		return null;
	}
	
	protected function getResults(&$analytics, $profile_id)
	{
	}
	
	public function getDailyForLastThirtyDays($tracking_id)
	{
		$client = $this->getApiClient();
		
		$profile_id = $this->getProfileIdByTrackingId($client, $tracking_id);
		
		$results = $client->data_ga->get(
			'ga:' . $profile_id, 
			date('Y-m-d', time() - 30*24*60*60), 
			'yesterday', 
			'ga:sessions,ga:pageviews', 
			[ 'dimensions' => 'ga:date' ]
		);
		
		$rows = $results->getRows();
		$items = [];
		foreach ($rows as $row)
		{
			if (mb_strlen($row[0]) != 8) continue;
			
			//$date = mb_substr($row[0], 0, 4) . '-' . mb_substr($row[0], 4, 2) . '-' . mb_substr($row[0], 6, 2);
			
			$items[] = [ 
				'date'     => $row[0], //$date, 
				'sessions' => intval($row[1]), 
				'views'    => intval($row[2])
			];
		}
		
		return $items;
	}
	
}
