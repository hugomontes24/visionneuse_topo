<?php

class SiteRepository
{
    private string $table = 'sites';
    // public function create(Site $Site): string
    // {
    //     $pg_PDO = PDOConnection::pg_get();

    //     $query = "INSERT INTO " . $this->table. " 
    //                     (name, city, img, points_id) 
    //                 VALUES 
    //                     (:name, :city, :img, :points_id)";
    //     $statement = $pg_PDO->prepare($query);
    //     $statement->bindValue(':name', $Site->getName(), PDO::PARAM_STR);
    //     $statement->bindValue(':city', $Site->getCity(), PDO::PARAM_STR);
    //     $statement->bindValue(':img', $Site->getImg(), PDO::PARAM_STR);
    //     $statement->bindValue(':points_id', $Site->getPointsId(), PDO::PARAM_INT);
    //     $statement->execute();
    //     return $pg_PDO->lastInsertId();
    // }


    public function getOne(string $id): ?SiteGetDTO  // GET   ID
    {
        $pg_PDO = PDOConnection::pg_get();
        
        $query = "SELECT  
                    id,
                    name,
                    city,
                    img,
                    ST_AsGeoJSON(geom) AS geojson,
                    CASE WHEN geom IS NULL OR ST_IsEmpty(geom) THEN NULL
                         ELSE json_build_array(ST_X(ST_Transform(geom, 3944)), ST_Y(ST_Transform(geom, 3944)), ST_Z(geom))
                    END AS coordinates_cc,
                    ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates_wgs84
                FROM ". $this->table . " 
                WHERE id = :id";

        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        // $data = [];
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        // Si aucun résultat trouvé en BDD, on retourne null
        if (!$data) {
            return null;
        }
        if ($data['coordinates_cc'] !== null) {
            $data['coordinates_cc'] = json_decode($data['coordinates_cc'], true);
        } else {
            $data['coordinates_cc'] = [];
        }
        if ($data['coordinates_wgs84'] !== null) {
            $data['coordinates_wgs84'] = json_decode($data['coordinates_wgs84'], true);
        } else {
            $data['coordinates_wgs84'] = [];
        }
        $SiteGetDTO = new SiteGetDTO();
        $SiteGetDTO->hydrate($data);

        return $SiteGetDTO;
    }

  public function getAll(): array
{
    $pg_PDO = PDOConnection::pg_get();

    $query = "SELECT 
                id,
                name,
                city,
                img,
                ST_AsGeoJSON(geom) AS geojson,
                CASE WHEN geom IS NULL OR ST_IsEmpty(geom) THEN NULL
                     ELSE json_build_array(ST_X(ST_Transform(geom, 3944)), ST_Y(ST_Transform(geom, 3944)), ST_Z(geom))
                END AS coordinates_cc,
                ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates_wgs84
              FROM " . $this->table;

    $statement = $pg_PDO->query($query);
    $sites = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        if ($row['coordinates_cc'] !== null) {
            $row['coordinates_cc'] = json_decode($row['coordinates_cc'], true);
        } else {
            $row['coordinates_cc'] = [];
        }
        if ($row['coordinates_wgs84'] !== null) {
            $row['coordinates_wgs84'] = json_decode($row['coordinates_wgs84'], true);
        } else {
            $row['coordinates_wgs84'] = [];
        }
        $siteGetDTO = new SiteGetDTO();
        $siteGetDTO->hydrate($row);
        
        // Option A (Recommandée) : Conserver des objets DTO dans le tableau
        $sites[] = $siteGetDTO;

    }

    return $sites;
}
    // public function getAllByIdCategory(int $idCategory): array
    // {
    //     $oPDO = PDOConnection::get();

    //     $query = "SELECT * FROM lesson
    //         WHERE id_category = :idCategory";

    //     $statement = $oPDO->prepare($query);
    //     $statement->bindValue(':idCategory', $idCategory, PDO::PARAM_INT);
    //     $statement->execute();

    //     $data = [];
    //     $userRepository = new UserRepository();
    //     while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    //         if($row === false){
    //             break;
    //         }
    //          // retrouver tous les users associés à la lesson
    //         $queryUsers = "SELECT * FROM lesson_user WHERE lesson_id = :id";
    //         $statementUser = $oPDO->prepare($queryUsers);
    //         $statementUser->bindValue(':id', $row["id"], PDO::PARAM_INT);
    //         $statementUser->execute();
            
    //         while($row1 = $statementUser->fetch(PDO::FETCH_ASSOC)){ 
    //             $a_user = $userRepository->getOne($row1["user_id"]); 
    //             if(!empty($a_user)){
    //                 $a_user["reservation_id"] = $row1["id"]; // id lesson_user = reservation_id
    //                 $row["reservations"][$row1["id"]] = $a_user;
    //                 // $row["users"][] = $a_user;
    //             }
    //         }
    //         $Lesson = new LessonGetDTO();
    //         $Lesson->hydrate($row);
            
    //         $data[] = $Lesson->toArray();
    //     }
    //     return $data;
    // }

  
    // public function update(int $lessonId, Lesson $NewLesson): int
    // {
    //     $oPDO = PDOConnection::get();

    //     $query = "UPDATE lesson 
    //                 SET 
    //                     duration = :duration,
    //                     date = :date,
    //                     price = :price,
    //                     nb_max_users = :nbMaxUsers,
    //                     id_category = :idCategory
    //                 WHERE id = :id";
    //     $statement = $oPDO->prepare($query);
    //     $statement->bindValue(':duration', $NewLesson->getDuration(), PDO::PARAM_INT);
    //     $statement->bindValue(':date', $NewLesson->getDate()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
    //     $statement->bindValue(':price', $NewLesson->getPrice(), PDO::PARAM_INT);
    //     $statement->bindValue(':nbMaxUsers', $NewLesson->getNbMaxUsers(), PDO::PARAM_INT);
    //     $statement->bindValue(':idCategory', $NewLesson->getIdCategory(), PDO::PARAM_INT);
    //     $statement->bindValue(':id', $lessonId, PDO::PARAM_INT);
    //     $statement->execute();
    //     return $statement->rowCount();
    // }

    // public function delete(string $id): int
    // {
    //     $oPDO = PDOConnection::get();

    //     $query = "DELETE FROM lesson WHERE id = :id";
    //     $statement = $oPDO->prepare($query);
    //     $statement->bindValue(':id', $id, PDO::PARAM_INT);
    //     $statement->execute();
        
    //     return $statement->rowCount();
    // }


}


