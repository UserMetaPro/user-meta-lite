<?php

function userMetaLogin( $formName=null ) {
    global $userMeta;       
    
    return $userMeta->userLoginProcess( $formName );
}

function userMetaProfileRegister( $actionType, $formName, $rolesForms = null ) {
    global $userMeta;       
    
    return $userMeta->userUpdateRegisterProcess( $actionType, $formName, $rolesForms );
}

function userMetaFormBuilder( $actionType, $formName, $rolesForms = null ) {
    global $userMeta;       
    
    return $userMeta->userUpdateRegisterProcess( $actionType, $formName, $rolesForms );
}