<?php

class ZoneMapper
{
    public function dataToGetDTOArray($data): array
    {
        $zones = [];    
        foreach ($data as $zone) {
            $zones[] = $this->dataToGetDTO($zone);    
        }
        return $zones;    
    }

    public function dataToGetDTO($data): ZoneGetDTO
    {
        $ZoneGetDTO = new ZoneGetDTO();
        $ZoneGetDTO->hydrate($data);
        return $ZoneGetDTO;                   
    }
}