<?php

class RasterMapper
{
    public function dataToGetDTOArray($data): array
    {
        $rasters = [];    
        foreach ($data as $raster) {
            $rasters[] = $this->dataToGetDTO($raster);    
        }
        return $rasters;    
    }

    public function dataToGetDTO($data): RasterGetDTO
    {
        $RasterGetDTO = new RasterGetDTO();
        $RasterGetDTO->hydrate($data);
        return $RasterGetDTO;                   
    }
}