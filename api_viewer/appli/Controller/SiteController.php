<?php
 class SiteController 
{
    public function __construct( 
        private SiteRepository $SiteRepository,
        private SiteMapper $SiteMapper
        ){}

    public function processRequest( string $method, ?int $id, ?string $more ) :void  // le point d'interrogation means nullable
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
        header('Content-Type: application/json; charset=UTF-8');
        $siteGetDTO = $this->SiteRepository->getOne($id);

        if($siteGetDTO === null){
            http_response_code(404);
            echo json_encode(['message' => 'Session not found with this id']);
            return;
        }
        // $Lesson = new Lesson(); // TODO à enlever ou retravailler
        // $Lesson->hydrate($a_lesson);
        switch($method){
            case 'GET':  // ** get one
                http_response_code(200);
                echo json_encode($siteGetDTO->toArray());
                break;
        //     case 'PATCH':
        //         $data = (array) json_decode(file_get_contents('php://input'), true);
        //         $NewLesson = new Lesson();
        //         $NewLesson->hydrate($data);

        //         $errors = $this->getValidationErrors($NewLesson);
        //         if(!empty($errors)){
        //             http_response_code(422);
        //             echo json_encode($errors);
        //             break;
        //         }
                
        //         $rows = $this->LessonRepository->update( $Lesson->getId(), $NewLesson );
        //         http_response_code(200);
        //         echo json_encode([
        //             'message' => "Lesson id = $id modified",
        //             'rows' => $rows
        //         ]);
        //         break;

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
            case 'GET': // ** get all
                
                $siteDTOs = $this->SiteRepository->getAll();
                // Transformation de chaque DTO en tableau associatif
                $data = array_map(fn(SiteGetDTO $dto) => $dto->toArray(), $siteDTOs);
                // var_dump($sites);
                header('Content-Type: application/json');
                echo json_encode($data);
                break;

            // case 'POST':
            //     $data = (array) json_decode(file_get_contents('php://input'), true);
                
            //     $Site = new Site(); // TODO à enlever ou retravailler
            //     $Site->hydrate($data);

            //     $errors = $this->getValidationErrors($Site);
            //     if(!empty($errors)){
            //         http_response_code(422);
            //         echo json_encode($errors);
            //         break;
            //     }

            //     $id = $this->SiteRepository->create($Site);
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
   
    
    private function getValidationErrors(Site $Site): array
    {
        $errors = [];
        if($Site->getName()===null){
            $errors['name'] = 'Name is required';
        }
    //    if($Site->getCity() < 1){    //   todo revalider ces controls
    //         $errors['city'] = 'City is required';
    //     }
    //     if($Site->getPosition() <1){
    //         $errors['position'] = 'Position is required';
    //     }
       
        return $errors;
    }

    function isDateTimeValid($dateTime): bool
    {
        $DateObject = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        return ($DateObject instanceof DateTime);        
    }
}

    // public function processResourceReservationRequest(string $method, string $id, ?int $user_id): void    
    // {
    //     // 1 verifier que la lesson existe
    //     $a_lesson = $this->LessonRepository->getOne($id);
    //     if(empty($a_lesson)){
    //         http_response_code(404);
    //         echo json_encode(['message' => 'Lesson not found with this id']);
    //         return;
    //     }
    //     $LessonUserRepository = new LessonUserRepository();
    //     $Lesson = new Lesson(); // TODO à enlever ou retravailler
    //     $Lesson->hydrate($a_lesson);
    //     switch($method){
    //         case 'POST':
    //             $data = (array) json_decode(file_get_contents('php://input'), true);
    //              // 2 verifier que le user existe
    //              $UserRepository = new UserRepository();
    //              $a_user = $UserRepository->getOneByEmail($data['email']);
    //              if(empty($a_user)){
    //                  // 3 else creer l user et recuperer id
    //                 $User = new User();
    //                 $User->hydrate($data);
    //                 $errors = $this->getValidationErrorsUser($User);
    //                 if(!empty($errors)){
    //                     http_response_code(422);
    //                     echo json_encode($errors);
    //                     break;    
    //                 }   
    //                 //$User->setPassword(password_hash($User->getPassword(), PASSWORD_DEFAULT));
    //                 $a_user['id'] = $UserRepository->create($User);                
    //              }
    //             // 4 ajouter l user a la lesson
    //             $LessonUser = new LessonUser($id, $a_user['id'], false);
    //             $lessonUserId = $LessonUserRepository->create($LessonUser);
                
    //             http_response_code(201);
    //             echo json_encode($a_user); // envoyer l user pour rafraichir les reservations
    //             break;

    //         case 'DELETE':
    //             $LessonUserRepository->deleteWithIds($id, $user_id);
    //             http_response_code(204);
    //             break;

    //         default:
    //             http_response_code(405);
    //             header("Allow: POST, DELETE");
    //         }   
    // }