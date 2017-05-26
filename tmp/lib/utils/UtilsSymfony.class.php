<?php

class UtilsSymfony{
    
    public static function isFromAdmin(){
        return sfContext::getInstance()->getConfiguration()->getApplication()=='backend';
    }

    public static function isFromClaim(){
        return sfContext::getInstance()->getConfiguration()->getApplication()=='claim';
    }
    
    public static function isFromCompliance(){
        return sfContext::getInstance()->getConfiguration()->getApplication()=='compliance';
    }
    
    public static function isFromCosting(){
        return sfContext::getInstance()->getConfiguration()->getApplication()=='costing';
    }    
    
    public static function isFromFabric(){
        return sfContext::getInstance()->getConfiguration()->getApplication()=='fabric';
    }    
        
    public static function isFromFs(){
        return sfContext::getInstance()->getConfiguration()->getApplication()=='fs';
    }      
}