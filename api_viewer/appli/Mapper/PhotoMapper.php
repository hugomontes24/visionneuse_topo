<?php

class PhotoMapper
{
    public function dataToGetDTOArray($data): array
    {
        $photos = [];    
        foreach ($data as $photo) {
            $photos[] = $this->dataToGetDTO($photo);    
        }
        return $photos;    
    }

    public function dataToGetDTO($data): PhotoGetDTO
    {
        $PhotoGetDTO = new PhotoGetDTO();
        $PhotoGetDTO->hydrate($data);
        return $PhotoGetDTO;                   
    }
}