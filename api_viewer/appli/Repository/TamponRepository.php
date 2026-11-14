<?php
class TamponRepository
{
    private string $table = 'tampons';

    public function getAllBySiteId(int $site_id): array
    {
         $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id, 
                        matricule,
                        site_id,
                        type,
                        reseau,
                        description,
                        rotation,
                        to_json(amont_ids) AS amont_ids,
                        to_json(aval_ids) AS aval_ids,
                        -- 1. Coordonnées natives CC47 (EPSG:3947) [X, Y, Z]
                        json_build_array(ST_X(geom), ST_Y(geom), ST_Z(geom)) AS coordinates_cc,
                     
                        -- 2. Coordonnées WGS84 (EPSG:4326) pour l'affichage Leaflet [Lng, Lat]
                        ST_AsGeoJSON(ST_Transform(geom, 4326))::json->'coordinates' AS coordinates_wgs84
                FROM " . $this->table . 
                " WHERE site_id = :site_id AND geom IS NOT NULL AND NOT ST_IsEmpty(geom)" ;
            $statement = $pg_PDO->prepare($query);
            $statement->bindValue(':site_id', $site_id, PDO::PARAM_INT);
            $statement->execute();
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            // var_dump($row);
           $row['amont_ids'] = ($row['amont_ids'] !== null) ? json_decode($row['amont_ids'], true) : null;
           $row['aval_ids'] = ($row['aval_ids'] !== null) ? json_decode($row['aval_ids'], true) : null;

           $row['coordinates_cc'] = ($row['coordinates_cc'] !== null) ? json_decode($row['coordinates_cc'], true) : [];
           $row['coordinates_wgs84'] = ($row['coordinates_wgs84'] !== null) ? json_decode($row['coordinates_wgs84'], true) : [];
           
            $tamponGetDTO = new TamponGetDTO();
            $tamponGetDTO->hydrate($row);
            
            $data[] = $tamponGetDTO->toArray();
        }
        return $data;
    }

  public function create(Tampon $Tampon): string
{
    $pg_PDO = PDOConnection::pg_get();
    
    // Si les coordonnées reçues du frontend (Leaflet) sont en WGS84 [lng, lat, z],
    // on force la conversion vers EPSG:3947 (CC47) avec ST_Transform.
    // Si tes coordonnées envoyées sont déjà en CC47 [X, Y, Z], remplace ST_Transform par ST_SetSRID.
    $query = "INSERT INTO " . $this->table . " 
                (matricule, site_id, type, reseau, description, rotation, amont_ids, aval_ids, geom)
            VALUES 
                (:matricule, :site_id, :type, :reseau, :description, :rotation, 
                 :amont_ids::integer[], 
                 :aval_ids::integer[],
                 ST_Transform(ST_SetSRID(ST_GeomFromGeoJSON(:geom), 4326), 3947)
                )";

    $statement = $pg_PDO->prepare($query);
    
    $statement->bindValue(':matricule', $Tampon->getMatricule(), PDO::PARAM_STR);
    $statement->bindValue(':site_id', $Tampon->getSiteId(), PDO::PARAM_INT);
    $statement->bindValue(':type', $Tampon->getType(), PDO::PARAM_INT);
    $statement->bindValue(':reseau', $Tampon->getReseau(), PDO::PARAM_INT);
    $statement->bindValue(':description', $Tampon->getDescription(), PDO::PARAM_STR);
    $statement->bindValue(':rotation', $Tampon->getRotation());

    // 1. Formatage des tableaux PHP [1, 2] vers le format PostgreSQL "{1,2}"
    $amontPgArray = '{' . implode(',', array_map('intval', $Tampon->getAmontIds())) . '}';
    $avalPgArray  = '{' . implode(',', array_map('intval', $Tampon->getAvalIds())) . '}';

    $statement->bindValue(':amont_ids', $amontPgArray, PDO::PARAM_STR);
    $statement->bindValue(':aval_ids', $avalPgArray, PDO::PARAM_STR);

    // 2. Construction de la structure GeoJSON standard (sans clé 'geom' englobante)
    $coords = $Tampon->getCoordinatesWgs84(); // Doit être un tableau [lng, lat] ou [lng, lat, z]
    
    $geoJSON = json_encode([
        'type' => 'Point',
        'coordinates' => array_map('floatval', $coords)
    ]);

    $statement->bindValue(':geom', $geoJSON, PDO::PARAM_STR);
    $statement->execute();

    return $pg_PDO->lastInsertId();
}

    public function getOne(int $id): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // On spécifie les colonnes et on transforme la géométrie comme dans le getAll
        $query = "SELECT id,
                        site_id, 
                        type, 
                        description, 
                        rotation, 
                        reseau,
                        ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates 
                FROM " . $this->table . " 
                WHERE id = :id";

        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        // Sécurité : si l'ID n'existe pas, fetch renvoie false
        if (!$row) {
            return [];
        }
        // Décodage des coordonnées (car PostgreSQL renvoie une chaîne JSON)
        $row['coordinates'] = json_decode($row['coordinates'], true);

        $tamponGetDTO = new TamponGetDTO();
        $tamponGetDTO->hydrate($row);

        return $tamponGetDTO->toArray();
    }

    public function getAll(): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id,
                        matricule,
                        site_id,    
                        type,
                        reseau,
                        description,
                        rotation,
                        to_json(amont_ids) AS amont_ids,
                        to_json(aval_ids) AS aval_ids,
                        -- 1. Coordonnées natives CC47 (EPSG:3947) [X, Y, Z]
                        json_build_array(ST_X(geom), ST_Y(geom), ST_Z(geom)) AS coordinates_cc,
                     
                        -- 2. Coordonnées WGS84 (EPSG:4326) pour l'affichage Leaflet [Lng, Lat]
                        ST_AsGeoJSON(ST_Transform(geom, 4326))::json->'coordinates' AS coordinates_wgs84
                FROM " . $this->table .  
                " WHERE geom IS NOT NULL AND NOT ST_IsEmpty(geom)" ;
        $statement = $pg_PDO->query($query);
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            // var_dump($row);  
            // Décodage des coordonnées (car PostgreSQL renvoie une chaîne JSON)
            $row['amont_ids'] = ($row['amont_ids'] !== null) ? json_decode($row['amont_ids'], true) : null;
            $row['aval_ids'] = ($row['aval_ids'] !== null) ? json_decode($row['aval_ids'], true) : null;
            $row['coordinates_cc'] = ($row['coordinates_cc'] !== null) ? json_decode($row['coordinates_cc'], true) : [];
            $row['coordinates_wgs84'] = ($row['coordinates_wgs84'] !== null) ? json_decode($row['coordinates_wgs84'], true) : [];
      
            $tamponGetDTO = new TamponGetDTO();
            $tamponGetDTO->hydrate($row);
            
            $data[] = $tamponGetDTO->toArray();
        }
        return $data;
    }

    public function update(TamponGetDTO $tamponPatch): int
    {
        $pg_PDO = PDOConnection::pg_get();

        $query = "UPDATE " . $this->table . " 
                    SET 
                        type = :type,
                        description = :description
                    WHERE id = :id";
        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':type', $tamponPatch->getType(), PDO::PARAM_STR);
        // $statement->bindValue(':classification', $tamponPatch->getClassification(), PDO::PARAM_INT);
        $statement->bindValue(':description', $tamponPatch->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(':id', $tamponPatch->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }

        public function delete(int $id): void
        {
            $pg_PDO = PDOConnection::pg_get();
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $statement = $pg_PDO->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }



}
