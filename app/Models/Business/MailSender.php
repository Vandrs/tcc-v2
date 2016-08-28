<?php 

namespace App\Models\Business;

use Mail;

class MailSender{
	public static function send($mailData){
        Mail::send($mailData['view'],$mailData,function($mailClass) use (&$mailData){
            if(isset($mailData['sender'])){
                $mailClass->sender($mailData['sender']['address'], $mailData['sender']['name']);
            }
            $mailClass->to($mailData['to']['address'],$mailData['to']['name']);
            if(isset($mailData['replyTo'])){
                $mailClass->replyTo($mailData['replyTo']['address'], $mailData['replyTo']['name']);
            }
            $mailClass->subject($mailData['subject']);
            if(isset($mailData['attachments'])){
                foreach($mailData['attachments'] as $attachment){
                    $mailClass->attach($attachment);
                }
            }
        });
	}
}