<?php
 class ZoneController 
{
    public function __construct( 
        private ZoneRepository $ZoneRepository
        ){}

    public function processRequest( string $method, ?int $id, ?string $email ) :void  // le point d'interrogation means nullable
    {
        if($id){  // single resource
            $this->processResourceRequest($method,$id);
            return;
        }
        // collection
        $this->processCollectionRequest($method);  
    }


    public function processResourceRequest(string $method, string $id): void    
    {
        $a_zone = $this->ZoneRepository->getOne($id);

        if(empty($a_zone)){
            http_response_code(404);
            echo json_encode(['message' => 'Session not found with this id']);
            return;
        }
        // $Lesson = new Lesson(); // TODO à enlever ou retravailler
        // $Lesson->hydrate($a_lesson);
        switch($method){
            case 'GET':  // ** get one
                echo json_encode($a_zone);
                break;
            case 'PATCH':
                $data = (array) json_decode(file_get_contents('php://input'), true);
                
                $zonePatch = new ZoneGetDTO();
                $zonePatch->hydrate($a_zone);
                $zonePatch->setClassification($data["classification"]);
                $zonePatch->setName($data["name"]);
                $zonePatch->setDescription($data["description"]);

                $errors = $this->getValidationErrors1($zonePatch);
                if(!empty($errors)){
                    http_response_code(422);
                    echo json_encode($errors);
                    break;
                }

                $rows = $this->ZoneRepository->update( $zonePatch );
                http_response_code(200);
                echo json_encode([
                    'message' => "Zone id = $id modified",
                    'rows' => $rows
                ]);
                break;

            case 'DELETE':
                $this->ZoneRepository->delete($id);
                http_response_code(204);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCollectionRequest(string $method): void
    {        
        switch($method){
            case 'GET':   // get all
                $zones = $this->ZoneRepository->getAll();
               
                header('Content-Type: application/json');
                // var_dump($zones);
                echo json_encode($zones);
                break;

            case 'POST':    // create
                $data = (array) json_decode(file_get_contents('php://input'), true);

                $Zone = new Zone(); // TODO à enlever ou retravailler
                $Zone->hydrate($data);

                $errors = $this->getValidationErrors($Zone);
                if(!empty($errors)){
                    http_response_code(422);
                    echo json_encode($errors);
                    break;
                }

                $id = $this->ZoneRepository->create($Zone);
                http_response_code(201);
                echo json_encode([
                    'message' => "Zone created",
                    'id' => $id
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(Zone $Zone): array
    {
        $errors = [];
        // if($Zone->getLayer()===null){
        //     $errors['layer'] = 'Layer is required';
        // }
    //    if($Site->getCity() < 1){    //   todo revalider ces controls
    //         $errors['city'] = 'City is required';
    //     }
    //     if($Site->getPosition() <1){
    //         $errors['position'] = 'Position is required';
    //     }
        return $errors;
    }

    private function getValidationErrors1(ZoneGetDTO $zoneGetDTO): array
    {
        $errors = [];
        // if($PhotoPatchDTO->getLayer()===null){
        //     $errors['layer'] = 'Layer is required';
        // }
        // if($PhotoPatchDTO->getDateTime() !== null && !$this->isDateTimeValid($PhotoPatchDTO->getDateTime())){
        //     $errors['dateTime'] = 'DateTime format is invalid. Expected format: Y-m-d H:i:s';
        // }        
        return $errors;
    }




    function isDateTimeValid($dateTime): bool
    {
        $DateObject = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        return ($DateObject instanceof DateTime);        
    }





}


 // foreach($photos as &$p){
                //     foreach($p['geom']['coordinates'] as &$line){
                //         foreach($line as &$pos){
                //             if(!is_finite($pos[2])) $pos[2] = null;
                //         }
                //     }
                // }
                
    
   