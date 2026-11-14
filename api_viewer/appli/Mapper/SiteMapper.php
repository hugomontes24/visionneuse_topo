<?php

class SiteMapper
{
    public function dataToGetDTOArray($data): array
    {
        $sites = [];    
        foreach ($data as $site) {
            $sites[] = $this->dataToGetDTO($site);    
        }
        return $sites;    
    }

    public function dataToGetDTO($data): SiteGetDTO
    {
        $SiteGetDTO = new SiteGetDTO();
        $SiteGetDTO->hydrate($data);
        return $SiteGetDTO;                   
    }
}