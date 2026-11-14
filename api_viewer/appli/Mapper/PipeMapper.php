<?php

class PipeMapper
{
    public function dataToGetDTOArray($data): array
    {
        $pipes = [];    
        foreach ($data as $pipe) {
            $pipes[] = $this->dataToGetDTO($pipe);    
        }
        return $pipes;    
    }

    public function dataToGetDTO($data): PipeGetDTO
    {
        $PipeGetDTO = new PipeGetDTO();
        $PipeGetDTO->hydrate($data);
        return $PipeGetDTO;                   
    }
}