<?php

class TamponMapper
{
    public function dataToGetDTOArray($data): array
    {
        $tampons = [];    
        foreach ($data as $tampon) {
            $tampons[] = $this->dataToGetDTO($tampon);    
        }
        return $tampons;    
    }

    public function dataToGetDTO($data): TamponGetDTO
    {
        $TamponGetDTO = new TamponGetDTO();
        $TamponGetDTO->hydrate($data);
        return $TamponGetDTO;                   
    }
}