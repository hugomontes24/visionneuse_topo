
<?php
class PhotoRepository
{
    private string $table = 'photos';
    // public function create(Photo $Photo): string
    // {
    //     $pg_PDO = PDOConnection::pg_get();

    //     $query = "INSERT INTO " . $this->table. " 
    //                     (layer) 
    //                 VALUES 
    //                     (:layer)";
    //     $statement = $pg_PDO->prepare($query);
    //     $statement->bindValue(':layer', $Photo->getLayer(), PDO::PARAM_STR);
    //     $statement->execute();
    //     return $pg_PDO->lastInsertId();
    // }

    public function getOne(int $id): array   // GET   ID
    {
        $pg_PDO = PDOConnection::pg_get();
        
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $data = [];
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        $SolidGetDTO = new PhotoGetDTO();
        $SolidGetDTO->hydrate($data);

        return $SolidGetDTO->toArray();
    }


    public function getAll(): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id, 
                        name, 
                        ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates,
                        etat, 
                        description
                FROM " . $this->table ;
        $statement = $pg_PDO->query($query);
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            $row['coordinates'] = json_decode($row['coordinates'], true);
            $PhotoGetDTO = new PhotoGetDTO();
            $PhotoGetDTO->hydrate($row);
            
            $data[] = $PhotoGetDTO->toArray();
        }
        return $data;
    }

    public function update(PhotoPatchDTO $photoPatch): int
    {
        $pg_PDO = PDOConnection::pg_get();

        $query = "UPDATE " . $this->table . " 
                    SET 
                        etat = :etat,
                        description = :description
                    WHERE id = :id";
        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':etat', $photoPatch->getEtat(), PDO::PARAM_INT);
        $statement->bindValue(':description', $photoPatch->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(':id', $photoPatch->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }



}
