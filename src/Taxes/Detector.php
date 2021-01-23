<?php 
namespace App\Taxes;


class Detector{
    protected $seuil;


    public function  __construct(  $seuil  )
    {
        
        $this->seuil = $seuil;
    }
    
    public function detect(float $detector) : bool{
    
    if ($detector > $this->seuil){
    
        return true;
    }else{
        return  false;
    }
    
    }


}