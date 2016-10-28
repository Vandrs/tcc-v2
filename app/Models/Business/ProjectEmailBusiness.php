<?php 

namespace App\Models\Business;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Business\MailBusiness;
use App\Models\Enums\EnumQueues;
use App\Jobs\SendEmailJob;



class ProjectEmailBusiness {

	use DispatchesJobs;

	public function feedNotificationEmail($project){
		$self =  $this;
		$project->getFollowers()->each(function($user) use ($project, $self){
			$data = $self->buildFeedNotificationData($project, $user);
			$mailBusiness = new MailBusiness($data);
			if($mailBusiness->isValid()){
				$job = (new SendEmailJob($data))->onQueue(EnumQueues::EMAIL);
				$self->dispatch($job);
			}
		});
	}

	public function newValidationNotificationEmail($project)
	{

	}

	private function buildFeedNotificationData($project, $user){
		return [
			'to'      	  => ['address' => $user->email, 'name' => $user->name],
			'subject' 	  => 'Nova atualização de projeto.',
			'view' 	  	  => 'emails.feed-notification',
			'view_data'   => [
				'project_url'  => $project->url,
				'project_name' => $project->title,
				'user_name'    => $user->name,
			]
		];
	}



}