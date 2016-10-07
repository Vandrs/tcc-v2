<?php

namespace App\Models\Enums;


class EnumCapabilities
{
    const CREATE_PROJECT 	   				 = 'crete-project';
    const UPDATE_PROJECT 	   				 = 'update-project';
    const DELETE_PROJECT 	   				 = 'delete-project';
    const FOLLOW_PROJECT 	   				 = 'follow-project';
    const MAKE_POST_PROJECT    				 = 'make-post-project';
    const MANAGE_PROJECT	   				 = 'manage-project';
    CONST MANAGE_PROJECT_USERS 				 = 'manage-project-users';
    CONST PROJECT_OWNER		   				 = 'project-owner'; 
    const REMOVE_AND_MANAGE_PROJECT_PROFILES = 'remove-manage-project-profiles';
}