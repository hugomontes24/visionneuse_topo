<?php
 class PhotoController 
{
    public function __construct( 
        private PhotoRepository $PhotoRepository
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
        $a_photo = $this->PhotoRepository->getOne($id);

        if(empty($a_photo)){
            http_response_code(404);
            echo json_encode(['message' => 'Session not found with this id']);
            return;
        }
        // $Lesson = new Lesson(); // TODO à enlever ou retravailler
        // $Lesson->hydrate($a_lesson);
        switch($method){
            case 'GET':  // ** get one
                echo json_encode($a_photo);
                break;
            case 'PATCH':
                $data = (array) json_decode(file_get_contents('php://input'), true);
                $PatchPhoto = new PhotoPatchDTO();
                $PatchPhoto->hydrate($data);

                $errors = $this->getValidationErrors1($PatchPhoto);
                if(!empty($errors)){
                    http_response_code(422);
                    echo json_encode($errors);
                    break;
                }

                $rows = $this->PhotoRepository->update( $PatchPhoto );
                http_response_code(200);
                echo json_encode([
                    'message' => "Lesson id = $id modified",
                    'rows' => $rows
                ]);
                break;

        //     case 'DELETE':
        //         $this->LessonRepository->delete($id);
        //         http_response_code(204);
        //         break;
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCollectionRequest(string $method): void
    {        
        switch($method){
            case 'GET':
                $photos = $this->PhotoRepository->getAll();
               
                header('Content-Type: application/json');
                echo json_encode($photos);
                break;

            // case 'POST':
            //     $data = (array) json_decode(file_get_contents('php://input'), true);

            //     $Photo = new Photo(); // TODO à enlever ou retravailler
            //     $Photo->hydrate($data);

            //     $errors = $this->getValidationErrors($Photo);
            //     if(!empty($errors)){
            //         http_response_code(422);
            //         echo json_encode($errors);
            //         break;
            //     }

            //     $id = $this->PhotoRepository->create($Photo);
            //     http_response_code(201);
            //     echo json_encode([
            //         'message' => "Site created",
            //         'id' => $id
            //     ]);
            //     break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(Photo $Photo): array
    {
        $errors = [];
        // if($Photo->getLayer()===null){
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

    private function getValidationErrors1(PhotoPatchDTO $PhotoPatchDTO): array
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
                
    
   